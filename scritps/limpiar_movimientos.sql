-- Deja en blanco el historial de movimientos y libera todos los casilleros.
--
-- * Borra todos los movimientos (entregas, retiros e intervenciones).
-- * Borra las aperturas manuales (door_event): sus movement_id quedarian
--   apuntando a movimientos inexistentes.
-- * Libera todas las puertas (door.state = 1): el estado ocupado/libre se deriva
--   de los movimientos; sin movimientos no queda ningun casillero ocupado.
--   OJO: tambien reactiva las puertas deshabilitadas manualmente.
-- * Reinicia los contadores AUTO_INCREMENT.
--
-- Despues de ejecutarlo, sincronizar las tablets (Actualizar base de datos desde
-- el panel o reiniciar la app): mientras tanto conservan sus movimientos locales
-- y podrian aceptar codigos de entregas que ya no existen en el servidor.
--
-- No hay FKs hacia movement ni door_event: el borrado no afecta otras tablas.
-- Se recomienda hacer un backup antes de ejecutarlo en produccion.

USE lock_app;

-- 1. Registros que seran afectados
SELECT
    (SELECT COUNT(*) FROM movement)            AS movimientos_a_eliminar,
    (SELECT COUNT(*) FROM door_event)          AS aperturas_a_eliminar,
    (SELECT COUNT(*) FROM door WHERE state <> 1) AS puertas_a_liberar;

-- 2. Eliminar y liberar
START TRANSACTION;

DELETE FROM door_event;
DELETE FROM movement;

UPDATE door
SET state = 1
WHERE state <> 1;

COMMIT;

-- 3. Reiniciar contadores
ALTER TABLE movement AUTO_INCREMENT = 1;
ALTER TABLE door_event AUTO_INCREMENT = 1;

-- 4. Verificacion (todo debe dar 0)
SELECT
    (SELECT COUNT(*) FROM movement)            AS movimientos,
    (SELECT COUNT(*) FROM door_event)          AS aperturas,
    (SELECT COUNT(*) FROM door WHERE state <> 1) AS puertas_ocupadas;
