<?php
App::uses('Hash', 'Utility');
App::uses('ClassRegistry', 'Utility');
if ($redir === 'applicant') {
    $this->Html->script('multi/extrask', array('inline' => false));
}

$former = $this->requestAction('/pockets/checklist/amendment');
$years = array_unique(Hash::extract($application['AmendmentChecklist'], '{n}.year'));
rsort($years);
$namedParams = isset($this->request->params['named']) ? $this->request->params['named'] : array();
$activeAmendmentLetterId = !empty($namedParams['aml']) ? (int)$namedParams['aml'] : 0;
$activeEditAmendmentLetterId = ($redir === 'manager' && !empty($namedParams['ame'])) ? (int)$namedParams['ame'] : 0;

$normalizeAmendmentYear = function ($value) {
    $normalized = strtolower(trim((string) $value));
    $normalized = preg_replace('/^\-+/', '', $normalized);
    $normalized = preg_replace('/^amd[\s_-]*/', '', $normalized);
    if ($normalized === '') {
        return '';
    }

    if (is_numeric($normalized)) {
        $numericValue = (float) $normalized;
        if (floor($numericValue) == $numericValue) {
            $normalized = (string) (int) $numericValue;
        } else {
            $normalized = rtrim(rtrim(number_format($numericValue, 6, '.', ''), '0'), '.');
        }
    } else {
        $normalized = preg_replace('/[^a-z0-9.-]/', '', $normalized);
    }

    if ($normalized === '') {
        return '';
    }

    return 'amd-' . $normalized;
};

$formatAmendmentLabel = function ($value) use ($normalizeAmendmentYear) {
    $normalized = $normalizeAmendmentYear($value);
    if ($normalized === '') {
        return trim((string) $value);
    }

    return 'AMD-' . preg_replace('/^amd-/', '', $normalized);
};

$amendmentSectionOneMap = array();
$amendmentRows = !empty($application['Amendment']) && is_array($application['Amendment']) ? array_values($application['Amendment']) : array();
$needsSectionOneRefetch = false;
foreach ($amendmentRows as $amendmentRow) {
    if (empty($amendmentRow['Amend']) || !is_array($amendmentRow['Amend'])) {
        $needsSectionOneRefetch = true;
        break;
    }
}

if ($needsSectionOneRefetch && !empty($application['Application']['id'])) {
    $Amendment = ClassRegistry::init('Amendment');
    $refetchedRows = $Amendment->find('all', array(
        'conditions' => array('Amendment.application_id' => $application['Application']['id']),
        'contain' => array('Amend', 'CoverLetter'),
        'order' => array('Amendment.id' => 'ASC')
    ));

    $amendmentRows = array();
    foreach ($refetchedRows as $resultRow) {
        if (empty($resultRow['Amendment']) || !is_array($resultRow['Amendment'])) {
            continue;
        }
        $flatRow = $resultRow['Amendment'];
        $flatRow['Amend'] = !empty($resultRow['Amend']) ? $resultRow['Amend'] : array();
        $flatRow['CoverLetter'] = !empty($resultRow['CoverLetter']) ? $resultRow['CoverLetter'] : array();
        $amendmentRows[] = $flatRow;
    }
}

if (!empty($amendmentRows)) {
    usort($amendmentRows, function ($left, $right) {
        $leftId = !empty($left['id']) ? (int) $left['id'] : 0;
        $rightId = !empty($right['id']) ? (int) $right['id'] : 0;
        if ($leftId === $rightId) {
            return 0;
        }
        return ($leftId < $rightId) ? -1 : 1;
    });

    foreach ($amendmentRows as $index => $amendmentRow) {
        $sectionOne = !empty($amendmentRow['Amend']) && is_array($amendmentRow['Amend']) ? $amendmentRow['Amend'] : array();
        $coverLetters = !empty($amendmentRow['CoverLetter']) && is_array($amendmentRow['CoverLetter']) ? $amendmentRow['CoverLetter'] : array();
        $coverFileName = '';
        if (!empty($coverLetters)) {
            $latestCover = end($coverLetters);
            $coverFileName = !empty($latestCover['basename']) ? trim((string) $latestCover['basename']) : '';
            reset($coverLetters);
        }

        $snapshot = array(
            'cover_letter' => !empty($sectionOne['cover_letter']) ? trim((string) $sectionOne['cover_letter']) : '',
            'summary' => !empty($sectionOne['summary']) ? trim((string) $sectionOne['summary']) : '',
            'reason' => !empty($sectionOne['reason']) ? trim((string) $sectionOne['reason']) : '',
            'objectives_impacts' => !empty($sectionOne['objectives_impacts']) ? trim((string) $sectionOne['objectives_impacts']) : '',
            'endpoints_impacts' => !empty($sectionOne['endpoints_impacts']) ? trim((string) $sectionOne['endpoints_impacts']) : '',
            'safety_impacts' => !empty($sectionOne['safety_impacts']) ? trim((string) $sectionOne['safety_impacts']) : '',
            'cover_file' => $coverFileName,
            'created' => !empty($amendmentRow['created']) ? date('d-M-Y H:i', strtotime($amendmentRow['created'])) : ''
        );

        $sequenceKey = $normalizeAmendmentYear($index + 1);
        if ($sequenceKey !== '') {
            $amendmentSectionOneMap[$sequenceKey] = $snapshot;
        }

        $ecctRefKey = $normalizeAmendmentYear(!empty($amendmentRow['ecct_ref_number']) ? $amendmentRow['ecct_ref_number'] : '');
        if ($ecctRefKey !== '' && empty($amendmentSectionOneMap[$ecctRefKey])) {
            $amendmentSectionOneMap[$ecctRefKey] = $snapshot;
        }
    }
}

