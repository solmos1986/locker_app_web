-- Recalcula door.state a partir del ultimo movimiento de cada puerta.
--
-- Desde esta version el backend marca la puerta como ocupada (state = 0) al
-- registrar una entrega y libre (state = 1) al registrar el retiro. Este script
-- corrige las puertas cuyo estado quedo desfasado antes del cambio:
--   ultimo movimiento = entrega (1) -> state = 0
--   ultimo movimiento = retiro  (2) -> state = 1
-- Las puertas sin movimientos no se tocan.

USE lock_app;

-- 1. Puertas que cambiarian de estado
SELECT door.door_id, door.name, door.state AS state_actual,
       IF(ultimo.type_movement_id = 1, 0, 1) AS state_nuevo
FROM door
JOIN movement ultimo ON ultimo.movement_id = (
    SELECT MAX(m.movement_id) FROM movement m WHERE m.door_id = door.door_id
)
WHERE door.state <> IF(ultimo.type_movement_id = 1, 0, 1);

-- 2. Actualizar
START TRANSACTION;

UPDATE door
JOIN movement ultimo ON ultimo.movement_id = (
    SELECT MAX(m.movement_id) FROM movement m WHERE m.door_id = door.door_id
)
SET door.state = IF(ultimo.type_movement_id = 1, 0, 1);

COMMIT;
