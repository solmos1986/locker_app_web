-- Corrige la respuesta "cerrado" 80010700b6 -> 8001070086 en response_comand.
--
-- getComandClosed() (app/helpers.php) tenía 80010700b6: el byte de control
-- estaba mal. El último byte es el XOR de los anteriores: 80^01^07^00 = 86.
-- Los controladores ya creados guardaron el valor erróneo en response_comand;
-- el helper corregido solo afecta a los controladores que se creen a partir de ahora.
--
-- Idempotente: si no queda ningún 80010700b6, no cambia nada.
-- Después, las tablets deben resincronizar (GET /api/database): lo hacen al
-- reconectar el socket o al recibir update_data.

UPDATE response_comand
SET comand = '8001070086'
WHERE comand = '80010700b6'
  AND name = 'cerrado';
