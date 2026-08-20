-- Fixes: Notice (8): Undefined index: review_deadline_overdue
--        [APP/View/Elements/alerts/notifications.ctp, line 5]
--
-- Root cause (two bugs, both in the original capas.sql / ReviewDeadlineAlertShell.php):
--
-- 1. app/View/Elements/alerts/notifications.ctp renders every notification's
--    alert box via $messages[$notification['Notification']['type']], where
--    $messages is a Message.name => Message.style lookup (see
--    UsersController::*_dashboard(), which does
--    Message->find('list', array('fields' => array('name', 'style')))).
--    The four reviewer_deadline_* rows capas.sql inserted never set `style`,
--    so the lookup has no entry for them - hence "Undefined index".
--
-- 2. ReviewDeadlineAlertShell was saving Notification.type as a value
--    derived from the tier label instead of reusing the Message name it had
--    just looked the content up by, producing mismatched strings:
--    'review_deadline_overdue' (missing the "r" - should be
--    "reviewer_deadline_overdue") and 'review_deadline_100_' / '_70_' /
--    '_50_' (stray trailing underscore from the '%' sign). This is fixed in
--    the shell going forward; the UPDATEs below repair rows already saved
--    with the broken values so they stop erroring immediately.
--
-- Safe to re-run.

UPDATE `messages` SET `style` = 'info'    WHERE `name` = 'reviewer_deadline_50';
UPDATE `messages` SET `style` = 'warning' WHERE `name` = 'reviewer_deadline_70';
UPDATE `messages` SET `style` = 'warning' WHERE `name` = 'reviewer_deadline_100';
UPDATE `messages` SET `style` = 'error'   WHERE `name` = 'reviewer_deadline_overdue';

UPDATE `notifications` SET `type` = 'reviewer_deadline_overdue' WHERE `type` = 'review_deadline_overdue';
UPDATE `notifications` SET `type` = 'reviewer_deadline_100'     WHERE `type` = 'review_deadline_100_';
UPDATE `notifications` SET `type` = 'reviewer_deadline_70'      WHERE `type` = 'review_deadline_70_';
UPDATE `notifications` SET `type` = 'reviewer_deadline_50'      WHERE `type` = 'review_deadline_50_';
