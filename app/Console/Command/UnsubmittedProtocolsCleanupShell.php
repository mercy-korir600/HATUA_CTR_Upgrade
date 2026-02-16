<?php
App::uses('AppShell', 'Console/Command');
App::uses('CakeEmail', 'Network/Email');
App::uses('Validation', 'Utility');
App::uses('AppModel', 'Model');

class UnsubmittedProtocolsCleanupShell extends AppShell
{
    const SEND_DELETION_EMAILS = false;

    public $uses = array('Application', 'DeletionSetting', 'AuditTrail');

    public function main()
    {
        $this->cleanup(false);
    }

    public function preview()
    {
        $this->cleanup(true);
    }

    public function cleanup($dryRun = false)
    {
        $this->out('Starting unsubmitted protocols cleanup...');
        $this->out($dryRun ? 'Mode: DRY RUN (no deletes, no audits, no emails)' : 'Mode: LIVE RUN');

        if (!$this->DeletionSetting->ensureTable()) {
            $this->err('Unable to access deletion settings table.');
            return;
        }

        $months = (int)$this->DeletionSetting->getCurrentMonths(AppModel::AUTO_DELETION_PERIOD_DEFAULT_MONTHS);
        if ($months < 1) {
            $months = AppModel::AUTO_DELETION_PERIOD_DEFAULT_MONTHS;
        }

        $cutoffDate = date('Y-m-d H:i:s', strtotime('-' . $months . ' months'));
        $this->out('Deletion period: ' . $months . ' month(s)');
        $this->out('Cutoff date: ' . $cutoffDate);
        $this->out('Excluding reports that were previously submitted and later unsubmitted by admins.');

        $applications = $this->Application->find('all', array(
            'contain' => array(
                'User' => array('fields' => array('User.id', 'User.name', 'User.email'))
            ),
            'fields' => array(
                'Application.id',
                'Application.user_id',
                'Application.protocol_no',
                'Application.created',
                'Application.email_address'
            ),
            'conditions' => array(
                'Application.submitted' => 0,
                'Application.created <=' => $cutoffDate,
                'Application.date_submitted' => null,
                'Application.initial_date_submitted' => null,
                'OR' => array(
                    'Application.unsubmitted' => 0,
                    'Application.unsubmitted IS NULL'
                )
            ),
            'order' => array('Application.created' => 'ASC')
        ));

        if (empty($applications)) {
            $this->out('No unsubmitted protocols matched the cleanup criteria.');
            return;
        }

        $this->out('Matched protocols: ' . count($applications));

        $deletedCount = 0;
        $auditCreatedCount = 0;
        $auditFailureCount = 0;
        $emailSentCount = 0;
        $emailFailureCount = 0;
        $emailMutedCount = 0;

        foreach ($applications as $application) {
            $applicationId = (int)$application['Application']['id'];
            $protocolNo = !empty($application['Application']['protocol_no']) ?
                $application['Application']['protocol_no'] :
                ('ID ' . $applicationId);

            if ($dryRun) {
                $this->out('Would soft delete protocol: ' . $protocolNo);
                continue;
            }

            if (!$this->_softDeleteApplication($applicationId)) {
                $this->log('Failed to soft delete unsubmitted protocol: ' . $protocolNo, 'unsubmitted_cleanup_error');
                $this->err('Failed to soft delete protocol ' . $protocolNo);
                continue;
            }

            $deletedCount++;
            $this->out('Soft deleted protocol: ' . $protocolNo);

            if ($this->_createDeletionAuditTrail($application, $months)) {
                $auditCreatedCount++;
            } else {
                $auditFailureCount++;
                $this->err('Failed creating audit trail for protocol ' . $protocolNo);
            }

            if (!self::SEND_DELETION_EMAILS) {
                $emailMutedCount++;
                $this->out('Email muted for protocol: ' . $protocolNo);
                continue;
            }

            $recipient = $this->_resolveRecipientEmail($application);
            if (empty($recipient)) {
                $this->log('No valid reporter email found for protocol: ' . $protocolNo, 'unsubmitted_cleanup_error');
                $this->err('No valid reporter email for protocol ' . $protocolNo);
                continue;
            }

            if ($this->_sendDeletionEmail($recipient, $application, $months)) {
                $emailSentCount++;
            } else {
                $emailFailureCount++;
                $this->err('Failed sending deletion email for protocol ' . $protocolNo);
            }
        }

        $this->out('Cleanup completed.');
        if ($dryRun) {
            $this->out('Would soft delete: ' . count($applications));
            return;
        }

        $this->out('Soft deleted: ' . $deletedCount);
        $this->out('Audit trails created: ' . $auditCreatedCount);
        $this->out('Audit trail failures: ' . $auditFailureCount);
        $this->out('Emails sent: ' . $emailSentCount);
        $this->out('Email failures: ' . $emailFailureCount);
        $this->out('Emails muted: ' . $emailMutedCount);
    }

