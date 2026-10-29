-- Eliminacion de departamentos que no fueron cargados por API (is_api = 'no').
--
-- ATENCION: las FK hacia department tienen ON DELETE CASCADE, por lo que tambien
-- se eliminaran los registros relacionados en las tablas:
--   * user      (user_ibfk_1     -> user.department_id)
--   * movement  (movement_ibfk_2 -> movement.department_id)

-- 1. Verificar cuantos registros seran eliminados
SELECT COUNT(*) AS departamentos_a_eliminar
FROM department
WHERE is_api = 'no';

-- 2. Eliminar
DELETE FROM department
WHERE is_api = 'no';
