<?php
App::uses('Shell', 'Console');
App::uses('String', 'Utility');
App::uses('Sanitize', 'Utility');
App::uses('Router', 'Routing');
App::uses('CakeEmail', 'Network/Email');

/**
 * ReviewDeadlineAlertShell
 *
 * Escalating deadline alerts for the Review stage (the reviewer's own
 * assessment window - stage 5 in the application timeline widget, see
 * ApplicationsController::stages(). Not to be confused with the
 * ReviewSubmission/"Sponsor Feedback" stage that follows it).
 *
 * A reviewer's SLA clock starts the moment they accept their review request
 * (Review.type = 'request', Review.accepted = 'accepted' - acceptance is
 * recorded in place on that same row, per WeeklyReviewerReminderTaskShell's
 * existing query). This shell reads that row's `modified` timestamp as the
 * "accepted on" date.
 *
 * CAVEAT: `modified` is a general-purpose timestamp, not a dedicated
 * "accepted_date" column - if anything else ever re-saves that Review row
 * after acceptance, the SLA clock would appear to reset. This is a
 * deliberate "use what already exists" choice to avoid a schema change;
 * recommend adding a real `accepted_date` column to `reviews` as a
 * follow-up once this is validated in practice.
 *
 * Escalation, from the accepted date, over a self::SLA_DAYS window (28
 * days, and the exact Day numbers below - not a percentage of the window -
 * mirror the CAPA.doc business requirement's reminder schedule verbatim):
 *
 *   - Day 1  (the day of acceptance itself)     -> one-time notice: "you
 *                                                    have 28 days to
 *                                                    finalize your review"
 *   - Day 14 (14 days elapsed)                  -> one-time reminder: "14
 *                                                    days remaining"
 *   - Day 21 (21 days elapsed)                  -> one-time reminder:
 *                                                    "only 7 days remaining"
 *   - Day 28 (the deadline itself)              -> reminder ("due today"),
 *                                                    then repeated once per day
 *   - past Day 28 (deadline lapsed)              -> a CAPA record is opened
 *                                                    (once, on the first day
 *                                                    it's overdue), and the
 *                                                    daily reminder keeps
 *                                                    firing until the
 *                                                    reviewer actually submits.
 *
 * Each tier is checked with ">=" rather than "==" (see _processReview()),
 * so a day cron misses (downtime, a double-fire, etc.) is caught up on the
 * next run instead of silently skipped - the one-time AuditTrail guard
 * still stops it from being sent twice.
 *
 * A reviewer is considered done once a Review row with
 * type='reviewer_comment' and status='submitted' exists for them on that
 * application (same check WeeklyReviewerReminderTaskShell already uses) -
 * at that point they drop out of every tier above, permanently.
 *
 * Idempotency: every reminder sent is logged to AuditTrail (foreign_key =
 * Review.id, model = 'Review Deadline Reminder <tier>'). One-time tiers
 * (Day 1/14/21) check AuditTrail for *any* prior send; the daily tier
 * (Day 28/overdue) checks for a send *today* so it naturally repeats once a
 * day. CAPA creation is guarded both by an application-level existence
 * check and by a UNIQUE KEY on (review_id, source_stage) in `capas`, so
 * re-running this shell (e.g. if cron double-fires) is always safe.
 *
 * Intended to run daily via cron/systemd - see setup_review_deadline_alert.sh
 * and review_deadline_alert.sh, which mirror the existing
 * setup_weekly_reminder.sh / reviewer_weekly_reminder.sh pattern already
 * used for the weekly reviewer nudge.
 */
class ReviewDeadlineAlertShell extends AppShell
{
    public $uses = array('Review', 'Application', 'ApplicationStage', 'User', 'Message', 'AuditTrail', 'Notification', 'Capa');

    /**
     * SLA window for the Review stage, in calendar days - matches the
     * CAPA.doc reminder schedule (Day 1/14/21/28), which is written
     * against a 28-day window ("you have 28 days...", 14 remaining at Day
     * 14, 7 remaining at Day 21, due at Day 28).
     */
    const SLA_DAYS = 28;

    protected $_messages = array();

