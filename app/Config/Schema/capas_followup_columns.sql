-- Adds CAPA follow-up support directly on the existing `capas` table -
-- no new table. Superseded/replaces the earlier capa_followups.sql
-- approach (delete that file / do not run it if you already fetched it -
-- the capa_followups table it defined was never created on a live
-- database, so there's nothing to migrate away from).
--
-- A CAPA "case" is now a small group of rows sharing the same
-- (review_id, source_stage):
--
--   `type` = 'Initial'  - the auto-opened record (one per reviewer
--                          assignment - still exactly one of these per
--                          (review_id, source_stage), just enforced in
--                          the application layer now instead of a DB
--                          UNIQUE KEY, since FollowUp rows deliberately
--                          share that same (review_id, source_stage)).
--   `type` = 'FollowUp' - a later update a manager appends: a note and/or
--                         a status change. Any number of these per case.
--
-- The case's current status is simply the `status` on the most recent row
-- (Initial or FollowUp) for that (review_id, source_stage) pair - so no
-- separate "head" record needs to be kept in sync when a follow-up
-- changes the status.
--
-- Safe to re-run - every statement is idempotent-guarded via
-- information_schema checks.

SET @col_exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'capas' AND COLUMN_NAME = 'type'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `capas` ADD COLUMN `type` varchar(20) NOT NULL DEFAULT ''Initial'' AFTER `id`',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @col_exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'capas' AND COLUMN_NAME = 'created_by_user_id'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `capas` ADD COLUMN `created_by_user_id` int(11) DEFAULT NULL AFTER `reviewer_user_id`',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Existing rows (created before this migration) are all auto-opened
-- records - back-fill them as 'Initial' explicitly (they'd already read
-- that way via the column default, but this makes it durable/explicit).
UPDATE `capas` SET `type` = 'Initial' WHERE `type` = '' OR `type` IS NULL;

-- The old UNIQUE KEY blocked more than one row per (review_id,
-- source_stage) - which is exactly what FollowUp rows need to violate on
-- purpose. Drop it and replace with a plain (non-unique) index for query
-- performance; "only one Initial per assignment" is now enforced in
-- ReviewDeadlineAlertShell::_ensureCapa()'s existence check instead.
SET @idx_exists := (
  SELECT COUNT(*) FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'capas' AND INDEX_NAME = 'uniq_review_source_stage'
);
SET @sql := IF(@idx_exists > 0,
  'ALTER TABLE `capas` DROP INDEX `uniq_review_source_stage`',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @idx_exists := (
  SELECT COUNT(*) FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'capas' AND INDEX_NAME = 'idx_capas_review_source_stage'
);
SET @sql := IF(@idx_exists = 0,
  'ALTER TABLE `capas` ADD INDEX `idx_capas_review_source_stage` (`review_id`, `source_stage`)',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
