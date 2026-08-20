-- Adds a `closed_date` timestamp to `capas`, stamped automatically the
-- moment a row's own `status` is saved as 'Closed' (see
-- ApplicationsController::manager_add_capa_followup()) - not
-- hand-entered, so it always reflects exactly when the closure was
-- recorded, not when someone later remembers to fill in a date field.
--
-- Cleared back to NULL if a case is ever reopened (status moves away from
-- 'Closed' again via a later follow-up), so it only ever reflects the
-- most recent closure, not a stale one from an earlier reopen/close cycle.
--
-- Safe to re-run - idempotent-guarded via information_schema checks, same
-- pattern as the other capas_*.sql migrations in this folder.

SET @col_exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'capas' AND COLUMN_NAME = 'closed_date'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `capas` ADD COLUMN `closed_date` datetime DEFAULT NULL AFTER `status`',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Back-fill: any existing row already sitting at status = 'Closed' gets
-- its `closed_date` set to its own `modified` timestamp (the closest
-- approximation of "when it was closed" available for rows that predate
-- this column).
UPDATE `capas` SET `closed_date` = `modified` WHERE `status` = 'Closed' AND `closed_date` IS NULL;
