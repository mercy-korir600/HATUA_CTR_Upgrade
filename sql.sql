ALTER TABLE `applications` ADD `unsubmitted` TINYINT(2) NULL DEFAULT '0' AFTER `date_submitted`, ADD `initial_date_submitted` DATETIME NULL AFTER `unsubmitted`;
ALTER TABLE `investigator_contacts` ADD `investigator_role` VARCHAR(50) NULL DEFAULT 'principal' AFTER `email`;
