<!-- start view -->

<h3 style="text-align: center;"> Site Inspection(s)</h3>
<hr class="soften" style="margin: 10px 0px;">

<?php
if (($this->Session->read('Auth.User.id') == $site_inspection['user_id'] or $this->Session->read('Auth.User.group_id') == '2') and $site_inspection['approved'] < 1) {

  echo $this->Form->create('SiteInspection', array(
    'url' => array('controller' => 'site_inspections', 'action' => 'edit', $site_inspection['id'], $site_inspection['application_id']),
    'type' => 'file',
    'class' => 'form-inline',
    'inputDefaults' => array(
      // 'div' => array('class' => 'control-group'),
      'label' => array('class' => 'control-label'),
      // 'between' => '<div class="controls">',
      // 'after' => '</div>',
      'class' => '',
      'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
      'error' => array('attributes' => array('class' => 'controls help-block')),
    ),
  ));
  echo $this->Form->input('SiteInspection.' . $akey . '.id', array('value' => $site_inspection['id'], 'type' => 'hidden'));
?>

  <h3>General Information</h3>
  <div class="row-fluid">
    <div class="span10">

      <table class="table table-bordered">
        <tbody>
          <tr>
            <th class="my-well">Study Title</th>
            <td><?php echo $site_inspection['study_title']; ?></td>
          </tr>
          <tr>
            <th class="my-well">Protocol number and version</th>
            <td><?php echo $site_inspection['protocol_no']; ?><br /><?php echo $site_inspection['version_no']; ?></td>
          </tr>
          <tr>
            <th class="my-well">PACTR Registration number</th>
            <td><?php echo $this->Form->input('SiteInspection.' . $akey . '.pactr_number', array('label' => false)); ?></td>
          </tr>
          <tr>
            <th class="my-well">Phase of trial</th>
            <td><?php echo $this->Form->input('SiteInspection.' . $akey . '.trial_phase', array('label' => false)); ?></td>
          </tr>
          <tr>
            <th class="my-well">Investigator(s)</th>
            <td><?php echo $this->Form->input('SiteInspection.' . $akey . '.investigators', array('label' => false, 'rows' => 2)); ?></td>
          </tr>
          <tr>
            <th class="my-well">Co-investigator(s)</th>
            <td><?php echo $this->Form->input('SiteInspection.' . $akey . '.co_investigators', array('label' => false, 'rows' => 2)); ?></td>
          </tr>
          <tr>
            <th class="my-well">Stage of study inspected:</th>
            <td>
              <?php
              echo $this->Form->input(
                'SiteInspection.' . $akey . '.study_stage',
                array(
                  'type' => 'select', 'empty' => false,
                  'options' =>  array(
                    'Before trial commencement' => 'Before trial commencement', 'During clinical conduct' => 'During clinical conduct',
                    'After completion of trial' => 'After completion of trial'
                  ),
                  'label' => false
                )
              );
              ?>
            </td>
          </tr>
          <tr>
            <th class="my-well">Country where the study is inspected</th>
            <td><?php echo $this->Form->input('SiteInspection.' . $akey . '.inspection_country', array('label' => false, 'rows' => 2)); ?></td>
          </tr>
          <tr>
            <th class="my-well">Names of Inspectors, and countries represented</th>
            <td><?php echo $this->Form->input('SiteInspection.' . $akey . '.inspector_names', array('label' => false, 'rows' => 2)); ?></td>
          </tr>
          <tr>
            <th class="my-well">Date(s) of Inspection</th>
            <td><?php echo $this->Form->input('SiteInspection.' . $akey . '.inspection_dates', array('label' => false, 'rows' => 2)); ?></td>
          </tr>
          <tr>
            <th class="my-well">Name and address of the clinical site</th>
            <td><?php echo $this->Form->input('SiteInspection.' . $akey . '.site_address', array('label' => false, 'rows' => 2)); ?></td>
          </tr>
          <tr>
            <th class="my-well">Name and address of the sponsor site</th>
            <td><?php echo $this->Form->input('SiteInspection.' . $akey . '.sponsor_address', array('label' => false, 'rows' => 2)); ?></td>
          </tr>
          <tr>
            <th class="my-well">Name and address of laboratories (clinical, bio-analytical)</th>
            <td><?php echo $this->Form->input('SiteInspection.' . $akey . '.lab_address', array('label' => false, 'rows' => 2)); ?></td>
          </tr>
        </tbody>
      </table>
      <h3>Checks and comments</h3>
      <table class="table table-bordered table-condensed">
        <thead>
          <th></th>
          <th></th>
          <th width="22%"></th>
          <th></th>
        </thead>
        <tbody>
          <?php
          // foreach ($site_inspection['SiteAnswer'] as $site_answer) { 
          for ($i = 0; $i <= count($site_inspection['SiteAnswer']) - 1; $i++) {
            echo $this->Form->input('SiteInspection.' . $akey . '.SiteAnswer.' . $i . '.id', array('type' => 'hidden', 'value' => $site_inspection['SiteAnswer'][$i]['id']));
            echo $this->Form->input('SiteInspection.' . $akey . '.SiteAnswer.' . $i . '.site_inspection_id', array('type' => 'hidden', 'value' => $site_inspection['id']));
            if ($site_inspection['SiteAnswer'][$i]['question_type'] == 'section') {
              echo "<tr class='success'><td colspan='4'><strong>" . $site_inspection['SiteAnswer'][$i]['question'] . "</strong></td></tr>";
            } elseif ($site_inspection['SiteAnswer'][$i]['question_type'] == 'comment') {
              echo "<tr><td colspan='2'>";
              echo $this->Form->input('SiteInspection.' . $akey . '.SiteAnswer.' . $i . '.comment', array('label' => false, 'rows' => 1));
              echo '</td>';
              echo "<td colspan='2'>";
              echo $this->Form->input(
                'SiteInspection.' . $akey . '.SiteAnswer.' . $i . '.finding',
                array(
                  'type' => 'select', 'empty' => true,
                  'options' =>  array('Minor' => 'Minor', 'Major' => 'Major', 'Critical' => 'Critical', 'General observation' => 'General observation'),
                  'label' => false
                )
              );
              echo "</td></tr>";
            } elseif ($site_inspection['SiteAnswer'][$i]['question_type'] == 'question') {
              echo "<tr><td>" . $site_inspection['SiteAnswer'][$i]['question_number'] . "</td>";
              echo "<td>" . $site_inspection['SiteAnswer'][$i]['question'] . "</td>";
              echo "<td>";
              echo $this->Form->input('SiteInspection.' . $akey . '.SiteAnswer.' . $i . '.answer', array(
                'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
                'class' => 'answer' . $i . $site_inspection['SiteAnswer'][$i]['question_number'],
                'before' => '
                        <input type="hidden" value="" id="SiteInspection' . $akey . $i . 'SiteAnswer_" name="data[SiteInspection][' . $akey . '][SiteAnswer][' . $i . '][answer]"> <label class="radio inline">',
                'after' => '</label>',
                'options' => array('Yes' => 'Yes'),
              ));
              echo $this->Form->input('SiteInspection.' . $akey . '.SiteAnswer.' . $i . '.answer', array(
                'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false, 'error' => false,
                'class' => 'answer' . $i . $site_inspection['SiteAnswer'][$i]['question_number'],
                'before' => '<label class="radio inline">', 'after' => '</label>',
                'options' => array('No' => 'No')
              ));
              echo $this->Form->input('SiteInspection.' . $akey . '.SiteAnswer.' . $i . '.answer', array(
                'type' => 'radio',  'label' => false, 'legend' => false, 'div' => false, 'hiddenField' => false,
                'class' => 'answer' . $i . $site_inspection['SiteAnswer'][$i]['question_number'],
                'format' => array('before', 'label', 'between', 'input', 'after', 'error'),
                'error' => array('attributes' => array('wrap' => 'p', 'class' => 'controls required error')),
                'before' => '<label class="radio inline">',
                'after' => '</label>
                            <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection"
                            onclick="$(\'.answer' . $i . $site_inspection['SiteAnswer'][$i]['question_number'] . '\').removeAttr(\'checked disabled\')">
                            <em class="accordion-toggle"><i class="icon-remove-circle"></i> </em></a> </span>
                           ',
                'options' => array('N/A' => 'N/A'),
              ));
              echo "</td>";
              echo "<td>";
              echo $this->Form->input('SiteInspection.' . $akey . '.SiteAnswer.' . $i . '.comment', array('label' => false, 'rows' => 1));
              echo '</td></tr>';
            } elseif ($site_inspection['SiteAnswer'][$i]['question_type'] == 'general_comment') {
              echo "<tr><td colspan='4'>";
              echo "<h5>Any other general comment or remark: </h5>";
              echo $this->Form->input('SiteInspection.' . $akey . '.SiteAnswer.' . $i . '.comment', array('label' => false, 'rows' => 1));
              echo '</td></tr>';
            }
          }

          echo "<tr><td colspan='4'>";
          echo $this->Form->input('SiteInspection.' . $akey . '.events_summary', array('label' => false, 'rows' => 2));
          echo  "</td></tr>";
          ?>
        </tbody>
      </table>
      <div class="form-actions">
        <?php
        echo $this->Form->button('<i class="icon-save"></i> Save Changes', array(
          'name' => 'saveChanges',
          'value' => 'saveChanges',
          'class' => 'btn btn-success mapop',
          'id' => 'SiteInspectionSaveChanges', 'title' => 'Save & continue editing',
          'data-content' => 'Save changes to form without submitting it.
                                          The form will still be available for further editing.',
          'div' => false,
        ));
        echo "&nbsp;";
        echo $this->Form->button('<i class="icon-rocket"></i> Submit', array(
          'name' => 'submitReport',
          'value' => 'submitReport',
          'onclick' => "return confirm('Are you sure you wish to submit the inspection report? It will be available for peer review.');",
          'class' => 'btn btn-primary mapop',
          'id' => 'SiteInspectionSubmitReport', 'title' => 'Save and Submit Report',
          'data-content' => 'Submit report for peer review and approval.',
          'div' => false,
        ));
        echo $this->Form->end();
        ?>
      </div>
    </div>
  </div>

<?php
  
}
if ($this->Session->read('Auth.User.id') !== $site_inspection['user_id'] or $site_inspection['approved'] >= 1) {
?>


  <h3>General Information</h3>
  <table class="table table-bordered">
    <tbody>
      <tr>
        <th class="my-well">Study Title</th>
        <td><?php echo $site_inspection['study_title']; ?></td>
      </tr>
      <tr>
        <th class="my-well">Protocol number and version</th>
        <td><?php echo $site_inspection['protocol_no']; ?><br /><?php echo $site_inspection['version_no']; ?></td>
      </tr>
      <tr>
        <th class="my-well">PACTR Registration number</th>
        <td><?php echo $site_inspection['pactr_number']; ?></td>
      </tr>
      <tr>
        <th class="my-well">Phase of trial</th>
        <td><?php echo $site_inspection['trial_phase']; ?></td>
      </tr>
      <tr>
        <th class="my-well">Investigator(s)</th>
        <td><?php echo $site_inspection['investigators']; ?></td>
      </tr>
      <tr>
        <th class="my-well">Co-investigator(s)</th>
        <td><?php echo $site_inspection['co_investigators']; ?></td>
      </tr>
      <tr>
        <th class="my-well">Stage of study inspected:</th>
        <td>
          <?php
          echo $site_inspection['study_stage'];
          ?>
        </td>
      </tr>
      <tr>
        <th class="my-well">Country where the study is inspected</th>
        <td><?php echo $site_inspection['inspection_country']; ?></td>
      </tr>
      <tr>
        <th class="my-well">Names of Inspectors, and countries represented</th>
        <td><?php echo $site_inspection['inspector_names']; ?></td>
      </tr>
      <tr>
        <th class="my-well">Date of Inspection</th>
        <td><?php echo $site_inspection['inspection_dates']; ?></td>
      </tr>
      <tr>
        <th class="my-well">Name and address of the clinical site</th>
        <td><?php echo $site_inspection['site_address']; ?></td>
      </tr>
      <tr>
        <th class="my-well">Name and address of laboratories (clinical, bio-analytical)</th>
        <td><?php echo $site_inspection['lab_address']; ?></td>
      </tr>
    </tbody>
  </table>
  <h3>Checks and comments</h3>
  <table class="table table-bordered table-condensed">
    <thead>
      <th></th>
      <th></th>
      <th width="22%"></th>
      <th></th>
    </thead>
    <tbody>
      <?php
      foreach ($site_inspection['SiteAnswer'] as $site_answer) {
        if ($site_answer['question_type'] == 'section') {
          echo "<tr class='success'><td colspan='4'><strong>" . $site_answer['question'] . "</strong></td></tr>";
        } elseif ($site_answer['question_type'] == 'comment') {
          echo "<tr><td colspan='2'>";
          echo $site_answer['comment'];
          echo '</td>';
          echo "<td colspan='2'>";
          echo $site_answer['finding'];
          echo "</td></tr>";
        } elseif ($site_answer['question_type'] == 'question') {
          echo "<tr><td>" . $site_answer['question_number'] . "</td>";
          echo "<td>" . $site_answer['question'] . "</td>";
          echo "<td>";
          echo $site_answer['answer'];
          echo "</td>";
          echo "<td>";
          echo $site_answer['comment'];
          echo '</td></tr>';
        } elseif ($site_answer['question_type'] == 'general_comment') {
          echo "<tr><td colspan='4'>";
          echo "<h5>Any other general comment or remark: </h5>";
          echo $site_answer['comment'];
          echo '</td></tr>';
        }
      }

      echo "<tr><td colspan='4'>";
      echo "<h5>Summary of events</h5>";
      echo $site_inspection['events_summary'];
      echo  "</td></tr>";
      ?>
    </tbody>
  </table>


<?php } ?>
