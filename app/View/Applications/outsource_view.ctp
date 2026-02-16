<?php
$this->extend('/Elements/application/applicant_view');
$ichecked = "&#x2611;";
$nchecked = "&#x2610;";
?>

<!-- START AMENDMENT LEAD -->
<?php $this->start('amendment-lead'); ?>
<?php
$this->assign('MyApplications', 'active');
$this->Html->script('ckeditor/ckeditor', array('inline' => false));
$this->Html->script('ckeditor/adapters/jquery', array('inline' => false));
$this->Html->script('jUpload/vendor/jquery.ui.widget.js', array('inline' => false));
$this->Html->script('jUpload/jquery.iframe-transport.js', array('inline' => false));
$this->Html->script('jUpload/jquery.fileupload.js', array('inline' => false));
$this->Html->script('jquery.blockUI.js', array('inline' => false));
$this->Html->script('bootstrap-editable', array('inline' => false));
$this->Html->css('bootstrap-editable', null, array('inline' => false));
//Only meant for applicant
$this->Html->script('multi/amendments-checklist', array('inline' => false));
$this->Html->script('multi/approvalyear', array('inline' => false));
$this->Html->script('multi/documents', array('inline' => false));
$this->Html->script('multi/afro_attachments', array('inline' => false));

$reviewers_comments = 0;
foreach ($application['Review'] as $review) {
  if ($review['type'] == 'ppb_comment') {
    $reviewers_comments++;
  }
}
// debug(Hash::check($application['Review'], '{n}[type=ppb_comment].id'));
$this->assign('is-applicant', 'true');
?>

<?php
echo $this->Session->flash();
?>