$discussionByYear = array();
if ($redir === 'manager' || $redir === 'applicant') {
    $Comment = ClassRegistry::init('Comment');
    $discussionRows = $Comment->find('all', array(
        'contain' => array('Attachment'),
        'conditions' => array(
            'Comment.model_id' => $application['Application']['id'],
            'Comment.model' => 'Amendment',
            'Comment.category LIKE' => 'amendment-discussion-%',
            'Comment.deleted' => null
        ),
        'order' => array('Comment.created ASC')
    ));

    foreach ($discussionRows as $discussionRow) {
        $category = (string) $discussionRow['Comment']['category'];
        $yearKey = preg_replace('/^amendment-discussion-/', '', $category);
        if (empty($yearKey)) {
            continue;
        }
        if (!isset($discussionByYear[$yearKey])) {
            $discussionByYear[$yearKey] = array();
        }
        $commentPayload = $discussionRow['Comment'];
        $commentPayload['Attachment'] = isset($discussionRow['Attachment']) ? $discussionRow['Attachment'] : array();
        $discussionByYear[$yearKey][] = $commentPayload;
    }
}
?>

<style type="text/css">
    .amendment-decision-row {
        margin-top: 4px;
    }

    .amendment-decision-row .decision-field {
        display: inline-block;
        vertical-align: bottom;
        margin: 0 10px 8px 0;
    }

    .amendment-decision-row .decision-field label {
        display: block;
        margin: 0 0 3px 0;
        color: #666;
        font-size: 11px;
        line-height: 14px;
        font-weight: 600;
    }

    .amendment-decision-row .decision-field input,
    .amendment-decision-row .decision-field select {
        margin-bottom: 0;
    }

    .amendment-decision-row .decision-comment .input-xlarge {
        width: 260px;
    }

    .amendment-decision-row .decision-submit .btn {
        margin-top: 17px;
    }

    .amendment-uploaded-meta {
        margin-left: 6px;
        font-size: 10px;
        line-height: 12px;
        color: #888;
        white-space: nowrap;
        display: inline-block;
    }

</style>

