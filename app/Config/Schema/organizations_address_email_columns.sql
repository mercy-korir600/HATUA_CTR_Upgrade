-- Migration: Add physical_address, postal_address, and email_address to organizations table
-- Safe to re-run - idempotent-guarded via information_schema checks.

SET @col_physical_exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'organizations' AND COLUMN_NAME = 'physical_address'
);
SET @sql_physical := IF(@col_physical_exists = 0,
  'ALTER TABLE `organizations` ADD COLUMN `physical_address` varchar(255) DEFAULT NULL AFTER `address`',
  'SELECT 1'
);
PREPARE stmt_physical FROM @sql_physical;
EXECUTE stmt_physical;
DEALLOCATE PREPARE stmt_physical;

SET @col_postal_exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'organizations' AND COLUMN_NAME = 'postal_address'
);
SET @sql_postal := IF(@col_postal_exists = 0,
  'ALTER TABLE `organizations` ADD COLUMN `postal_address` varchar(255) DEFAULT NULL AFTER `physical_address`',
  'SELECT 1'
);
PREPARE stmt_postal FROM @sql_postal;
EXECUTE stmt_postal;
DEALLOCATE PREPARE stmt_postal;

SET @col_email_exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'organizations' AND COLUMN_NAME = 'email_address'
);
SET @sql_email := IF(@col_email_exists = 0,
  'ALTER TABLE `organizations` ADD COLUMN `email_address` varchar(255) DEFAULT NULL AFTER `postal_address`',
  'SELECT 1'
);
PREPARE stmt_email FROM @sql_email;
EXECUTE stmt_email;
DEALLOCATE PREPARE stmt_email;

-- Back-fill physical_address from legacy address if physical_address is empty
UPDATE `organizations` 
SET `physical_address` = `address` 
WHERE (`physical_address` IS NULL OR `physical_address` = '') 
  AND (`address` IS NOT NULL AND `address` != '');