    public function main()
    {
        $this->out('Running Review stage deadline alert engine (SLA = ' . self::SLA_DAYS . ' days)...');

        $this->_messages = $this->Message->find('list', array(
            'conditions' => array('Message.name' => array(
                'reviewer_deadline_day1', 'reviewer_deadline_day1_subject',
                'reviewer_deadline_day14', 'reviewer_deadline_day14_subject',
                'reviewer_deadline_day21', 'reviewer_deadline_day21_subject',
                'reviewer_deadline_day28', 'reviewer_deadline_day28_subject',
                'reviewer_deadline_overdue', 'reviewer_deadline_overdue_subject',
            )),
            'fields' => array('Message.name', 'Message.content'),
        ));

        // Message table stores subject alongside content in this app (see
        // AnnualLettersController), but the seed in capas.sql also mirrors
        // the '<name>_subject' convention WeeklyReviewerReminderTaskShell
        // uses, so either layout works with the lookups below.
        $subjects = $this->Message->find('list', array(
            'conditions' => array('Message.name' => array(
                'reviewer_deadline_day1', 'reviewer_deadline_day14', 'reviewer_deadline_day21',
                'reviewer_deadline_day28', 'reviewer_deadline_overdue',
            )),
            'fields' => array('Message.name', 'Message.subject'),
        ));
        foreach ($subjects as $name => $subject) {
            if (!empty($subject) && empty($this->_messages[$name . '_subject'])) {
                $this->_messages[$name . '_subject'] = $subject;
            }
        }

        $reviews = $this->Review->find('all', array(
            'contain' => array('User', 'Application'),
            'conditions' => array(
                'Review.type' => 'request',
                'Review.accepted' => 'accepted',
            ),
        ));

        $this->out('Found ' . count($reviews) . ' accepted review assignment(s) to check.');

        foreach ($reviews as $row) {
            $this->_processReview($row);
        }

        $this->out('--- Review deadline alert engine completed ---');
    }

    protected function _processReview($row)
    {
        $review = $row['Review'];
        if (empty($row['Application']) || empty($row['User'])) {
            return;
        }
        $reviewerId = $review['user_id'];
        $applicationId = $review['application_id'];

        // Already submitted - nothing left to chase.
        $submitted = $this->Review->find('first', array(
            'conditions' => array(
                'Review.user_id' => $reviewerId,
                'Review.application_id' => $applicationId,
                'Review.type' => 'reviewer_comment',
                'Review.status' => 'submitted',
            ),
        ));
        if ($submitted) {
            return;
        }

        $acceptedOn = !empty($review['modified']) ? $review['modified'] : $review['created'];
        if (empty($acceptedOn)) {
            return;
        }

        $accepted = new DateTime(date('Y-m-d', strtotime($acceptedOn)));
        $today = new DateTime(date('Y-m-d'));
        $elapsedDays = (int)$today->diff($accepted)->format('%a');

        $deadline = clone $accepted;
        $deadline->modify('+' . self::SLA_DAYS . ' days');
        $daysOverdue = ($today > $deadline) ? (int)$today->diff($deadline)->format('%a') : 0;
        $daysRemaining = max(0, self::SLA_DAYS - $elapsedDays);

        $variables = array(
            'protocol_link' => Router::url(array(
                'controller' => 'applications', 'action' => 'view', $applicationId, 'reviewer' => true,
            ), true),
            'protocol_no' => $row['Application']['protocol_no'],
            'name' => $row['User']['name'],
            'email' => $row['User']['email'],
            'study_title' => $row['Application']['short_title'],
            'days_elapsed' => $elapsedDays,
            'sla_days' => self::SLA_DAYS,
            'deadline_date' => $deadline->format('jS F Y'),
            'days_overdue' => $daysOverdue,
            'days_remaining' => $daysRemaining,
        );

        if ($today > $deadline) {
            // Past Day 28: keep reminding daily, and make sure a CAPA is open.
            $this->_sendReminder('overdue', 'Review Deadline Overdue Reminder', true, $review, $row, $variables,
                'reviewer_deadline_overdue', 'reviewer_deadline_overdue_subject');
            $this->_ensureCapa($review, $row, $deadline, $daysOverdue);
        } elseif ($today == $deadline) {
            // Day 28: the deadline has arrived but not yet lapsed - "due today".
            $this->_sendReminder('Day 28', 'Review Deadline Reminder Day28', true, $review, $row, $variables,
                'reviewer_deadline_day28', 'reviewer_deadline_day28_subject');
        } elseif ($elapsedDays >= 21) {
            $this->_sendReminder('Day 21', 'Review Deadline Reminder Day21', false, $review, $row, $variables,
                'reviewer_deadline_day21', 'reviewer_deadline_day21_subject');
        } elseif ($elapsedDays >= 14) {
            $this->_sendReminder('Day 14', 'Review Deadline Reminder Day14', false, $review, $row, $variables,
                'reviewer_deadline_day14', 'reviewer_deadline_day14_subject');
        } elseif ($elapsedDays >= 0) {
            $this->_sendReminder('Day 1', 'Review Deadline Reminder Day1', false, $review, $row, $variables,
                'reviewer_deadline_day1', 'reviewer_deadline_day1_subject');
        } else {
            $this->out("- Review #{$review['id']} ({$row['Application']['protocol_no']}): {$elapsedDays}/" . self::SLA_DAYS . " days elapsed, nothing to send.");
        }
    }

