
<?php if(($this->Session->read('Auth.User.id') == $rreview['user_id']) and $rreview['status'] != 'Summary') { ?>
  <div class="page-header">
    <div class="styled_title"><h3>Exit Report</h3></div>
  </div>
    <?php
      echo $this->Form->create('Review', array(
            'url' => array('controller' => 'reviews','action' => 'exit', $rreview['id'], $rreview['application_id']),
            'type' => 'file',
            'class' => 'form-horizontal',
            'inputDefaults' => array(
              'div' => array('class' => 'control-group'),
              'label' => array('class' => 'control-label'),
              'between' => '<div class="controls">',
              'after' => '</div>',
              'class' => '',
              'format' => array('before', 'label', 'between', 'input', 'after','error'),
              'error' => array('attributes' => array( 'class' => 'controls help-block')),
             ),
      ));
      echo $this->Form->input('Review.'.$akey.'.id', array('value' => $rreview['id'], 'type' => 'hidden'));

      echo $this->Form->input('Review.'.$akey.'.recommendation',
        array('class' => 'input-xxlarge', 'label' => array('class' => 'control-label required', 'text' => 'Recommendation <span class="sterix">*</span>')));
      ?>
  <div class="row-fluid">
                      <div class="span11">
                        <div class="uploadsTable">
                          <h6 class="muted"><b>Attach File(s) </b>
                              <button type="button" class="btn btn-primary btn-small addUpload">&nbsp;<i class="icon-plus"></i>&nbsp;</button>
                          </h6>
                          <hr>
                        </div>
                      </div>
                  </div>
    <div class="row-fluid">
      <div class="span10">
        <div class="well">
          <?php
            echo $this->Form->button('<i class="icon-thumbs-up"></i> Submit', array(
                'name' => 'submitReport',
                'onclick'=>"return confirm('Are you sure you wish to submit the report?');",
                'class' => 'btn btn-info  mapop',
                'id' => 'LeloSubmitReport', 'title'=>'Save and Submit Report',
                'data-content' => 'Save the report and submit.',
                'div' => false,
              ));

          ?>
        </div>
      </div>
    </div>

    <?php
    echo $this->Form->end();
  ?>

  <?php } else { ?>
  <div class="page-header">
    <div class="styled_title"><h3>Exit Report</h3></div>
  </div>
    <table class="table  table-condensed">
      <tbody>
       <tr>
        <td class="table-label required"><p>Summary of comments: <span class="sterix">*</span></p></td>
        <td>
          <?php 
               echo $rreview['summary'];
          ?>
        </td>
       </tr>
       <tr>
        <td class="table-label required"><p>Reccomendation: <span class="sterix">*</span></p></td>
        <td>
          <?php 
               echo $rreview['recommendation'];
          ?>
        </td>
       </tr>
      </tbody>
    </table>
  <?php } ?>
