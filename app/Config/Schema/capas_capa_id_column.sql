-- Adds a genuine self-referencing parent pointer to `capas`, so a
-- FollowUp row can record exactly which row it's replying to (the
-- Initial row, OR another FollowUp row) instead of only being
-- grouped loosely by (review_id, source_stage).
--
-- This supersedes the old "flat group" model for THREADING purposes -
-- see app/Model/Capa.php's class docblock and buildThread() - while
-- (review_id, source_stage) is still used to fetch every row that
-- could belong to a case, and to locate the case's Initial row for
-- status-sync (see ApplicationsController::manager_add_capa_followup()).
--
-- Safe to re-run - idempotent-guarded via information_schema checks,
-- same pattern as capas_followup_columns.sql.

SET @col_exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'capas' AND COLUMN_NAME = 'capa_id'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `capas` ADD COLUMN `capa_id` int(11) DEFAULT NULL AFTER `id`',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @idx_exists := (
  SELECT COUNT(*) FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'capas' AND INDEX_NAME = 'idx_capas_capa_id'
);
SET @sql := IF(@idx_exists = 0,
  'ALTER TABLE `capas` ADD INDEX `idx_capas_capa_id` (`capa_id`)',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Back-fill existing FollowUp rows so they point at their case's
-- Initial row - the best approximation we can make retroactively,
-- since the old model had no per-row parent (every FollowUp was
-- implicitly "a reply to the case" as a whole, not to one specific
-- row). New FollowUps created after this migration record their real
-- immediate parent (see ApplicationsController::manager_add_capa_followup()).
UPDATE `capas` f
JOIN `capas` i
  ON i.review_id = f.review_id
  AND i.source_stage = f.source_stage
  AND i.type = 'Initial'
SET f.capa_id = i.id
WHERE f.type = 'FollowUp' AND f.capa_id IS NULL;