<div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#application" data-toggle="tab">Application</a></li>
    <?php if ($application['Application']['user_id'] == $this->Session->read('Auth.User.id')) { ?>
      <li><a href="#outsourcing" data-toggle="tab">Outsourcing</a></li>
      <li><a href="#tab17" data-toggle="tab">Screening</a></li>
      <li><a href="#amendments" data-toggle="tab">Amendments</a></li>
      <li><a href="#tab2" data-toggle="tab">Reviewers&rsquo; Comments <small>(<?php echo $reviewers_comments; ?>)</small></a></li>
      <li><a href="#tab6" data-toggle="tab">Site Inspections (<?php echo count($application['SiteInspection']) ?>)</a></li>
      <?php }
    // if ($application['Outsource']['model_sae'] == '1') { 
    $currentUserId = $this->Session->read('Auth.User.id');
    $result = Hash::extract($application, "Outsource.{n}[user_id=$currentUserId]");

    if (!empty($result)) {
      if ($result[0]['model_sae'] == '1') {
      ?>
        <li><a href="#tab7" data-toggle="tab">SAE/SUSAR (<?php echo count($application['Sae']) ?>)</a></li>
      <?php }
      if ($result[0]['model_ciom'] == '1') {
      ?>
        <li><a href="#tab15" data-toggle="tab">CIOMS E2B (<?php echo count($application['Ciom']) ?>)</a></li>
      <?php }
      if ($result[0]['model_dev'] == '1') { ?>
        <li><a href="#tab13" data-toggle="tab">Protocol Deviations (<?php echo count($application['Deviation']) ?>)</a></li>
    <?php }
    } ?>
    <?php if ($application['Application']['user_id'] == $this->Session->read('Auth.User.id')) { ?>
      <li><a href="#outsourcing" data-toggle="tab">Outsourcing</a></li>
      <li><a href="#tab8" data-toggle="tab" style="color: #52A652;">Annual Approval Checklist</a></li>
      <li><a href="#tab10" data-toggle="tab" style="color: #52A652;">Annual Participants Flow</a></li>
      <li><a href="#tab14" data-toggle="tab" style="color: #52A652;">Manufacturing Site(s)</a></li>
      <!-- <li><a href="#tab11" data-toggle="tab" style="color: #52A652;">Study Budget</a></li> -->
      <li><a href="#tab12" data-toggle="tab" style="color: #5e3ed3;">Approval Letters</a></li>
      <!-- <li><a href="#tab9" data-toggle="tab" style="color: #52A652;">Final Study Report</a></li> -->
      <?php if ($application['Application']['approved'] == 2) { ?>
        <li><a href="#tab9" data-toggle="tab" style="color: #15189d;">Final Study Report</a></li>
    <?php }
    } ?>
  </ul>
  <div class="tab-content my-tab-content">
    <div class="tab-pane" id="tab1">
      <!-- content for tab1 comes here -->

      <div class="row-fluid">
        <?php if ($application['Application']['submitted'] == 1) { ?>
          <h4 class="text-success">
            Submitted Application : (<?php echo $application['Application']['protocol_no']; ?>) &mdash;
            <small> Created on:
              <?php
              echo date('d-m-Y h:i:s a', strtotime($application['Application']['created']));
              ?>
            </small>
          </h4>
        <?php } else { ?>
          <h4 class="text-success">
            UnSubmitted Application : &mdash; <small> Created on:
              <?php
              echo date('d-m-Y h:i:s a', strtotime($application['Application']['created']));
              ?>
            </small>
          </h4>
        <?php } ?>

      </div>
      <?php $this->end(); ?>
      <!-- ######## -->
      <!-- START RIGHT BAR -->
      <?php $this->start('view-rightbar'); ?>
    </div>

    <?php $this->end();  ?>
    <!-- ######## -->
    <!-- FORM HEADER -->
    <?php $this->start('form-header'); ?>
    <div class="span12">
      <?php
      echo $this->Form->create('Application', array(
        'type' => 'file',
        'class' => 'form-horizontal',
        'id' => 'fakeidshouldnotexist',
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
      echo $this->Form->input('id', array('value' => $application['Application']['id']));
      ?>
      <?php $this->end(); ?>

      <?php
      $this->start('form-actions');
      if ($application['Application']['submitted'] == 1) {
      ?>
        <div class="form-actions" style="margin-top: 0px; padding-left: 10px;">
          <?php



          ?>
        </div>
      <?php
      }
      $this->end();
      ?>

      <?php $this->start('tabs'); ?>
      <ul>
        <li><a href="#tabs-1">1. Abstract</a></li>
        <li><a href="#tabs-2">2. Investigator &amp; Pharmacist</a></li>
        <li><a href="#tabs-3">3. Sponsor</a></li>
        <li><a href="#tabs-4">4. Participants</a></li>
        <li><a href="#tabs-5">5. Sites</a></li>
        <li><a href="#tabs-6">6. Placebo</a></li>
        <li><a href="#tabs-7">7. Criteria</a></li>
        <li><a href="#tabs-8">8. Scope</a></li>
        <li><a href="#tabs-9">9. Design</a></li>
        <li><a href="#tabs-15">10. Study Budget</a></li>
        <li><a href="#tabs-10">11. Organizations</a></li>
        <li><a href="#tabs-11">12. Other details</a></li>
        <li><a href="#tabs-12">13. Checklist </a></li>
        <li><a href="#tabs-13">14. Declaration</a></li>
        <li><a href="#tabs-14">15. Notifications</a></li>
      </ul>
      <?php $this->end(); ?>


      <?php $this->start('endjs'); ?>
    </div> <!-- End or bootstrab tab1 -->

    <div class="tab-pane" id="tab17">
      <div class="marketing">
        <div class="row-fluid">
          <div class="span12">
            <h3 class="text-info">Screening for completeness</h3>
          </div>
        </div>
        <hr class="soften" style="margin: 10px 0px;">
      </div>
      <div class="row-fluid">
        <div class="span12">
          <br>
          <div class="amend-form">
            <h5 class="text-center"><u>FEEDBACK/QUERIES</u></h5>
            <div class="row-fluid">
              <div class="span8">
                <?php
                // debug(min(Hash::extract($application, 'ApplicationStage.{n}[stage=Screening]')));
                // $eid = min(Hash::extract($application, 'ApplicationStage.{n}[stage=Screening]'));
                $var = Hash::extract($application, 'ApplicationStage.{n}[stage=Screening]');
                $eid = null;
                if (!empty($var)) $eid = min($var);
                // debug($eid);
                if (!empty($eid)) echo $this->element('comments/list', ['comments' => $eid['Comment'], 'show' => true]);
                ?>
              </div>
              <div class="span4 lefty">
                <?php
                if (!empty($eid) && !empty($eid['Comment']))  echo $this->element('comments/add', [
                  'model' => [
                    'model_id' => $application['Application']['id'], 'foreign_key' => $eid['id'],
                    'model' => 'ApplicationStage', 'category' => 'external', 'url' => 'add_screening_query'
                  ]
                ])
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="tab-pane" id="outsourcing">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('application/outsourcing'); ?>
        </div>
      </div>
    </div>

    <div class="tab-pane active" id="application">
      <div class="row-fluid">
        <div class="span12">

          <!-- Only show Abstract -->


          <hr class="my-view" style="clear: left;">
          <table class="table table-condensed">
            <thead>
              <tr>
                <th class="table-label required">
                  <h5 style="text-align: center;">Study Title: <span class="sterix">*</span></h5>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo $application['Application']['study_title'] ?></td>
              </tr>
              <?php
              foreach ($application['Amendment'] as $key => $amendment) {
                if ($amendment['submitted'] == 1 && !empty($amendment['study_title'])) {      ?>
                  <tr class="table-viewlabel">
                    <td class="table-viewlabel"><?php echo $key + 1; ?></td>
                  </tr>
                  <tr class="table-viewlabel">
                    <td class="table-noline"> <?php echo $amendment['study_title'];  ?></td>
                  </tr>
              <?php   }
              } ?>
              <?php echo $this->fetch('study_title'); ?>
            </tbody>
          </table>
          <table class="table  table-condensed">
            <tbody>
              <tr>
                <td class="table-label required">
                  <p>Short Title: <span class="sterix">*</span></p>
                </td>
                <td>
                  <?php
                  if (!empty($application['Application']['short_title'])) {
                    echo $application['Application']['short_title'];
                  } else { ?>
                    <input name="data[Application][short_title]" class="input-xxlarge" placeholder=" " maxlength="30" id="ApplicationShortTitle" type="text">
                    <button name="submitStudyTitle" class="btn btn-info" id="ApplicationViewSaveShortTitle" type="button">
                      <i class="icon-save"></i> Save
                    </button>
                  <?php }  ?>
                </td>
              </tr>
            </tbody>
          </table>
          <hr class="my-view">

          <table class="table table-condensed">
            <thead>
              <tr>
                <th class="table-label required">
                  <h5 style="text-align: center;">Laymans Summary: <span class="sterix">*</span></h5>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo $application['Application']['laymans_summary'] ?></td>
              </tr>
              <?php
              foreach ($application['Amendment'] as $key => $amendment) {
                if ($amendment['submitted'] == 1 && !empty($amendment['laymans_summary'])) {      ?>
                  <tr class="table-viewlabel">
                    <td class="table-viewlabel"><?php echo $key + 1; ?></td>
                  </tr>
                  <tr class="table-viewlabel">
                    <td class="table-noline"> <?php echo $amendment['laymans_summary'];  ?></td>
                  </tr>
              <?php   }
              } ?>
              <?php echo $this->fetch('laymans_summary'); ?>
            </tbody>
          </table>

          <table class="table table-condensed">
            <thead>
              <tr>
                <th class="table-label required">
                  <h5 style="text-align: center;">Abstract of the study: <span class="sterix">*</span></h5>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo $application['Application']['abstract_of_study'] ?></td>
              </tr>
              <?php
              foreach ($application['Amendment']  as $key => $amendment) {
                if ($amendment['submitted'] == 1  && !empty($amendment['abstract_of_study'])) {      ?>
                  <tr class="table-viewlabel">
                    <td class="table-viewlabel"><?php echo $key + 1; ?></td>
                  </tr>
                  <tr class="table-viewlabel table-in">
                    <td class="table-noline">
                      <?php
                      if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'pdf') {
                        echo ($amendment['abstract_of_study']);
                      } else {   ?>
                        <iframe width="100%" height="250" srcdoc='<?php echo ($amendment["abstract_of_study"]);  ?>'> </iframe>
                      <?php  } ?>
                    </td>
                  </tr>
              <?php   }
              } ?>
              <?php echo $this->fetch('abstract_of_study'); ?>
            </tbody>
          </table>

          <table class="table  table-condensed">
            <tbody>
              <tr>
                <td class="table-label required">
                  <p>Version No: <span class="sterix">*</span></p>
                </td>
                <td><?php echo $application['Application']['version_no'] ?></td>
              </tr>
              <?php
              foreach ($application['Amendment'] as $key => $amendment) {
                if ($amendment['submitted'] == 1 && !empty($amendment['version_no'])) {      ?>
                  <tr class="table-viewlabel">
                    <td class="table-viewlabel"><?php echo $key + 1; ?></td>
                    <td class=" table-noline"><?php echo $amendment['version_no'];  ?></td>
                  </tr>
              <?php   }
              } ?>
              <?php echo $this->fetch('version_no'); ?>
              <tr>
                <td class="table-label required">
                  <p>Date of Protocol <span class="sterix">*</span></p>
                </td>
                <td><?php echo $application['Application']['date_of_protocol'] ?></td>
              </tr>
              <?php
              foreach ($application['Amendment']  as $key => $amendment) {
                if ($amendment['submitted'] == 1 && !empty($amendment['date_of_protocol'])) {      ?>
                  <tr class="table-viewlabel">
                    <td class="table-viewlabel"><?php echo $key + 1; ?></td>
                    <td class=" table-noline"><?php echo $amendment['date_of_protocol'];  ?></td>
                  </tr>
              <?php   }
              } ?>
              <?php echo $this->fetch('date_of_protocol'); ?>
              <tr>
                <td class="table-label required">
                  <p>Study Drug <span class="sterix">*</span></p>
                </td>
                <td><?php echo $application['Application']['study_drug'] ?></td>
              </tr>
              <?php
              foreach ($application['Amendment']  as $key => $amendment) {
                if ($amendment['submitted'] == 1 && !empty($amendment['study_drug'])) {      ?>
                  <tr class="table-viewlabel">
                    <td class="table-viewlabel"><?php echo $key + 1; ?></td>
                    <td class=" table-noline"><?php echo $amendment['study_drug'];  ?></td>
                  </tr>
              <?php   }
              } ?>
              <?php echo $this->fetch('study_drug'); ?>


              <tr>
                <td class="table-label required">
                  <p>Route(s) of Administration <span class="sterix">*</span></p>
                </td>
                <td><?php
                    foreach ($application['StudyRoute'] as $route) {
                      echo "<p>" . $route['study_route'] . "</p>";
                    }
                    ?></td>
              </tr>
            </tbody>
          </table>

          <table class="table  table-condensed">
            <tbody>
              <tr>
                <td class="table-label required">
                  <p>Disease condition being investigated <span class="sterix">*</span></p>
                </td>
                <td><?php echo $application['Application']['disease_condition'] ?></td>
              </tr>
              <?php
              foreach ($application['Amendment'] as $key => $amendment) {
                if ($amendment['submitted'] == 1 && !empty($amendment['disease_condition'])) {      ?>
                  <tr class="table-viewlabel">
                    <td class="table-viewlabel"><?php echo $key + 1; ?></td>
                    <td class=" table-noline"><?php echo $amendment['disease_condition'];  ?></td>
                  </tr>
              <?php   }
              } ?>
              <?php echo $this->fetch('disease_condition'); ?>
              <tr>
                <td class="table-label required">
                  <p>Product Type <span class="sterix">*</span></p>
                </td>
                <td>
                  <p class="control-nolabel"><?php echo ($application['Application']['product_type_biologicals']   ? $ichecked : $nchecked); ?> Biologicals
                  </p>

                  <p class="control-nolabel">
                    <?php echo ($application['Application']['product_type_proteins']   ? $ichecked : $nchecked); ?> Proteins
                    <?php echo ($application['Application']['product_type_immunologicals']   ? $ichecked : $nchecked); ?> Immunologicals
                    <?php echo ($application['Application']['product_type_vaccines']   ? $ichecked : $nchecked); ?> Vaccines
                    <?php echo ($application['Application']['product_type_hormones']   ? $ichecked : $nchecked); ?> Hormones
                    <?php echo ($application['Application']['product_type_toxoid']   ? $ichecked : $nchecked); ?> Toxoid
                  </p>
                </td>
              </tr>
              <tr>
                <td class=" table-noline"></td>
                <td class=" table-noline">
                  <p class="control-nolabel"><?php echo ($application['Application']['product_type_chemical']   ? $ichecked : $nchecked); ?> Chemical</p>
                  <p><?php echo $application['Application']['product_type_chemical_name']; ?></p>
                </td>
              </tr>
              <tr>
                <td class=" table-noline"></td>
                <td class=" table-noline">
                  <p class="control-nolabel"><?php echo ($application['Application']['product_type_medical_device']   ? $ichecked : $nchecked); ?> Medical Device</p>
                  <p><?php echo $application['Application']['product_type_medical_device_name']; ?></p>
                </td>
              </tr>
              <?php
              foreach ($application['Amendment'] as $key => $amendment) {
                if ($amendment['submitted'] == 1 && !empty($amendment['product_type'])) {      ?>
                  <tr class="table-viewlabel">
                    <td class="table-viewlabel"><?php echo $key + 1; ?> </td>
                    <td class=" table-noline"><?php echo $amendment['product_type']; ?> </td>
                  </tr>
              <?php   }
              } ?>
              <?php echo $this->fetch('product_type'); ?>
            </tbody>
          </table>

          <p class="table-label required">MANUFACTURER(S)</p>
          <?php foreach ($application['Manufacturer'] as $key => $manufacturer) { ?>
            <span class="badge badge-info"><?php echo $key + 1; ?></span>
            <table class="table  table-condensed">
              <tbody>
                <tr>
                  <td class="table-label">
                    <p>Name of manufacturer</p>
                  </td>
                  <td><?php echo "<p>" . $manufacturer['manufacturer_name'] . "</p>";  ?></td>
                </tr>
                <tr>
                  <td class="table-label">
                    <p>Manufacturing site address</p>
                  </td>
                  <td><?php echo "<p>" . $manufacturer['address'] . "</p>";  ?></td>
                </tr>
                <tr>
                  <td class="table-label">
                    <p>Phone</p>
                  </td>
                  <td><?php echo "<p>" . $manufacturer['manufacturer_phone'] . "</p>";  ?></td>
                </tr>
                <tr>
                  <td class="table-label">
                    <p>Email</p>
                  </td>
                  <td><?php echo "<p>" . $manufacturer['manufacturer_email'] . "</p>";  ?></td>
                </tr>
                <tr>
                  <td class="table-label">
                    <p>Manufacturing activities at site</p>
                  </td>
                  <td><?php echo "<p>" . $manufacturer['manufacturing_activities'] . "</p>";  ?></td>
                </tr>
                <tr>
                  <td class="table-label">
                    <p>If others, specify</p>
                  </td>
                  <td><?php echo "<p>" . $manufacturer['other_specify'] . "</p>";  ?></td>
                </tr>
                <tr>
                  <td class="table-label">
                    <p>Country of manufacture</p>
                  </td>
                  <td><?php echo "<p>" . $manufacturer['manufacturer_country'] . "</p>";  ?></td>
                </tr>
              </tbody>
            </table>
          <?php } ?>

          <table class="table  table-condensed">
            <tbody>
              <tr>
                <td class="table-label required">
                  <p>Is there a comparator drug/medical device? <span class="sterix">*</span></p>
                </td>
                <td><?php echo $application['Application']['comparator'] ?></td>
              </tr>
              <tr>
                <td class="table-label">
                  <p>If yes, give the name</p>
                </td>
                <td><?php echo $application['Application']['comparator_name'] ?></td>
              </tr>
              <tr>
                <td class="table-label">
                  <p>If yes, is the comparator currently registered?</p>
                </td>
                <td><?php echo $application['Application']['comparator_registered'] ?></td>
              </tr>
              <tr>
                <td class="table-label">
                  <p>List of the countries where the comparator is registered</p>
                </td>
                <td><?php echo $application['Application']['comparator_countries'] ?></td>
              </tr>
            </tbody>
          </table>

          <p class="table-label required">KENYA ETHICS REVIEW COMMITTEE(S)</p>
          <?php foreach ($application['EthicalCommittee'] as $key => $date) { ?>
            <span class="badge badge-info"><?php echo $key + 1; ?></span>
            <table class="table  table-condensed">
              <tbody>
                <tr>
                  <td class="table-label">
                    <p>Name of Ethics Review Committee (ERC)</p>
                  </td>
                  <td><?php echo "<p>" . $date['ethical_committee'] . "</p>";  ?></td>
                </tr>
                <tr>
                  <td class="table-label">
                    <p>Date of initial complete submission to ERC</p>
                  </td>
                  <td><?php echo "<p>" . $date['submission_date'] . "</p>";  ?></td>
                </tr>
                <tr>
                  <td class="table-label">
                    <p>ERC Reference Number</p>
                  </td>
                  <td><?php echo "<p>" . $date['erc_number'] . "</p>";  ?></td>
                </tr>
                <tr>
                  <td class="table-label">
                    <p>Initial approval date by ERC</p>
                  </td>
                  <td><?php echo "<p>" . $date['initial_approval'] . "</p>";  ?></td>
                </tr>
              </tbody>
            </table>
          <?php } ?>

          <table>
            <tbody>
              <?php
              foreach ($application['Amendment']  as $key => $amendment) {
                if ($amendment['submitted'] == 1 && !empty($amendment['ethical_committees'])) {      ?>
                  <tr class="table-viewlabel">
                    <td class="table-viewlabel"><?php echo $key + 1; ?></td>
                    <td class=" table-noline"><?php echo $amendment['ethical_committees']; ?></td>
                  </tr>
              <?php   }
              } ?>
              <?php echo $this->fetch('ethical_committees'); ?>

              <?php /*
              foreach($application['Amendment']  as $key => $amendment) {
                if($amendment['submitted'] == 1 && !empty($amendment['approval_date'])){      ?>
                  <tr class="table-viewlabel">
                      <td class="table-viewlabel"><?php echo $key+1; ?></td>
                      <td class=" table-noline"><?php  echo $amendment['approval_date'];  ?></td>
                   </tr>
             <?php   } } ?>
              <?php echo $this->fetch('approval_date'); */ ?>
            </tbody>
          </table>

          <!-- End of Abstract -->

        </div>
      </div>
    </div>
    <div class="tab-pane" id="tab2">
      <div class="marketing">
        <div class="row-fluid">
          <div class="span12">
            <h2 class="text-info">The Expert Committee on Clinical Trials</h2>
            <h3 class="text-info" style="text-decoration: underline;">Reviewer's Comments</h3>
          </div>
        </div>
        <hr class="soften" style="margin: 10px 0px;">
      </div>
      <p><strong>1. Protocol Code: </strong><?php echo $application['Application']['protocol_no']; ?></p>
      <p><strong>2. Protocol title: </strong><?php echo $application['Application']['study_title']; ?></p>
      <div class="row-fluid">
        <div class="span12">
          <h4 class="text-success">Reviewer's Comments
            <?php
            echo $this->Html->link(
              __('<i class="icon-download-alt"></i> Download Comments <small>(PDF)</small>'),
              array('controller' => 'applications', 'ext' => 'pdf', 'action' => 'view', $application['Application']['id']),
              array('escape' => false, 'class' => 'btn pull-right', 'style' => 'margin-right: 10px;')
            );
            ?>
          </h4>
          <?php
          $counter = 0;
          foreach ($application['Review'] as $review) {
            $counter++;
            echo "<hr><span class=\"badge badge-success\">" . $counter . "</span> <small class='muted'>created on: " . date('d-m-Y H:i:s', strtotime($review['created'])) . "</small>";
            echo "<div style='padding-left: 29px;' class='morecontent'>" . $review['text'] . "</div>";
            // echo "<br>";
            echo "<div style='padding-left: 29px;' class='morecontent'>" . $review['recommendation'] . "</div>";
          }
          ?>
        </div>
      </div>

      <div class="row-fluid">
        <div class="span12">
          <br>
          <div class="amend-form">
            <h5 class="text-center text-info"><u>FEEDBACK</u></h5>
            <div class="row-fluid">
              <div class="span8">
                <?php
                //Reviews limited to ppb_comment already
                $var = Hash::extract($application, 'Review.{n}[type=ppb_comment]');
                $rid = null;
                if (!empty($var)) $rid = min($var);
                // debug($rid);
                if (!empty($rid)) echo $this->element('comments/list', ['comments' => $rid['ExternalComment'], 'show' => false]);
                ?>
              </div>
              <div class="span4 lefty">
                <?php
                if (!empty($rid))  echo $this->element('comments/add', [
                  'model' => [
                    'model_id' => $application['Application']['id'], 'foreign_key' => $rid['id'],
                    'model' => 'Review', 'category' => 'external', 'url' => 'add_review_response'
                  ]
                ])
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="tab-pane" id="tab6">
      <div class="row-fluid">
        <div class="span12">

          <?php
          echo $this->element('/application/inspection_edit');
          ?>

        </div>
      </div>
    </div>

    <div class="tab-pane" id="tab7">
      <div class="row-fluid">
        <div class="span12">
          <?php
          if ($application['Application']['submitted']) {
            if ($application['Application']['submitted']) {
              echo "<br>";
              echo $this->Html->link(
                '<i class="icon-list-alt"></i> Create SAE',
                array('controller' => 'saes', 'action' => 'add', $application['Application']['id'], 'sae'),
                array('escape' => false, 'class' => 'btn btn-success btn-mini')
              );
              echo "&nbsp;";
              echo $this->Html->link(
                '<i class="icon-credit-card"></i> Create SUSAR',
                array('controller' => 'saes', 'action' => 'add', $application['Application']['id'], 'susar'),
                array('escape' => false, 'class' => 'btn btn-primary btn-mini')
              );
              echo "<br>";
              echo "<br>";
            }
          }
          ?>
          <table class="table  table-bordered table-striped">
            <thead>
              <tr>
                <th>Id</th>
                <th>Reference No.</th>
                <th>Report Type</th>
                <th>Patient Initials</th>
                <th>Created</th>
                <th class="actions"><?php echo __('Actions'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($application['Sae'] as $sae) : ?>
                <tr class="">
                  <td><?php echo h($sae['id']); ?>&nbsp;</td>
                  <td><?php echo h($sae['reference_no']); ?>&nbsp;</td>
                  <td><?php echo h($sae['report_type']);
                      if ($sae['report_type'] == 'Followup') {
                        echo "<br> Initial: ";
                        echo $this->Html->link(
                          '<label class="label label-info">' . substr($sae['reference_no'], 0, strpos($sae['reference_no'], '-')) . '</label>',
                          array('controller' => 'saes', 'action' => 'view', $sae['sae_id']),
                          array('escape' => false)
                        );
                      }
                      ?>&nbsp;
                  </td>
                  <td><?php echo h($sae['patient_initials']); ?>&nbsp;</td>
                  <td><?php echo h($sae['created']); ?>&nbsp;</td>
                  <td class="actions">
                    <?php if ($sae['approved'] > 0) echo $this->Html->link(
                      __('<label class="label label-info">View</label>'),
                      array('controller' => 'saes', 'action' => 'view', $sae['id']),
                      array('target' => '_blank', 'escape' => false)
                    ); ?>
                   
                    <?php
                    if ($sae['approved'] < 1) {
                      if($sae['user_id'] == $this->Session->read('Auth.User.id')){
                        echo $this->Html->link(__('<label class="label label-success">Edit</label>'), array('controller' => 'saes', 'action' => 'edit', $sae['id']), array('target' => '_blank', 'escape' => false));
                      echo $this->Form->postLink(__('<label class="label label-important">Delete</label>'), array('controller' => 'saes', 'action' => 'delete', $sae['id'], 1), array('escape' => false), __('Are you sure you want to delete # %s?', $sae['id']));
                    }}
                    if ($redir === 'outsource' && $sae['approved'] > 0) echo $this->Form->postLink('<i class="icon-facebook"></i> Follow Up', array('controller' => 'saes', 'action' => 'followup', $sae['id']), array('class' => 'btn btn-mini btn-warning', 'escape' => false), __('Create followup for %s?', $sae['reference_no']));
                    ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

        </div>
      </div>
    </div>

    <div class="tab-pane" id="tab15">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('application/ciom'); ?>
        </div>
      </div>
    </div>

    <div class="tab-pane" id="tab13">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('application/deviation'); ?>
        </div>
      </div>
    </div>

    <div class="tab-pane" id="tab8">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('multi/approval'); ?>
        </div>
      </div>
    </div>


    <div class="tab-pane" id="amendments">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('multi/amendments'); ?>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="tab9">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('multi/final'); ?>
        </div>
      </div>
    </div>

    <div class="tab-pane" id="tab10">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('multi/approval_participants'); ?>
        </div>
      </div>
    </div>

    <div class="tab-pane" id="tab14">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('multi/annual_manufacturers'); ?>
        </div>
      </div>
    </div>

    <div class="tab-pane" id="tab11">
      <?php echo $this->element('multi/approval_budget'); ?>
    </div>

    <div class="tab-pane" id="tab12">
      <div class="row-fluid">
        <div class="span12">
          <?php echo $this->element('multi/annual_letters'); ?>
        </div>
      </div>
    </div>

  </div>
</div>

<script text="type/javascript">
  $.expander.defaults.slicePoint = 170;
  $(function() {
    $(document).ajaxStop($.unblockUI);
    $("#tabs").tabs({
      cookie: {
        expires: 1
      }
    });
    $(".morecontent").expander();

    // var editor = $('#ApplicationFinalReport').ckeditor();
    if ($('#ApplicationFinalReport').length) {
      var editor = CKEDITOR.editor.replace('ApplicationFinalReport');
      var editor = CKEDITOR.editor.replace('ApplicationImplicationResults');
      var editor = CKEDITOR.editor.replace('ApplicationLaymansSummary');
    }
    $(document).on('click', '#ApplicationViewSaveReport', function() {
      var data_save = $('#ApplicationId').serializeArray();
      //var value = $('#ApplicationFinalReport').val();
      data_save.push({
        name: $('#ApplicationFinalReport').attr('name'),
        value: editor.getData()
      });

      $.ajax({
        url: $('form').attr('action'),
        type: $('form').attr('method'),
        dataType: 'json',
        data: data_save,
        beforeSend: function() {
          $.blockUI({
            css: {
              border: 'none',
              padding: '15px',
              backgroundColor: '#000',
              '-webkit-border-radius': '10px',
              '-moz-border-radius': '10px',
              opacity: .5,
              color: '#fff'
            },
            message: '<p class="lead"><span><i class="icon-spinner icon-spin"></i> Please wait... </span></p>'
          });
        },
        success: function(data) {
          alert('Executive Summary saved!');
        },
        error: function(xhr, err) {
          alert('Error: Could not save executive summary of the study. Kindly refresh the page.');
        }
      });
      return false;
    });
    $('#ApplicationTrialStatusId').on('change', function() {
      // console.log($(this).serialize())
      var data_save = $('#ApplicationId').serializeArray();
      data_save.push({
        name: $(this).attr('name'),
        value: $(this).val()
      });

      $.ajax({
        url: $('form').attr('action'),
        type: $('form').attr('method'),
        dataType: 'json',
        data: data_save,
        beforeSend: function() {
          $.blockUI({
            css: {
              border: 'none',
              padding: '15px',
              backgroundColor: '#000',
              '-webkit-border-radius': '10px',
              '-moz-border-radius': '10px',
              opacity: .5,
              color: '#fff'
            },
            message: '<p class="lead"><span><i class="icon-spinner icon-spin"></i> Please wait... </span></p>'
          });
        },
        success: function(data) {
          alert('Status updated!');
        },
        error: function(xhr, err) {
          alert('Error: Could not update the status of the trial. Please logout and login again.');
        }
      });
      return false;
    });
    // CKEDITOR.replace( 'data[Application][final_report]');
    $(document).on('click', '#ApplicationViewSaveShortTitle', function() {
      var data_save = $('#ApplicationId').serializeArray();
      //var value = $('#ApplicationFinalReport').val();
      data_save.push({
        name: $('#ApplicationShortTitle').attr('name'),
        value: $('#ApplicationShortTitle').val()
      });

      $.ajax({
        url: $('form').attr('action'),
        type: $('form').attr('method'),
        dataType: 'json',
        data: data_save,
        beforeSend: function() {
          $.blockUI({
            css: {
              border: 'none',
              padding: '15px',
              backgroundColor: '#000',
              '-webkit-border-radius': '10px',
              '-moz-border-radius': '10px',
              opacity: .5,
              color: '#fff'
            },
            message: '<p class="lead"><span><i class="icon-spinner icon-spin"></i> Please wait... </span></p>'
          });
        },
        success: function(data) {
          alert('Short title successfully saved!');
        },
        error: function(xhr, err) {
          alert('Error: Could not save short title of the study. Kindly refresh the page.');
        }
      });
      return false;
    });
  });
</script>
<?php $this->end(); ?>
