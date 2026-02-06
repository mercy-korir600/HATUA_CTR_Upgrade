<!-- Annual Approval Checklists -->
<h4 style="background-color: #37732c; color: #fff; text-align: center;">Amendments Checklist </h4>
<p><small>All submitted documents should be version referenced and dated.</small></p>
<table class="table table-bordered table-condensed table-striped">
    <thead>
        <tr>
            <th>Number</th>
            <th class="actions"><?php echo __('Files'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        App::uses('Hash', 'Utility');
        $former = $this->requestAction('/pockets/checklist/amendment');
        $years = array_unique(Hash::extract($application['AmendmentChecklist'], '{n}.year'));
        rsort($years);
        foreach ($years as $year) : ?>
            <tr class="">
                <td><b><?php echo h($year); ?></b></td>
                <td>
                    <?php
                    $f = 0;
                    foreach ($former as $rem => $mer) {
                        $f++;
                        echo "<div id='$rem$year'>";
                        echo "$f. ";
                        echo "$mer<br/>";
                        foreach ($application['AmendmentChecklist'] as $anc) {
                            if ($anc['year'] == $year && $anc['pocket_name'] == $rem) {
                                $id = $anc['id'];
                                echo "&nbsp;&nbsp; <span id='$rem$id'> &nbsp;<i class='icon-file-text-alt'></i> ";
                                echo $this->Html->link(
                                    __($anc['basename']),
                                    array('controller' => 'attachments', 'action' => 'download', $anc['id'], 'full_base' => true),
                                    array('class' => '')
                                );
                                $version_no = $anc['version_no'];
                                $file_date = $anc['file_date'];
                                echo "</span>&nbsp;
                          <span id='version$id' style='margin-left:10px;'>Version: $version_no</span>
                          <span id='fileDate$id' style='margin-left:10px;'>Dated: $file_date</span>
                          <span id='AmendmentChecklist$id' style='margin-left:10px;' class='btn btn-mini'><i class='icon-remove'></i></span>
                          <br>";
                            }
                        }
                        echo "</div>";
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<hr>

<?php
if ($redir == 'applicant') { ?>
    <h5>Checklist Form</h5>
    <div class="well">
        <table id="amendmentchecklisttable" class="table table-bordered  table-condensed table-striped">
            <thead>
                <tr id="approvalsTableHeader">
                    <th>#</th>
                    <th style="width: 10%;">
                        <small class="muted">Select Number</small>
                        <?php
                        $numbers = range(1, 10);

                        // Generate key-value pairs with the same numbers
                        $keyValuePairs = array_combine($numbers, $numbers);

                        echo $this->Form->input('Fake.year', array(
                            'type' => 'select',
                            'label' => false,
                            'between' => false,
                            'after' => false,
                            'div' => false,
                            'options' => $keyValuePairs,
                            'readonly' => 'readonly',
                            'data-original-title' => "Click here to change years",
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
                        <td><?php $key++;
                            echo $i; ?></td>
                        <td>
                            <?php
                            echo $this->Form->input('AmendmentChecklist.' . $key . '.model', array('type' => 'hidden', 'value' => 'AmendmentChecklist'));
                            echo $this->Form->input('AmendmentChecklist.' . $key . '.group', array('type' => 'hidden', 'value' => $pos));
                            echo $this->Form->input('AmendmentChecklist.' . $key . '.filesize', array('type' => 'hidden'));
                            echo $this->Form->input('AmendmentChecklist.' . $key . '.basename', array('type' => 'hidden'));
                            echo $this->Form->input('AmendmentChecklist.' . $key . '.checksum', array('type' => 'hidden'));

                            echo $this->Form->input('AmendmentChecklist.' . $key . '.year', array(
                                'type' => 'text', 'label' => false, 'between' => false, 'after' => false, 'div' => false,
                                'readonly' => 'readonly', 'class' => 'span11 checklistyearyear'
                            ));
                            ?>

                        </td>
                        <td>
                            <?php
                            echo $this->Form->input('AmendmentChecklist.' . $key . '.description', array('type' => 'hidden', 'value' => $value));
                            echo $this->Form->input('AmendmentChecklist.' . $key . '.pocket_name', array('type' => 'hidden', 'value' => $pos));
                            echo '<p>' . $value . '</p>';
                            ?>
                        </td>
                        <td class="files"><?php
                                            echo $this->Form->input('AmendmentChecklist.' . $key . '.file', array(
                                                'label' => false, 'between' => false, 'after' => false, 'div' => false, 'class' => 'span12 input-file',
                                                'error' => array('escape' => false, 'attributes' => array('class' => 'help-block')),
                                                'type' => 'file',
                                            ));
                                            ?>
                        </td>
                        <td>
                            <?php
                            if ($this->fetch('is-applicant') == 'true')  echo $this->Form->input('AmendmentChecklist.' . $key . '.version_no', array(
                                'label' => false, 'between' => false, 'after' => false, 'div' => false, 'placeholder' => 'Version', 'class' => 'span12 input-file',
                                'error' => array('escape' => false, 'attributes' => array('class' => 'help-block')),
                            ));
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($this->fetch('is-applicant') == 'true')  echo $this->Form->input('AmendmentChecklist.' . $key . '.file_date', array(
                                'type' => 'text', 'label' => false, 'between' => false, 'after' => false, 'div' => false, 'placeholder' => 'dd-mm-yyyy', 'class' => 'span12 input-file pickadate',
                                'error' => array('escape' => false, 'attributes' => array('class' => 'help-block')),
                            ));
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $this->Form->button('<i class="icon-save"></i> ', array(
                                'name' => 'addApproval',
                                'type' => 'button',
                                'class' => 'btn btn-primary add-approval tiptip',
                                'data-original-title' => "Add a file",
                                'div' => false,
                            ));
                            ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div>
         
        </div>
    </div>
<?php
} ?>
