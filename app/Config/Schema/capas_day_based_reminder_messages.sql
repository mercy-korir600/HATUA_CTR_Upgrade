-- Replaces the 50%/70%/100% percentage-based reminder tiers with the
-- exact escalation schedule specified in the CAPA.doc business
-- requirement: reminders on Day 1, Day 14, Day 21 and Day 28 of the
-- (now 28-day, was 30-day) Review-stage SLA window, wording taken
-- directly from that document. See ReviewDeadlineAlertShell.php, which
-- looks these up by `name` and no longer references the old
-- reviewer_deadline_50/70/100 rows.
--
-- The old reviewer_deadline_50/70/100(_subject) rows are left in place
-- rather than deleted - they're simply no longer looked up by the shell,
-- and deleting them isn't needed for correctness. `reviewer_deadline_overdue`
-- is reused as-is (still the right tier name for "past the deadline,
-- repeating daily"), its wording just updated to match the doc's
-- "non-conformity" framing.
--
-- Safe to re-run - each INSERT is guarded by a NOT EXISTS check on
-- `name` (the `messages` table has no UNIQUE key to rely on instead),
-- and the UPDATE is naturally idempotent.

INSERT INTO `messages` (`name`, `subject`, `content`, `style`, `created`, `modified`)
SELECT * FROM (SELECT
    'reviewer_deadline_day1' AS `name`,
    'Clinical trial application :protocol_no has been allocated for your review' AS `subject`,
    '<p>Dear :name,</p><p>Clinical trial application No. <strong>:protocol_no - :study_title</strong> has been allocated for your review. You have :sla_days days to finalize your review using the review templates on the CT portal.</p><p><a href=":protocol_link">Open the application to submit your review</a></p>' AS `content`,
    'info' AS `style`, NOW() AS `created`, NOW() AS `modified`
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM `messages` WHERE `name` = 'reviewer_deadline_day1');

INSERT INTO `messages` (`name`, `subject`, `content`, `style`, `created`, `modified`)
SELECT * FROM (SELECT
    'reviewer_deadline_day1_subject' AS `name`,
    NULL AS `subject`,
    'Clinical trial application :protocol_no has been allocated for your review' AS `content`,
    'info' AS `style`, NOW() AS `created`, NOW() AS `modified`
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM `messages` WHERE `name` = 'reviewer_deadline_day1_subject');

INSERT INTO `messages` (`name`, `subject`, `content`, `style`, `created`, `modified`)
SELECT * FROM (SELECT
    'reviewer_deadline_day14' AS `name`,
    'Reminder: :days_remaining days remaining to review :protocol_no' AS `subject`,
    '<p>Dear :name,</p><p>Clinical trial application No. <strong>:protocol_no - :study_title</strong> allocated to you has :days_remaining days remaining for you to finalize your review. Complete and post your report before the due date (:deadline_date).</p><p><a href=":protocol_link">Open the application to submit your review</a></p>' AS `content`,
    'info' AS `style`, NOW() AS `created`, NOW() AS `modified`
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM `messages` WHERE `name` = 'reviewer_deadline_day14');

INSERT INTO `messages` (`name`, `subject`, `content`, `style`, `created`, `modified`)
SELECT * FROM (SELECT
    'reviewer_deadline_day14_subject' AS `name`,
    NULL AS `subject`,
    'Reminder: :days_remaining days remaining to review :protocol_no' AS `content`,
    'info' AS `style`, NOW() AS `created`, NOW() AS `modified`
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM `messages` WHERE `name` = 'reviewer_deadline_day14_subject');

INSERT INTO `messages` (`name`, `subject`, `content`, `style`, `created`, `modified`)
SELECT * FROM (SELECT
    'reviewer_deadline_day21' AS `name`,
    'Reminder: only :days_remaining days remaining to review :protocol_no' AS `subject`,
    '<p>Dear :name,</p><p>Clinical trial application No. <strong>:protocol_no - :study_title</strong> allocated to you has only :days_remaining days remaining for you to finalize your review. Complete and post your report before the due date (:deadline_date).</p><p><a href=":protocol_link">Open the application to submit your review</a></p>' AS `content`,
    'warning' AS `style`, NOW() AS `created`, NOW() AS `modified`
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM `messages` WHERE `name` = 'reviewer_deadline_day21');

INSERT INTO `messages` (`name`, `subject`, `content`, `style`, `created`, `modified`)
SELECT * FROM (SELECT
    'reviewer_deadline_day21_subject' AS `name`,
    NULL AS `subject`,
    'Reminder: only :days_remaining days remaining to review :protocol_no' AS `content`,
    'warning' AS `style`, NOW() AS `created`, NOW() AS `modified`
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM `messages` WHERE `name` = 'reviewer_deadline_day21_subject');

INSERT INTO `messages` (`name`, `subject`, `content`, `style`, `created`, `modified`)
SELECT * FROM (SELECT
    'reviewer_deadline_day28' AS `name`,
    'Due today: your review of :protocol_no' AS `subject`,
    '<p>Dear :name,</p><p>Clinical trial application No. <strong>:protocol_no - :study_title</strong> allocated to you is due today. Any further delay will be a non-conformity and a CAPA shall be raised. Kindly submit the report now.</p><p><a href=":protocol_link">Open the application to submit your review</a></p>' AS `content`,
    'warning' AS `style`, NOW() AS `created`, NOW() AS `modified`
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM `messages` WHERE `name` = 'reviewer_deadline_day28');

INSERT INTO `messages` (`name`, `subject`, `content`, `style`, `created`, `modified`)
SELECT * FROM (SELECT
    'reviewer_deadline_day28_subject' AS `name`,
    NULL AS `subject`,
    'Due today: your review of :protocol_no' AS `content`,
    'warning' AS `style`, NOW() AS `created`, NOW() AS `modified`
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM `messages` WHERE `name` = 'reviewer_deadline_day28_subject');

-- reviewer_deadline_overdue already exists on a live DB (from the
-- original capas.sql seed) - update its wording in place rather than
-- inserting, and make sure `style` stays set (see
-- capas_notification_style_fix.sql for why that matters).
UPDATE `messages` SET
  `subject` = 'OVERDUE: your review of :protocol_no is :days_overdue day(s) past deadline',
  `content` = '<p>Dear :name,</p><p>Clinical trial application No. <strong>:protocol_no - :study_title</strong> allocated to you was due on :deadline_date and is now :days_overdue day(s) overdue. This is a non-conformity and has been logged as a CAPA (Corrective and Preventive Action) item. Please submit your review as soon as possible.</p><p><a href=":protocol_link">Open the application to submit your review</a></p>',
  `style` = 'error'
WHERE `name` = 'reviewer_deadline_overdue';

UPDATE `messages` SET
  `content` = 'OVERDUE: your review of :protocol_no is :days_overdue day(s) past deadline',
  `style` = 'error'
WHERE `name` = 'reviewer_deadline_overdue_subject';

-- If this is a fresh-ish install where the overdue rows don't exist yet
-- for some reason, insert them too.
INSERT INTO `messages` (`name`, `subject`, `content`, `style`, `created`, `modified`)
SELECT * FROM (SELECT
    'reviewer_deadline_overdue' AS `name`,
    'OVERDUE: your review of :protocol_no is :days_overdue day(s) past deadline' AS `subject`,
    '<p>Dear :name,</p><p>Clinical trial application No. <strong>:protocol_no - :study_title</strong> allocated to you was due on :deadline_date and is now :days_overdue day(s) overdue. This is a non-conformity and has been logged as a CAPA (Corrective and Preventive Action) item. Please submit your review as soon as possible.</p><p><a href=":protocol_link">Open the application to submit your review</a></p>' AS `content`,
    'error' AS `style`, NOW() AS `created`, NOW() AS `modified`
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM `messages` WHERE `name` = 'reviewer_deadline_overdue');

INSERT INTO `messages` (`name`, `subject`, `content`, `style`, `created`, `modified`)
SELECT * FROM (SELECT
    'reviewer_deadline_overdue_subject' AS `name`,
    NULL AS `subject`,
    'OVERDUE: your review of :protocol_no is :days_overdue day(s) past deadline' AS `content`,
    'error' AS `style`, NOW() AS `created`, NOW() AS `modified`
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM `messages` WHERE `name` = 'reviewer_deadline_overdue_subject');