    protected function _alreadySent($reviewId, $auditModel, $repeatDaily)
    {
        $conditions = array(
            'AuditTrail.foreign_key' => $reviewId,
            'AuditTrail.model' => $auditModel,
        );
        if ($repeatDaily) {
            $conditions['DATE(AuditTrail.created)'] = date('Y-m-d');
        }
        return (bool)$this->AuditTrail->find('first', array('conditions' => $conditions));
    }

    protected function _sendReminder($tierLabel, $auditModel, $repeatDaily, $review, $row, $variables, $bodyKey, $subjectKey)
    {
        $reviewId = $review['id'];

        if ($this->_alreadySent($reviewId, $auditModel, $repeatDaily)) {
            $this->out("- {$tierLabel} reminder already sent for Review #{$reviewId}" . ($repeatDaily ? ' today' : '') . ', skipping.');
            return;
        }

        if (empty($this->_messages[$bodyKey]) || empty($this->_messages[$subjectKey])) {
            $this->err("Message template '{$bodyKey}'/'{$subjectKey}' not found (run the capas.sql seed) - logging only, no email sent.");
        } else {
            $body = String::insert($this->_messages[$bodyKey], $variables);
            $subject = Sanitize::html(String::insert($this->_messages[$subjectKey], $variables), array('remove' => true));

            $email = new CakeEmail();
            $email->config('gmail');
            $email->template('default');
            $email->emailFormat('html');
            $email->to($row['User']['email']);
            $email->subject($subject);
            $email->viewVars(array('message' => $body));
            if (!$email->send()) {
                $this->log('Failed to send ' . $tierLabel . ' Review deadline reminder for Review #' . $reviewId, 'review_deadline_alert_error');
            }

            $this->Notification->create();
            $this->Notification->save(array('Notification' => array(
                'user_id' => $row['User']['id'],
                // IMPORTANT: this must exactly equal a `messages`.`name` row
                // that has a `style` set - app/View/Elements/alerts/notifications.ctp
                // renders every notification via
                // $messages[$notification['Notification']['type']] (a
                // name => style lookup built in UsersController::*_dashboard()),
                // and throws "Undefined index" if the type isn't a real
                // Message name. $bodyKey already IS that Message name, so
                // reuse it directly instead of deriving a new string from
                // $tierLabel (the old code below produced mismatched values
                // like 'review_deadline_overdue' - missing the 'r' in
                // "review[er]" - and 'review_deadline_100_' with a stray
                // trailing underscore for the '%' tiers).
                'type' => $bodyKey,
                'model' => 'Review',
                'foreign_key' => $reviewId,
                'title' => $subject,
                'system_message' => $body,
            )));
        }

        $this->AuditTrail->create();
        $this->AuditTrail->save(array('AuditTrail' => array(
            'foreign_key' => $reviewId,
            'model' => $auditModel,
            'message' => $tierLabel . ' review deadline reminder sent to ' . $row['User']['name']
                . ' for application ' . $row['Application']['protocol_no']
                . ' (' . $variables['days_elapsed'] . ' of ' . self::SLA_DAYS . ' days elapsed).',
            'ip' => $row['Application']['protocol_no'],
        )));

        $this->out("- {$tierLabel} reminder sent to {$row['User']['name']} for {$row['Application']['protocol_no']} (Review #{$reviewId}).");
    }