    protected function _softDeleteApplication($applicationId)
    {
        $runtime = $this->Application->softDelete(null);

        $this->Application->id = $applicationId;
        if (!$this->Application->exists()) {
            return false;
        }

        $this->Application->delete($applicationId, false);

        $this->Application->softDelete(false);
        $deletedValue = $this->Application->field('deleted', array('Application.id' => $applicationId));
        $this->Application->softDelete($runtime);

        return ((string)$deletedValue === '1');
    }

    protected function _resolveRecipientEmail($application)
    {
        if (!empty($application['User']['email']) && Validation::email($application['User']['email'])) {
            return $application['User']['email'];
        }

        if (!empty($application['Application']['email_address']) && Validation::email($application['Application']['email_address'])) {
            return $application['Application']['email_address'];
        }

        return null;
    }

    protected function _createDeletionAuditTrail($application, $months)
    {
        $applicationId = (int)$application['Application']['id'];
        $protocolNo = !empty($application['Application']['protocol_no']) ?
            $application['Application']['protocol_no'] :
            ('ID ' . $applicationId);

        $createdOn = !empty($application['Application']['created']) ?
            $application['Application']['created'] :
            'unknown date';

        $audit = array(
            'AuditTrail' => array(
                'foreign_key' => $applicationId,
                'model' => 'Application',
                'message' => 'Unsubmitted protocol ' . $protocolNo .
                    ' was automatically soft deleted after exceeding ' . (int)$months .
                    ' month(s) threshold. Created on ' . $createdOn . '.',
                'ip' => $protocolNo
            )
        );

        $this->AuditTrail->create();
        if ($this->AuditTrail->save($audit)) {
            return true;
        }

        $this->log(
            'Failed creating cleanup audit trail for protocol ' . $protocolNo,
            'unsubmitted_cleanup_error'
        );
        return false;
    }

    protected function _sendDeletionEmail($recipient, $application, $months)
    {
        $protocolNo = !empty($application['Application']['protocol_no']) ?
            $application['Application']['protocol_no'] :
            ('ID ' . $application['Application']['id']);

        $reporterName = !empty($application['User']['name']) ? $application['User']['name'] : 'Reporter';
        $safeName = htmlspecialchars($reporterName, ENT_QUOTES, 'UTF-8');
        $safeProtocolNo = htmlspecialchars($protocolNo, ENT_QUOTES, 'UTF-8');

        $message = 'Dear ' . $safeName . ',<br><br>' .
            'Your unsubmitted protocol <strong>' . $safeProtocolNo . '</strong> has been automatically deleted ' .
            'because it exceeded the allowed unsubmitted duration of ' . (int)$months . ' month(s).<br><br>' .
            'If this deletion is unexpected, please contact support.<br><br>Regards,<br>PPB';

        $email = new CakeEmail();
        $email->config('gmail');
        $email->template('default');
        $email->emailFormat('html');
        $email->to($recipient);
        $email->subject('Automatic Deletion Notice: ' . $protocolNo);
        $email->viewVars(array('message' => $message));

        if (!$email->send()) {
            $this->log(
                'Failed deletion email for protocol ' . $protocolNo . ' to ' . $recipient,
                'unsubmitted_cleanup_error'
            );
            return false;
        }

        return true;
    }
}