<h4 style="background-color: #37732c; color: #fff; text-align: center;">Amendments Checklist </h4>
<p><small>All submitted documents should be version referenced and dated.</small></p>
<div style="overflow-x:auto;">
    <table class="table table-bordered table-condensed table-striped">
        <thead>
            <tr>
                <th style="min-width: 110px;">Amendment</th>
                <?php
                echo $this->element('amendments/section_one_snapshot', array(
                    'format' => 'column_headers'
                ));
                ?>
                <th class="actions" style="min-width: 320px;"><?php echo __('Files'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($years as $year) : ?>
                <?php
                $yearLabel = $formatAmendmentLabel($year);
                $yearKey = $normalizeAmendmentYear($year);
                $sectionOneSnapshot = ($yearKey !== '' && !empty($amendmentSectionOneMap[$yearKey])) ? $amendmentSectionOneMap[$yearKey] : array();
                ?>
                <tr>
                    <td><b><?php echo h($yearLabel); ?></b></td>
                    <?php
                    echo $this->element('amendments/section_one_snapshot', array(
                        'sectionOne' => $sectionOneSnapshot,
                        'format' => 'column_values',
                        'emptyText' => 'Not provided'
                    ));
                    ?>
                    <td>
                    <?php
                    $f = 0;
                    foreach ($former as $rem => $mer) {
                        $f++;
                        echo "<div id='" . h($rem . $year) . "'>";
                        echo "$f. ";
                        echo h($mer) . "<br/>";

                        foreach ($application['AmendmentChecklist'] as $anc) {
                            if ($anc['year'] == $year && $anc['pocket_name'] == $rem) {
                                $id = $anc['id'];

                                $deleteUrl = array('controller' => 'attachments', 'action' => 'delete', $id, 'ext' => 'json');
                                if ($redir === 'applicant') {
                                    $deleteUrl['applicant'] = true;
                                } elseif ($redir === 'manager') {
                                    $deleteUrl['manager'] = true;
                                }
                                $deleteHref = $this->Html->url($deleteUrl);

                                echo "<span id='amendmentAttachmentRow$id' class='amendment-attachment-row'>";
                                echo "&nbsp;&nbsp; <span id='" . h($rem . $id) . "'> &nbsp;<i class='icon-file-text-alt'></i> ";
                                echo $this->Html->link(
                                    __($anc['basename']),
                                    array('controller' => 'attachments', 'action' => 'download', $anc['id'], 'full_base' => true),
                                    array('class' => '')
                                );

                                $versionNo = $anc['version_no'];
                                $fileDate = $anc['file_date'];
                                $uploadedAt = !empty($anc['created']) ? date('d-m-Y H:i', strtotime($anc['created'])) : 'N/A';

                                echo "</span>&nbsp;
                                      <span id='version$id' style='margin-left:10px;'>Version: $versionNo</span>
                                      <span id='fileDate$id' style='margin-left:10px;'>Dated: $fileDate</span>
                                      <span id='uploadedAt$id' class='amendment-uploaded-meta'>Uploaded: $uploadedAt</span>";

                                if ($redir === 'applicant' || $redir === 'manager') {
                                    echo "<span id='AmendmentChecklist$id' data-delete-url='$deleteHref' style='margin-left:10px;' class='btn btn-mini delete_amendment_checklist_file' title='Delete attachment'><i class='icon-remove'></i></span>";
                                }

                                echo "</span><br>";
                            }
                        }

                        echo "</div>";
                    }

                    $additionalFiles = array();
                    foreach ($application['AmendmentChecklist'] as $anc) {
                        if ($anc['year'] == $year && trim((string) $anc['pocket_name']) === '') {
                            $additionalFiles[] = $anc;
                        }
                    }
                    ?>

                    <?php if (!empty($additionalFiles)) : ?>
                        <hr style="margin: 8px 0;">
                        <small><strong>Additional Files</strong></small><br>
                        <?php foreach ($additionalFiles as $index => $anc) : ?>
                            <?php
                            $id = $anc['id'];
                            $versionNo = $anc['version_no'];
                            $fileDate = $anc['file_date'];
                            $uploadedAt = !empty($anc['created']) ? date('d-m-Y H:i', strtotime($anc['created'])) : 'N/A';
                            $description = $anc['description'];

                            $deleteUrl = array('controller' => 'attachments', 'action' => 'delete', $id, 'ext' => 'json');
                            if ($redir === 'applicant') {
                                $deleteUrl['applicant'] = true;
                            } elseif ($redir === 'manager') {
                                $deleteUrl['manager'] = true;
                            }
                            $deleteHref = $this->Html->url($deleteUrl);
                            ?>
                            <?php echo ($index + 1) . '. ' . h($description); ?><br>
                            <span id="additionalAttachmentRow<?php echo (int)$id; ?>" class="amendment-attachment-row">
                                &nbsp;&nbsp;<span id="Additional<?php echo (int)$id; ?>">&nbsp;<i class="icon-file-text-alt"></i>
                                    <?php
                                    echo $this->Html->link(
                                        __($anc['basename']),
                                        array('controller' => 'attachments', 'action' => 'download', $anc['id'], 'full_base' => true),
                                        array('class' => '')
                                    );
                                    ?>
                                </span>&nbsp;
                                <span style="margin-left:10px;">Version: <?php echo h($versionNo); ?></span>
                                <span style="margin-left:10px;">Dated: <?php echo h($fileDate); ?></span>
                                <span class="amendment-uploaded-meta">Uploaded: <?php echo h($uploadedAt); ?></span>
                                <?php if ($redir === 'applicant' || $redir === 'manager') : ?>
                                    <span id="AdditionalDelete<?php echo (int)$id; ?>" data-delete-url="<?php echo h($deleteHref); ?>" style="margin-left:10px;" class="btn btn-mini delete_amendment_checklist_file" title="Delete attachment"><i class="icon-remove"></i></span>
                                <?php endif; ?>
                            </span><br>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php
                    $discussionItems = isset($discussionByYear[(string) $year]) ? $discussionByYear[(string) $year] : array();
                    $discussionYearSlug = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $year);
                    $discussionCategory = 'amendment-discussion-' . (string) $year;
                    ?>
                    <?php if ($redir === 'manager' || $redir === 'applicant') : ?>
                        <hr style="margin: 10px 0;">
                        <small><strong>Manager / Applicant Discussion</strong></small>
                        <div class="amend-form" style="margin-top: 6px;">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#amendment-comment-list-<?php echo h($discussionYearSlug); ?>" data-toggle="tab">COMMENTS/QUERIES</a></li>
                                <li><a href="#amendment-comment-add-<?php echo h($discussionYearSlug); ?>" data-toggle="tab">Add Comment</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="amendment-comment-list-<?php echo h($discussionYearSlug); ?>">
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <?php echo $this->element('comments/list_expandable', array('comments' => $discussionItems, 'category' => false)); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="amendment-comment-add-<?php echo h($discussionYearSlug); ?>">
                                    <div class="row-fluid">
                                        <div class="span12">
                                            <?php
                                            echo $this->element('comments/add', array(
                                                'model' => array(
                                                    'model_id' => $application['Application']['id'],
                                                    'foreign_key' => $application['Application']['id'],
                                                    'model' => 'Amendment',
                                                    'category' => $discussionCategory,
                                                    'url' => 'add_amendment_discussion',
                                                ),
                                                'comments' => array(),
                                            ));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($redir === 'manager') : ?>
                        <?php $yearSlug = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $year); ?>
                        <hr style="margin: 10px 0;">
                        <a class="btn btn-link btn-comment" role="button" data-toggle="collapse" href="#amendment-review-<?php echo h($yearSlug); ?>" aria-controls="amendment-review-<?php echo h($yearSlug); ?>">Review &amp; Approve</a>

                        <div id="amendment-review-<?php echo h($yearSlug); ?>" class="collapse">
                            <div class="well" style="margin-bottom: 10px;">
                                <h5 class="text-info">Summary Report</h5>
                                <?php
                                echo $this->Form->create('AmendmentApproval', array(
                                    'url' => array('controller' => 'amendment_approvals', 'action' => 'approve_amendment', $application['Application']['id']),
                                    'type' => 'file',
                                    'class' => 'form-horizontal',
                                    'inputDefaults' => array(
                                        'div' => array('class' => 'control-group'),
                                        'label' => array('class' => 'control-label'),
                                        'between' => '<div class="controls">',
                                        'after' => '</div>',
                                        'class' => '',
                                        'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
                                        'error' => array('attributes' => array('class' => 'controls help-block')),
                                    ),
                                ));
                                echo $this->Form->input('application_id', array('value' => $application['Application']['id'], 'type' => 'hidden'));
                                echo $this->Form->input('approval_date', array('value' => date('d-m-Y'), 'type' => 'hidden'));
                                echo $this->Form->input('amendment', array('value' => $year, 'type' => 'hidden'));
                                echo $this->Form->input('status', array('value' => 'summary', 'type' => 'hidden'));
                                echo $this->Form->input('content', array(
                                    'label' => array('class' => 'control-label', 'text' => 'Summary Notes'),
                                    'placeholder' => 'Enter summary notes (optional)',
                                    'rows' => 3,
                                    'class' => 'input-xxlarge',
                                ));
                                echo $this->Form->input('Attachment.0.model', array('type' => 'hidden', 'value' => 'AmendmentApproval'));
                                echo $this->Form->input('Attachment.0.category', array('type' => 'hidden', 'value' => $year));
                                echo $this->Form->input('Attachment.0.file', array(
                                    'type' => 'file',
                                    'label' => array('class' => 'control-label', 'text' => 'Attach Summary File'),
                                    'class' => 'input-xlarge',
                                ));
                                echo $this->Form->input('Attachment.0.description', array(
                                    'label' => array('class' => 'control-label', 'text' => 'File Description'),
                                    'rows' => 2,
                                    'class' => 'input-xxlarge',
                                ));
                                ?>
                                <div class="controls">
                                    <?php
                                    echo $this->Form->button('<i class="icon-save"></i> Submit Summary', array(
                                        'name' => 'submit',
                                        'class' => 'btn btn-primary',
                                        'escape' => false,
                                    ));
                                    ?>
                                </div>
                                <?php echo $this->Form->end(); ?>

                                <?php
                                $summaryItems = array();
                                foreach ($application['AmendmentApprovalSummary'] as $summary) {
                                    if ((string) $summary['amendment'] === (string) $year) {
                                        $summaryItems[] = $summary;
                                    }
                                }
                                ?>

                                <?php if (!empty($summaryItems)) : ?>
                                    <hr>
                                    <h6>Submitted Summary Reports</h6>
                                    <?php foreach ($summaryItems as $summary) : ?>
                                        <div class="well" style="padding: 8px; margin-bottom: 8px;">
                                            <p style="margin: 0 0 4px 0;"><strong>Date:</strong> <?php echo h($summary['approval_date']); ?></p>
                                            <?php if (!empty($summary['content'])) : ?>
                                                <p style="margin: 0 0 6px 0;"><strong>Notes:</strong> <?php echo h($summary['content']); ?></p>
                                            <?php endif; ?>
                                            <?php if (!empty($summary['Attachment'])) : ?>
                                                <p style="margin: 0 0 4px 0;"><strong>Attachments:</strong></p>
                                                <?php foreach ($summary['Attachment'] as $attachment) : ?>
                                                    <p style="margin: 0;">
                                                        <?php
                                                        echo $this->Html->link(
                                                            __($attachment['basename']),
                                                            array('controller' => 'amendment_approvals', 'action' => 'file_download', $attachment['id'], 'manager' => false),
                                                            array('class' => 'btn btn-link')
                                                        );
                                                        ?>
                                                    </p>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <div class="well" style="margin-bottom: 10px;">
                                <h5 class="text-success">Approve or Reject Amendment</h5>
                                <?php
                                echo $this->Form->create('AmendmentApproval', array(
                                    'url' => array('controller' => 'amendment_approvals', 'action' => 'approve', $application['Application']['id']),
                                    'type' => 'post',
                                    'class' => 'form-inline amendment-decision-form',
                                ));
                                echo $this->Form->input('application_id', array('value' => $application['Application']['id'], 'type' => 'hidden'));
                                echo $this->Form->input('approval_date', array('value' => date('d-m-Y'), 'type' => 'hidden'));
                                echo $this->Form->input('amendment', array('value' => $year, 'type' => 'hidden'));
                                ?>
                                <div class="amendment-decision-row">
                                    <div class="decision-field">
                                        <label for="amendment-status-<?php echo h($yearSlug); ?>">Decision</label>
                                        <?php
                                        echo $this->Form->input('status', array(
                                            'type' => 'select',
                                            'label' => false,
                                            'div' => false,
                                            'empty' => false,
                                            'options' => array('approved' => 'Approve', 'rejected' => 'Reject'),
                                            'class' => 'input-medium',
                                            'id' => 'amendment-status-' . $yearSlug,
                                        ));
                                        ?>
                                    </div>

                                    <div class="decision-field decision-comment">
                                        <label for="amendment-comment-<?php echo h($yearSlug); ?>">Comment</label>
                                        <?php
                                        echo $this->Form->input('content', array(
                                            'type' => 'text',
                                            'label' => false,
                                            'div' => false,
                                            'placeholder' => 'Optional comment',
                                            'class' => 'input-xlarge',
                                            'id' => 'amendment-comment-' . $yearSlug,
                                        ));
                                        ?>
                                    </div>

                                    <div class="decision-field">
                                        <label for="amendment-password-<?php echo h($yearSlug); ?>">Password</label>
                                        <?php
                                        echo $this->Form->input('password', array(
                                            'type' => 'password',
                                            'label' => false,
                                            'div' => false,
                                            'class' => 'input-medium',
                                            'placeholder' => 'Password',
                                            'id' => 'amendment-password-' . $yearSlug,
                                        ));
                                        ?>
                                    </div>

                                    <div class="decision-field decision-submit">
                                        <?php
                                        echo $this->Form->button('<i class="icon-thumbs-up"></i> Submit Decision', array(
                                            'name' => 'submit',
                                            'class' => 'btn btn-primary',
                                            'escape' => false,
                                        ));
                                        ?>
                                    </div>
                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php
                    $yearLetters = array();
                    foreach ($application['AmendmentLetter'] as $letter) {
                        if ((string) $letter['status'] === (string) $year) {
                            $yearLetters[] = $letter;
                        }
                    }
                    ?>

                    <hr style="margin: 10px 0;">
                    <small><strong>Amendment Approval Letters</strong></small>
                    <?php if (empty($yearLetters)) : ?>
                        <p class="muted" style="margin-top: 6px;">No amendment approval letter has been generated for this amendment yet.</p>
                    <?php else : ?>
                        <table class="table table-bordered table-condensed" style="margin-top: 6px;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Approval No.</th>
                                    <th>Approval Date</th>
                                    <th>Created</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($yearLetters as $letter) : ?>
                                    <?php
                                    $isSubmittedLetter = ((string) $letter['submitted'] === '1' || (int) $letter['submitted'] === 1);
                                    if ($redir !== 'manager' && !$isSubmittedLetter) {
                                        continue;
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo h($letter['id']); ?></td>
                                        <td><?php echo h($letter['approval_no']); ?></td>
                                        <td><?php echo h($letter['approval_date']); ?></td>
                                        <td><?php echo h($letter['created']); ?></td>
                                        <td><?php echo $isSubmittedLetter ? 'Submitted' : 'Draft'; ?></td>
                                        <td>
                                            <?php
                                            $viewRoute = array(
                                                'controller' => 'applications',
                                                'action' => 'view',
                                                $application['Application']['id'],
                                                'aml' => $letter['id']
                                            );
                                            if ($redir === 'manager') {
                                                $viewRoute['manager'] = true;
                                            } elseif ($redir === 'applicant') {
                                                $viewRoute['applicant'] = true;
                                            }
                                            echo $this->Html->link(
                                                '<span class="label label-info">View</span>',
                                                $viewRoute,
                                                array('escape' => false, 'style' => 'margin-right: 5px;')
                                            );

                                            if ($redir === 'manager' && !$isSubmittedLetter) {
                                                echo $this->Html->link(
                                                    '<span class="label label-success">Edit Draft</span>',
                                                    array(
                                                        'controller' => 'applications',
                                                        'action' => 'view',
                                                        $application['Application']['id'],
                                                        'ame' => $letter['id'],
                                                        'manager' => true
                                                    ),
                                                    array('escape' => false, 'style' => 'margin-right: 5px;')
                                                );

                                                echo $this->Html->link(
                                                    '<span class="label label-warning">Finalize</span>',
                                                    array('controller' => 'amendment_letters', 'action' => 'capprove', $letter['id']),
                                                    array('escape' => false, 'style' => 'margin-right: 5px;')
                                                );
                                            }

                                            $pdfRoute = array('controller' => 'amendment_letters', 'action' => 'download', $letter['id'], 'ext' => 'pdf');
                                            if ($redir === 'manager') {
                                                $pdfRoute['manager'] = true;
                                            } elseif ($redir === 'applicant') {
                                                $pdfRoute['applicant'] = true;
                                            }
                                            echo $this->Html->link(
                                                '<span class="label label-inverse">Download PDF</span>',
                                                $pdfRoute,
                                                array('escape' => false)
                                            );
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>

                    <?php
                    $inlineViewLetter = null;
                    if ($activeAmendmentLetterId > 0) {
                        foreach ($yearLetters as $letterItem) {
                            if ((int)$letterItem['id'] !== $activeAmendmentLetterId) {
                                continue;
                            }
                            $canViewLetter = ($redir === 'manager' || (string)$letterItem['submitted'] === '1' || (int)$letterItem['submitted'] === 1);
                            if ($canViewLetter) {
                                $inlineViewLetter = $letterItem;
                            }
                            break;
                        }
                    }

                    $inlineEditLetter = null;
                    if ($redir === 'manager' && $activeEditAmendmentLetterId > 0) {
                        foreach ($yearLetters as $letterItem) {
                            if ((int)$letterItem['id'] === $activeEditAmendmentLetterId) {
                                $inlineEditLetter = $letterItem;
                                break;
                            }
                        }
                    }
                    ?>

                    <?php if (!empty($inlineViewLetter)) : ?>
                        <div class="well" style="margin-top: 8px;">
                            <h5 class="text-info">Amendment Approval Letter</h5>
                            <p class="muted" style="margin-bottom: 10px;">
                                Approval No: <strong><?php echo h($inlineViewLetter['approval_no']); ?></strong>
                                <span style="margin-left: 10px;">Status: <strong><?php echo ((string)$inlineViewLetter['submitted'] === '1' || (int)$inlineViewLetter['submitted'] === 1) ? 'Submitted' : 'Draft'; ?></strong></span>
                            </p>
                            <div>
                                <?php echo $inlineViewLetter['content']; ?>
                                <?php if (!empty($inlineViewLetter['qrcode'])) : ?>
                                    <div style="margin-top: 8px;"><?php echo base64_decode($inlineViewLetter['qrcode']); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($inlineEditLetter) && $redir === 'manager') : ?>
                        <?php $editorId = 'AmendmentLetterContentEditor' . (int)$inlineEditLetter['id']; ?>
                        <div class="ctr-groups" style="margin-top: 8px;">
                            <?php
                            echo $this->Form->create('AmendmentLetter', array(
                                'url' => array('controller' => 'amendment_letters', 'action' => 'approve', $inlineEditLetter['id'], 'manager' => true),
                                'type' => 'file',
                                'class' => 'form-horizontal',
                                'inputDefaults' => array(
                                    'div' => array('class' => 'control-group'),
                                    'label' => array('class' => 'control-label'),
                                    'between' => '<div class="controls">',
                                    'after' => '</div>',
                                    'class' => '',
                                    'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
                                    'error' => array('attributes' => array('class' => 'controls help-block')),
                                ),
                            ));
                            ?>
                            <fieldset>
                                <legend>Edit Amendment Approval Letter</legend>
                                <?php
                                echo $this->Form->input('id', array('type' => 'hidden', 'value' => $inlineEditLetter['id']));
                                echo $this->Form->input('approval_date', array(
                                    'div' => array('class' => 'control-group'),
                                    'type' => 'text',
                                    'value' => $inlineEditLetter['approval_date'],
                                    'class' => 'datepickers-amendment-letter',
                                    'label' => array('class' => 'control-label required', 'text' => 'Approval date <span class="sterix">*</span>'),
                                    'after' => '<span class="help-inline">Date format (dd-mm-yyyy)</span></div>',
                                ));
                                echo $this->Form->input('expiry_date', array(
                                    'div' => array('class' => 'control-group'),
                                    'type' => 'text',
                                    'value' => $inlineEditLetter['expiry_date'],
                                    'class' => 'datepickers-amendment-letter',
                                    'label' => array('class' => 'control-label required', 'text' => 'Expiry date <span class="sterix">*</span>'),
                                    'after' => '<span class="help-inline">Date format (dd-mm-yyyy)</span></div>',
                                ));
                                echo $this->Form->input('content', array(
                                    'label' => false,
                                    'value' => $inlineEditLetter['content'],
                                    'between' => '<div class="controle">',
                                    'class' => 'input-large',
                                    'id' => $editorId,
                                ));
                                ?>
                            </fieldset>
                            <div class="form-actions">
                                <?php
                                if ((string)$inlineEditLetter['submitted'] !== '1' && (int)$inlineEditLetter['submitted'] !== 1) {
                                    echo $this->Form->submit('Save as Draft', array(
                                        'name' => 'saveDraft',
                                        'class' => 'btn btn-warning',
                                        'div' => false
                                    ));
                                    echo "&nbsp;";
                                    echo $this->Form->submit('Paste Signature and Submit', array(
                                        'name' => 'submitLetter',
                                        'class' => 'btn btn-success',
                                        'div' => false
                                    ));
                                } else {
                                    echo $this->Form->submit('Save Changes', array(
                                        'name' => 'saveChanges',
                                        'class' => 'btn btn-info',
                                        'div' => false
                                    ));
                                    echo "&nbsp;";
                                    echo $this->Form->submit('Paste Signature and Submit', array(
                                        'name' => 'submitLetter',
                                        'class' => 'btn btn-success',
                                        'div' => false
                                    ));
                                }
                                ?>
                            </div>
                            <?php echo $this->Form->end(); ?>
                        </div>
                        <script type="text/javascript">
                          (function ($) {
                            $(".datepickers-amendment-letter").datepicker({
                              minDate: "-5Y",
                              maxDate: "+999D",
                              dateFormat: "dd-mm-yy",
                              showButtonPanel: true,
                              changeMonth: true,
                              changeYear: true,
                              buttonImageOnly: true,
                              showAnim: "show",
                              showOn: "both",
                              buttonImage: "/img/calendar.gif"
                            });
                          })(jQuery);

                          if (typeof CKEDITOR !== "undefined") {
                            CKEDITOR.replace(<?php echo json_encode($editorId); ?>);
                          }
                        </script>
                    <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<hr>

