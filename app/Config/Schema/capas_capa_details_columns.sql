-- Adds the remaining columns needed to mirror the CAPA record format
-- specified in the CAPA.doc business requirement:
--
--   Description of non conformity | Root cause | Corrective/preventive
--   action | Status | Target date | Responsible person
--
-- `description` and `status` already existed (auto-populated by
-- ReviewDeadlineAlertShell and kept in sync by
-- ApplicationsController::manager_add_capa_followup()). This migration
-- adds `root_cause`, `corrective_action` and `target_date` as plain
-- columns on `capas` - free text/date, same as the rest of this table,
-- not new FK relations - since the CAPA log described in the doc is a
-- simple record, not something that needs to resolve against another
-- table.
--
-- "Responsible person" is deliberately NOT a new column here - it's just
-- the existing `reviewer_user_id`/Reviewer association, relabeled in the
-- CAPA views (see Capa.php, app/View/Elements/capas/modal.ctp,
-- app/View/Capas/manager_index.ctp, csv_export.ctp). The reviewer whose
-- missed deadline opened the case IS who needs to act to close it, so a
-- second, separately-maintained "who's responsible" field would just be
-- duplicate data that could drift out of sync with it.
--
-- `root_cause` and `corrective_action` start out NULL on the auto-opened
-- 'Initial' row (nobody has investigated yet at that point) and get
-- filled in via a follow-up. `target_date` (the target date for
-- completing the corrective/preventive action) is distinct from the
-- existing `deadline_date` (the original Review-stage SLA deadline that
-- was missed) and starts NULL until a manager sets one.
--
-- Safe to re-run - idempotent-guarded via information_schema checks, same
-- pattern as the other capas_*.sql migrations in this folder.

SET @col_exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'capas' AND COLUMN_NAME = 'target_date'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `capas` ADD COLUMN `target_date` date DEFAULT NULL AFTER `days_overdue`',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @col_exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'capas' AND COLUMN_NAME = 'root_cause'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `capas` ADD COLUMN `root_cause` text AFTER `description`',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @col_exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'capas' AND COLUMN_NAME = 'corrective_action'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `capas` ADD COLUMN `corrective_action` text AFTER `root_cause`',
  'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
