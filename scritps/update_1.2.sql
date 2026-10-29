-- Eliminacion de retiros (type_movement_id = 2) duplicados y proteccion contra nuevos.
--
-- Por cada envio (id_ref) solo debe existir una entrega (tipo 1) y un retiro (tipo 2).
-- Hasta ahora un mismo codigo podia retirarse varias veces y cada vez se insertaba
-- otro tipo 2. Este script conserva el retiro mas antiguo de cada envio, borra el
-- resto y crea un indice unico (id_ref, type_movement_id) para que la BD rechace
-- nuevos duplicados.
--
-- No existen tablas con FK hacia movement, por lo que el borrado no
-- afecta en cascada a otras tablas.
--
-- Requiere desplegar antes la version del backend con
-- MovementService::updateMovement idempotente; de lo contrario, un retiro
-- repetido devolvera error 500 a la tablet en vez de ignorarse.

-- Seleccionar la base antes de ejecutar (ajustar el nombre si en prod es otro).
USE lock_app;

-- 1. Verificar cuantos registros seran afectados

-- Envios con retiros duplicados
SELECT id_ref, COUNT(*) AS retiros, GROUP_CONCAT(movement_id ORDER BY movement_id) AS ids
FROM movement
WHERE type_movement_id = 2
GROUP BY id_ref
HAVING COUNT(*) > 1;

SELECT COUNT(*) - COUNT(DISTINCT id_ref) AS retiros_a_eliminar
FROM movement
WHERE type_movement_id = 2;

-- Debe devolver 0 filas. Si devuelve alguna, hay envios con mas de una entrega
-- (tipo 1) y el paso 3 fallara: revisarlos manualmente antes de continuar.
SELECT id_ref, COUNT(*) AS entregas, GROUP_CONCAT(movement_id ORDER BY movement_id) AS ids
FROM movement
WHERE type_movement_id = 1
GROUP BY id_ref
HAVING COUNT(*) > 1;

-- Debe devolver 0: el indice cubre los primeros 36 caracteres (largo de un UUID).
SELECT COUNT(*) AS id_ref_mas_largos_que_uuid
FROM movement
WHERE CHAR_LENGTH(id_ref) > 36;

-- 2. Eliminar duplicados (conserva el retiro con menor movement_id de cada envio)
-- Se usa una tabla temporal porque MySQL no permite borrar de movement
-- consultando movement en la misma sentencia.
START TRANSACTION;

DROP TEMPORARY TABLE IF EXISTS tmp_retiro_conservar;

CREATE TEMPORARY TABLE tmp_retiro_conservar AS
SELECT MIN(movement_id) AS movement_id
FROM movement
WHERE type_movement_id = 2
GROUP BY id_ref;

DELETE FROM movement
WHERE type_movement_id = 2
  AND movement_id NOT IN (SELECT movement_id FROM tmp_retiro_conservar);

DROP TEMPORARY TABLE tmp_retiro_conservar;

COMMIT;

-- 3. Indice unico para impedir nuevos duplicados
-- (DDL: hace commit implicito, por eso va fuera de la transaccion.
-- Si el indice ya existe, esta sentencia falla con "Duplicate key name"; se puede ignorar.)
ALTER TABLE movement
ADD UNIQUE KEY uq_movement_ref_type (id_ref(36), type_movement_id);

-- 4. Comprobar resultado (debe devolver 0 filas)
SELECT id_ref, type_movement_id, COUNT(*) AS filas
FROM movement
GROUP BY id_ref, type_movement_id
HAVING COUNT(*) > 1;
