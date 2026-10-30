-- Aperturas manuales desde el panel.
--
-- Toda apertura desde el panel queda auditada en door_event (usuario, tipo,
-- motivo). Tipos:
--   mantenimiento (incluye limpieza): solo abre; solo si el casillero no tiene
--     una entrega pendiente.
--   intervencion: solo con entrega pendiente; la cierra y libera el casillero.
--     Ademas se registra el movimiento "intervenido" en el mismo id_ref (como un retiro).
--
-- * type_movement 4 = intervenido.
-- * movement.users_id: usuario del panel (tabla users) que intervino; NULL en
--   entregas y retiros de la tablet.
-- * movement.observacion: motivo de la intervencion.
--
-- La tablet no usa estas columnas ni door_event: no viajan en /api/database.

USE lock_app;

INSERT INTO type_movement (type_movement_id, name)
SELECT 4, 'intervenido'
WHERE NOT EXISTS (SELECT 1 FROM type_movement WHERE type_movement_id = 4);

ALTER TABLE movement
    ADD COLUMN users_id BIGINT UNSIGNED NULL AFTER type_movement_id,
    ADD COLUMN observacion VARCHAR(255) NULL AFTER users_id;

CREATE TABLE door_event (
    door_event_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    door_id INT NOT NULL,
    users_id BIGINT UNSIGNED NULL,
    tipo VARCHAR(20) NOT NULL,
    observacion VARCHAR(255) NOT NULL,
    movement_id BIGINT NULL,
    create_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    KEY idx_door_event_door (door_id),
    FOREIGN KEY (door_id) REFERENCES door (door_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Verificacion
SELECT * FROM type_movement;
SHOW COLUMNS FROM movement;
SHOW COLUMNS FROM door_event;