    protected function _ensureCapa($review, $row, DateTime $deadline, $daysOverdue)
    {
        // A CAPA "case" is a group of rows sharing (review_id,
        // source_stage) - the 'Initial' row plus any manager-added
        // 'FollowUp' rows (see Capa.php). Once ANY row exists for this
        // pair, the case is already open - don't open a second one.
        $existing = $this->Capa->find('first', array(
            'conditions' => array('Capa.review_id' => $review['id'], 'Capa.source_stage' => 'Review'),
        ));
        if ($existing) {
            return;
        }

        $applicationStage = $this->ApplicationStage->find('first', array(
            'conditions' => array(
                'ApplicationStage.application_id' => $row['Application']['id'],
                'ApplicationStage.stage' => 'Review',
            ),
            'order' => array('ApplicationStage.id' => 'ASC'),
        ));

        // Count only 'Initial' rows (i.e. CAPA cases) for the running
        // sequence number - counting all rows would inflate it every time
        // a manager appends a 'FollowUp' elsewhere in the table.
        $caseCount = $this->Capa->find('count', array('conditions' => array('Capa.type' => 'Initial')));
        $refNo = 'CAPA/' . $row['Application']['protocol_no'] . '/' . date('Y') . '/' . ($caseCount + 1);

        $this->Capa->create();
        $saved = $this->Capa->save(array('Capa' => array(
            'type' => 'Initial',
            // Explicit for readability/defensiveness - an Initial row is
            // always a root, it has no parent (see Capa.php).
            'capa_id' => null,
            'reference_no' => $refNo,
            'application_id' => $row['Application']['id'],
            'application_stage_id' => !empty($applicationStage['ApplicationStage']['id']) ? $applicationStage['ApplicationStage']['id'] : null,
            'review_id' => $review['id'],
            'reviewer_user_id' => $review['user_id'],
            'source_stage' => 'Review',
            'deadline_date' => $deadline->format('Y-m-d'),
            'days_overdue' => $daysOverdue,
            'status' => 'Open',
            // Description of non conformity (per the CAPA.doc table
            // format) - known and auto-filled the moment the deadline
            // lapses. Root cause and the corrective/preventive action
            // can't be known yet - they're filled in via a follow-up once
            // someone investigates (see ApplicationsController::
            // manager_add_capa_followup()). `target_date` (the target for
            // completing that action) likewise starts blank for a manager
            // to set. There's no `responsible_person` field to set here -
            // that's just `reviewer_user_id`/the Reviewer association,
            // relabeled "Responsible person" in the CAPA views.
            'description' => 'Reviewer ' . $row['User']['name'] . ' did not submit their review for application '
                . $row['Application']['protocol_no'] . ' within the ' . self::SLA_DAYS . '-day SLA (deadline was '
                . $deadline->format('jS F Y') . '; now ' . $daysOverdue . ' day(s) overdue).',
            'root_cause' => null,
            'corrective_action' => null,
            'target_date' => null,
        )));

        if ($saved) {
            $this->out("!! CAPA {$refNo} opened for {$row['User']['name']} / {$row['Application']['protocol_no']}.");
            $this->AuditTrail->create();
            $this->AuditTrail->save(array('AuditTrail' => array(
                'foreign_key' => $review['id'],
                'model' => 'CAPA Created',
                'message' => 'CAPA ' . $refNo . ' auto-opened: reviewer ' . $row['User']['name']
                    . ' missed the Review stage deadline for application ' . $row['Application']['protocol_no'] . '.',
                'ip' => $row['Application']['protocol_no'],
            )));
        } else {
            $this->err('Failed to save CAPA for Review #' . $review['id'] . ': ' . print_r($this->Capa->validationErrors, true));
            $this->log('Failed to save CAPA for Review #' . $review['id'], 'review_deadline_alert_error');
        }
    }
}
