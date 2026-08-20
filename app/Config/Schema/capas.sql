-- CAPA (Corrective and Preventive Action) tracking - minimal example table.
--
-- Auto-populated by app/Console/Command/ReviewDeadlineAlertShell.php when a
-- reviewer misses the Review-stage SLA deadline (stage 5/"Review" in the
-- application timeline - the reviewer's own assessment window, not the
-- ReviewSubmission/"Sponsor Feedback" stage that follows it).
--
-- Intentionally kept small for now per request - reference the application
-- and the reviewer, plus enough basic detail to act on. Extend later with
-- root-cause, corrective action, verification, closure fields etc.
--
-- No FOREIGN KEY constraints, matching this codebase's existing convention
-- of enforcing relations in the CakePHP ORM layer rather than in MySQL.
--
-- Follow-ups reuse this same table rather than a separate one - see
-- capas_followup_columns.sql for the full rationale (this CREATE already
-- has those columns baked in for fresh installs). In short: `type`
-- distinguishes the one auto-opened 'Initial' row per reviewer assignment
-- from any number of manager-added 'FollowUp' rows appended later, all
-- sharing the same (review_id, source_stage) - which is why that pair is
-- a plain index here rather than a UNIQUE key.

CREATE TABLE IF NOT EXISTS `capas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL DEFAULT 'Initial',
  `reference_no` varchar(50) DEFAULT NULL,
  `application_id` int(11) NOT NULL,
  `application_stage_id` int(11) DEFAULT NULL,
  `review_id` int(11) DEFAULT NULL,
  `reviewer_user_id` int(11) NOT NULL,
  `created_by_user_id` int(11) DEFAULT NULL,
  `source_stage` varchar(30) NOT NULL DEFAULT 'Review',
  `deadline_date` date DEFAULT NULL,
  `days_overdue` int(11) DEFAULT NULL,
  `description` text,
  `status` varchar(30) NOT NULL DEFAULT 'Open',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_capas_review_source_stage` (`review_id`,`source_stage`),
  KEY `idx_capas_application_id` (`application_id`),
  KEY `idx_capas_reviewer_user_id` (`reviewer_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Message templates for the Review-stage deadline alert engine, following
-- the same `name` / `subject` / `content` convention as the existing
-- 'reviewer_reminder_email' template (see WeeklyReviewerReminderTaskShell).
-- Placeholders use CakePHP's String::insert() default syntax (":key") and
-- are filled in by ReviewDeadlineAlertShell.
--
-- `style` matters beyond email formatting: app/View/Elements/alerts/notifications.ctp
-- (rendered on every dashboard) looks up each notification's alert-box CSS
-- class via $messages[Notification.type], where $messages is exactly this
-- Message.name => Message.style list (see UsersController::*_dashboard()).
-- ReviewDeadlineAlertShell sets Notification.type to the same name used
-- here, so every one of these rows MUST have a `style` - leaving it NULL
-- causes "Undefined index" the moment a matching notification is displayed.
-- Valid values (see app/View/Messages/admin_add.ctp): info, success,
-- warning, error.
--
-- Adjust column names below if your live `messages` table differs -
-- ctr.sql/app/Config/ctr.sql in this repo are both stale relative to the
-- live schema (neither has the `name`/`content`/`style` columns the active
-- code already relies on), so this targets the columns the running app uses.
INSERT INTO `messages` (`name`, `subject`, `content`, `style`, `created`, `modified`) VALUES
('reviewer_deadline_50',
 'Reminder: your review of :protocol_no is due soon',
 '<p>Dear :name,</p><p>This is a reminder that your review of <strong>:protocol_no - :study_title</strong> is now :days_elapsed of :sla_days days in (50% of the review window elapsed). Please submit your comments at your earliest convenience.</p><p><a href=":protocol_link">Open the application to submit your review</a></p>',
 'info',
 NOW(), NOW()),
('reviewer_deadline_70',
 'Second reminder: your review of :protocol_no is due soon',
 '<p>Dear :name,</p><p>Your review of <strong>:protocol_no - :study_title</strong> is now :days_elapsed of :sla_days days in (70% of the review window elapsed). The submission deadline is :deadline_date. Please submit your comments soon.</p><p><a href=":protocol_link">Open the application to submit your review</a></p>',
 'warning',
 NOW(), NOW()),
('reviewer_deadline_100',
 'Deadline today: your review of :protocol_no is due',
 '<p>Dear :name,</p><p>Today, :deadline_date, is the submission deadline for your review of <strong>:protocol_no - :study_title</strong>. Please submit your comments today to avoid this being logged as a missed deadline.</p><p><a href=":protocol_link">Open the application to submit your review</a></p>',
 'warning',
 NOW(), NOW()),
('reviewer_deadline_overdue',
 'OVERDUE: your review of :protocol_no is :days_overdue day(s) past deadline',
 '<p>Dear :name,</p><p>Your review of <strong>:protocol_no - :study_title</strong> was due on :deadline_date and is now :days_overdue day(s) overdue. This has been logged as a CAPA (Corrective and Preventive Action) item. Please submit your comments as soon as possible.</p><p><a href=":protocol_link">Open the application to submit your review</a></p>',
 'error',
 NOW(), NOW());