<?php if ($redir == 'applicant') : ?>
    <ul id="amendment-upload-tabs" class="nav nav-tabs">
        <li class="active"><a href="#amendment-checklist-pane" data-toggle="tab">Checklist</a></li>
        <li><a href="#amendment-additional-pane" data-toggle="tab">Additional Files</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="amendment-checklist-pane">
            <div class="well">
        <table id="amendmentchecklisttable" class="table table-bordered table-condensed table-striped">
            <thead>
                <tr id="approvalsTableHeader">
                    <th>#</th>
                    <th style="width: 10%;">
                        <small class="muted">Select Number</small>
                        <?php
                        $numbers = range(1, 10);
                        $keyValuePairs = array_combine($numbers, $numbers);

                        echo $this->Form->input('Fake.year', array(
                            'type' => 'select',
                            'label' => false,
                            'between' => false,
                            'after' => false,
                            'div' => false,
                            'options' => $keyValuePairs,
                            'readonly' => 'readonly',
                            'data-original-title' => 'Click here to change amendment number',
                            'class' => 'span12 amendmentyear tiptip'
                        ));
                        ?>
                    </th>
                    <th style="width: 40%;">Description</th>
                    <th>File <span class="sterix">*</span></th>
                    <th style="width: 7%">Version No.</th>
                    <th style="width: 12%">Date <small class="muted">(dd-mm-yyyy)</small></th>
                    <th style="width: 7%">Submit</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                $key = 0;
                foreach ($former as $pos => $value) {
                    $i++;
                ?>
                    <tr>
                        <td><?php $key++; echo $i; ?></td>
                        <td>
                            <?php
                            echo $this->Form->input('AmendmentChecklist.' . $key . '.model', array('type' => 'hidden', 'value' => 'AmendmentChecklist'));
                            echo $this->Form->input('AmendmentChecklist.' . $key . '.group', array('type' => 'hidden', 'value' => $pos));
                            echo $this->Form->input('AmendmentChecklist.' . $key . '.filesize', array('type' => 'hidden'));
                            echo $this->Form->input('AmendmentChecklist.' . $key . '.basename', array('type' => 'hidden'));
                            echo $this->Form->input('AmendmentChecklist.' . $key . '.checksum', array('type' => 'hidden'));

                            echo $this->Form->input('AmendmentChecklist.' . $key . '.year', array(
                                'type' => 'text',
                                'label' => false,
                                'between' => false,
                                'after' => false,
                                'div' => false,
                                'readonly' => 'readonly',
                                'class' => 'span11 checklistyearyear'
                            ));
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $this->Form->input('AmendmentChecklist.' . $key . '.description', array('type' => 'hidden', 'value' => $value));
                            echo $this->Form->input('AmendmentChecklist.' . $key . '.pocket_name', array('type' => 'hidden', 'value' => $pos));
                            echo '<p>' . h($value) . '</p>';
                            ?>
                        </td>
                        <td class="files">
                            <?php
                            echo $this->Form->input('AmendmentChecklist.' . $key . '.file', array(
                                'label' => false,
                                'between' => false,
                                'after' => false,
                                'div' => false,
                                'class' => 'span12 input-file',
                                'error' => array('escape' => false, 'attributes' => array('class' => 'help-block')),
                                'type' => 'file',
                            ));
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($this->fetch('is-applicant') == 'true') {
                                echo $this->Form->input('AmendmentChecklist.' . $key . '.version_no', array(
                                    'label' => false,
                                    'between' => false,
                                    'after' => false,
                                    'div' => false,
                                    'placeholder' => 'Version',
                                    'class' => 'span12 input-file',
                                    'error' => array('escape' => false, 'attributes' => array('class' => 'help-block')),
                                ));
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($this->fetch('is-applicant') == 'true') {
                                echo $this->Form->input('AmendmentChecklist.' . $key . '.file_date', array(
                                    'type' => 'text',
                                    'label' => false,
                                    'between' => false,
                                    'after' => false,
                                    'div' => false,
                                    'placeholder' => 'dd-mm-yyyy',
                                    'class' => 'span12 input-file pickadate',
                                    'error' => array('escape' => false, 'attributes' => array('class' => 'help-block')),
                                ));
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $this->Form->button('<i class="icon-save"></i> ', array(
                                'name' => 'addApproval',
                                'type' => 'button',
                                'class' => 'btn btn-primary add-approval tiptip',
                                'data-original-title' => 'Add a file',
                                'escape' => false,
                                'div' => false,
                            ));
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
            </div>
        </div>

        <div class="tab-pane" id="amendment-additional-pane">
            <div class="well">
        <p class="selected-year-name muted"></p>
        <h5><i class="icon-file"></i> Add additional files:
            <button type="button" class="btn-mini" id="addAttachmentA">&nbsp;<i class="icon-plus"></i>&nbsp;</button>
        </h5>
        <table id="buildamendmentform" class="table table-bordered table-condensed table-striped">
            <thead>
                <tr id="amendmentsTableHeader">
                    <th>#</th>
                    <th width="30%">File</th>
                    <th width="40%">Description</th>
                    <th width="5%">Version</th>
                    <th width="10%">Date</th>
                    <th width="15%">Action</th>
                </tr>
            </thead>
        </table>

        <div class="ctr-groups" style="margin-top: 10px;">
            <?php
            echo $this->Html->link(
                __('<i class="icon-thumbs-up"></i> Submit All'),
                '#',
                array(
                    'escape' => false,
                    'class' => 'btn btn-info',
                    'id' => 'submit-all-button'
                )
            );
            ?>
        </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script type="text/javascript">
  $(function () {
    function normalizeAmendmentYear(value) {
      var normalized = (value || '').toString().trim().toLowerCase();
      normalized = normalized.replace(/^-+/, '');
      normalized = normalized.replace(/^amd[\s_-]*/i, '');
      if (!normalized) {
        return '';
      }

      if (/^\d+(\.\d+)?$/.test(normalized)) {
        var numericValue = parseFloat(normalized);
        if (!isNaN(numericValue)) {
          if (Math.floor(numericValue) === numericValue) {
            normalized = String(parseInt(numericValue, 10));
          } else {
            normalized = String(numericValue).replace(/(\.\d*?[1-9])0+$/, '$1').replace(/\.0+$/, '');
          }
        }
      }

      return normalized ? 'amd-' + normalized : '';
    }

    $(document).off('click', '.delete_amendment_checklist_file').on('click', '.delete_amendment_checklist_file', function () {
      var trigger = $(this);
      if (!confirm('Are you sure you would like to delete this attachment?')) {
        return;
      }

      var intId = parseInt(trigger.attr('id').replace(/\D/g, ''), 10);
      if (!intId) {
        alert('Invalid attachment selected.');
        return;
      }

      var deleteUrl = trigger.attr('data-delete-url') || ('/attachments/delete/' + intId + '.json');
      $.ajax({
        type: 'POST',
        url: deleteUrl,
        data: { id: intId },
        success: function () {
          window.location.reload();
        },
        error: function (xhr) {
          if (xhr && xhr.status === 200) {
            window.location.reload();
            return;
          }
          alert('Failed to delete attachment.');
        }
      });
    });

    var submitButton = document.getElementById('submit-all-button');
    if (!submitButton) {
      return;
    }

    submitButton.addEventListener('click', function (event) {
      event.preventDefault();

      var selectedElement = document.querySelector('.selected-year-name');
      var selectedYear = '';
      if (selectedElement) {
        selectedYear = (selectedElement.getAttribute('data-year-value') || '').trim();
        if (!selectedYear) {
          selectedYear = normalizeAmendmentYear(selectedElement.textContent);
        }
      }
      if (!selectedYear) {
        var selector = document.querySelector('.amendmentyear');
        if (selector && selector.value) {
          selectedYear = normalizeAmendmentYear(selector.value);
        }
      }

      selectedYear = normalizeAmendmentYear(selectedYear);

      if (!selectedYear) {
        alert('Please select an amendment number before submitting.');
        return;
      }

      if (!confirm('Are you sure you want to submit all amendment files for this amendment number?')) {
        return;
      }

      var submitBaseUrl = <?php echo json_encode($this->Html->url(array('controller' => 'applications', 'action' => 'submitall', $application['Application']['id'], 'applicant' => true))); ?>;
      window.location.href = submitBaseUrl + '/' + encodeURIComponent(selectedYear);
    });
  });
</script>
