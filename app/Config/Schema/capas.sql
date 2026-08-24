-- CAPA (Corrective and Preventive Action) tracking table.
--
-- Auto-populated by app/Console/Command/ReviewDeadlineAlertShell.php when a
-- reviewer misses the Review-stage SLA deadline (stage 5/"Review" in the
-- application timeline - the reviewer's own assessment window, not the
-- ReviewSubmission/"Sponsor Feedback" stage that follows it).
--
-- Column set mirrors the CAPA table format specified in the CAPA.doc
-- business requirement: Description of non conformity | Root cause |
-- Corrective/preventive action | Status | Target date | Responsible
-- person - plus the bookkeeping columns (reference no., which
-- application/review/reviewer triggered it, the original deadline it
-- missed, follow-up threading) this app already needed. "Responsible
-- person" has no column of its own - it's the existing `reviewer_user_id`
-- / Reviewer association, relabeled in the CAPA views (see Capa.php).
--
-- No FOREIGN KEY constraints, matching this codebase's existing convention
-- of enforcing relations in the CakePHP ORM layer rather than in MySQL.
--
-- Follow-ups reuse this same table rather than a separate one - see
-- capas_followup_columns.sql for the full rationale (this CREATE already
-- has those columns baked in for fresh installs, along with the later
-- capas_capa_id_column.sql / capas_closed_date_column.sql /
-- capas_capa_details_columns.sql additions). In short: `type`
-- distinguishes the one auto-opened 'Initial' row per reviewer assignment
-- from any number of manager-added 'FollowUp' rows appended later, all
-- sharing the same (review_id, source_stage) - which is why that pair is
-- a plain index here rather than a UNIQUE key.

CREATE TABLE IF NOT EXISTS `capas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `capa_id` int(11) DEFAULT NULL,
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
  `target_date` date DEFAULT NULL,
  `description` text,
  `root_cause` text,
  `corrective_action` text,
  `status` varchar(30) NOT NULL DEFAULT 'Open',
  `closed_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_capas_capa_id` (`capa_id`),
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
-- Escalation follows the CAPA.doc schedule exactly: a Day 1 notice on
-- allocation/acceptance ("you have 28 days"), reminders on Day 14 ("14
-- days remaining") and Day 21 ("only 7 days remaining"), a Day 28 "due
-- today" notice warning that further delay raises a CAPA, then a daily
-- overdue reminder once the deadline has actually lapsed (at which point
-- ReviewDeadlineAlertShell opens the CAPA).
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
('reviewer_deadline_day1',
 'Clinical trial application :protocol_no has been allocated for your review',
 '<p>Dear :name,</p><p>Clinical trial application No. <strong>:protocol_no - :study_title</strong> has been allocated for your review. You have :sla_days days to finalize your review using the review templates on the CT portal.</p><p><a href=":protocol_link">Open the application to submit your review</a></p>',
 'info',
 NOW(), NOW()),
('reviewer_deadline_day14',
 'Reminder: :days_remaining days remaining to review :protocol_no',
 '<p>Dear :name,</p><p>Clinical trial application No. <strong>:protocol_no - :study_title</strong> allocated to you has :days_remaining days remaining for you to finalize your review. Complete and post your report before the due date (:deadline_date).</p><p><a href=":protocol_link">Open the application to submit your review</a></p>',
 'info',
 NOW(), NOW()),
('reviewer_deadline_day21',
 'Reminder: only :days_remaining days remaining to review :protocol_no',
 '<p>Dear :name,</p><p>Clinical trial application No. <strong>:protocol_no - :study_title</strong> allocated to you has only :days_remaining days remaining for you to finalize your review. Complete and post your report before the due date (:deadline_date).</p><p><a href=":protocol_link">Open the application to submit your review</a></p>',
 'warning',
 NOW(), NOW()),
('reviewer_deadline_day28',
 'Due today: your review of :protocol_no',
 '<p>Dear :name,</p><p>Clinical trial application No. <strong>:protocol_no - :study_title</strong> allocated to you is due today. Any further delay will be a non-conformity and a CAPA shall be raised. Kindly submit the report now.</p><p><a href=":protocol_link">Open the application to submit your review</a></p>',
 'warning',
 NOW(), NOW()),
('reviewer_deadline_overdue',
 'OVERDUE: your review of :protocol_no is :days_overdue day(s) past deadline',
 '<p>Dear :name,</p><p>Clinical trial application No. <strong>:protocol_no - :study_title</strong> allocated to you was due on :deadline_date and is now :days_overdue day(s) overdue. This is a non-conformity and has been logged as a CAPA (Corrective and Preventive Action) item. Please submit your review as soon as possible.</p><p><a href=":protocol_link">Open the application to submit your review</a></p>',
 'error',
 NOW(), NOW());
