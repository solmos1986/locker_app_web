-- Eliminacion de todos los movimientos y liberacion de todos los casilleros.
--
-- El estado ocupado/libre de una puerta se calcula a partir de su ultimo
-- movimiento (LockerService::getDoors). Sin movimientos, todas las puertas
-- quedan libres. Ademas se reactivan todas las puertas (door.state = 1).
--
-- No existen tablas con FK hacia movement, por lo que el borrado no
-- afecta en cascada a otras tablas.

-- 1. Verificar cuantos registros seran afectados
SELECT COUNT(*) AS movimientos_a_eliminar
FROM movement;

SELECT COUNT(*) AS puertas_a_liberar
FROM door
WHERE state <> 1;

-- 2. Eliminar y liberar
START TRANSACTION;

DELETE FROM movement;

UPDATE door
SET state = 1
WHERE state <> 1;

COMMIT;

-- 3. (Opcional) Reiniciar el contador de movement_id
ALTER TABLE movement AUTO_INCREMENT = 1;
