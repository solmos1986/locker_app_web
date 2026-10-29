-- Migra las fechas DATETIME de la zona del servidor de produccion (CET/CEST) a UTC.
--
-- Desde esta version Laravel abre la conexion con time_zone = '+00:00'
-- (config/database.php), asi que CURRENT_TIMESTAMP guarda en UTC. Este script
-- convierte los valores ya guardados en hora CET/CEST.
--
-- * Solo toca columnas DATETIME. Las TIMESTAMP (tablas de Laravel) MySQL ya las
--   guarda en UTC internamente.
-- * No usa CONVERT_TZ con nombres de zona (requiere las tablas de zona horaria);
--   aplica la regla de horario de verano europea: CEST (UTC+2) desde el ultimo
--   domingo de marzo 02:00 hasta el ultimo domingo de octubre 03:00; CET (UTC+1)
--   el resto del anio.
-- * Se puede ejecutar UNA sola vez: deja la marca en la tabla migracion_utc y
--   aborta si ya existe.
--
-- Ejecutar justo ANTES de desplegar el backend con el cambio de zona horaria.
-- Los registros creados entre el script y el despliegue quedaran 1 hora adelantados.
-- Solo para produccion (servidor en CET). NO ejecutar en una base cuyo servidor
-- este en otra zona (por ejemplo la local, en America/La_Paz).

USE lock_app;

DELIMITER $$

DROP FUNCTION IF EXISTS cet_a_utc $$
CREATE FUNCTION cet_a_utc(d DATETIME) RETURNS DATETIME
    DETERMINISTIC NO SQL
BEGIN
    DECLARE fin_marzo DATE;
    DECLARE fin_octubre DATE;
    IF d IS NULL THEN
        RETURN NULL;
    END IF;
    SET fin_marzo   = LAST_DAY(MAKEDATE(YEAR(d), 1) + INTERVAL 2 MONTH);
    SET fin_marzo   = fin_marzo - INTERVAL (DAYOFWEEK(fin_marzo) - 1) DAY;
    SET fin_octubre = LAST_DAY(MAKEDATE(YEAR(d), 1) + INTERVAL 9 MONTH);
    SET fin_octubre = fin_octubre - INTERVAL (DAYOFWEEK(fin_octubre) - 1) DAY;
    RETURN d - INTERVAL IF(
        d >= TIMESTAMP(fin_marzo, '02:00:00') AND d < TIMESTAMP(fin_octubre, '03:00:00'),
        2, 1
    ) HOUR;
END $$

DROP PROCEDURE IF EXISTS migrar_fechas_utc $$
CREATE PROCEDURE migrar_fechas_utc()
BEGIN
    DECLARE fin INT DEFAULT 0;
    DECLARE tabla VARCHAR(64);
    DECLARE asignaciones TEXT;
    DECLARE tablas CURSOR FOR
        SELECT c.table_name,
               GROUP_CONCAT(CONCAT('`', c.column_name, '` = cet_a_utc(`', c.column_name, '`)') SEPARATOR ', ')
        FROM information_schema.columns c
        JOIN information_schema.tables t
          ON t.table_schema = c.table_schema AND t.table_name = c.table_name
        WHERE c.table_schema = DATABASE()
          AND c.data_type = 'datetime'
          AND t.table_type = 'BASE TABLE'
          AND c.table_name <> 'migracion_utc'
        GROUP BY c.table_name;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin = 1;

    IF EXISTS (SELECT 1 FROM information_schema.tables
               WHERE table_schema = DATABASE() AND table_name = 'migracion_utc') THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La migracion a UTC ya fue ejecutada (existe la tabla migracion_utc)';
    END IF;

    OPEN tablas;
    leer: LOOP
        FETCH tablas INTO tabla, asignaciones;
        IF fin = 1 THEN
            LEAVE leer;
        END IF;
        -- Todas las columnas en un solo UPDATE: si update_at no se asigna
        -- explicitamente, ON UPDATE CURRENT_TIMESTAMP lo pisaria con la hora actual.
        SET @sentencia = CONCAT('UPDATE `', tabla, '` SET ', asignaciones);
        PREPARE sentencia FROM @sentencia;
        EXECUTE sentencia;
        DEALLOCATE PREPARE sentencia;
    END LOOP;
    CLOSE tablas;

    CREATE TABLE migracion_utc (ejecutado_utc DATETIME NOT NULL);
    INSERT INTO migracion_utc VALUES (UTC_TIMESTAMP());
END $$

DELIMITER ;

-- 1. Verificar la conversion (debe dar 2026-07-01 10:00:00 y 2026-12-01 11:00:00)
SELECT cet_a_utc('2026-07-01 12:00:00') AS verano, cet_a_utc('2026-12-01 12:00:00') AS invierno;

-- 2. Vista previa de los ultimos movimientos
SELECT movement_id, create_at AS create_at_cet, cet_a_utc(create_at) AS create_at_utc
FROM movement ORDER BY movement_id DESC LIMIT 10;

-- 3. Migrar
CALL migrar_fechas_utc();

-- 4. Limpiar
DROP PROCEDURE migrar_fechas_utc;
DROP FUNCTION cet_a_utc;
