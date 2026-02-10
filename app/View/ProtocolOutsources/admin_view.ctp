<div class="row-fluid">
  <?php
  $this->assign('Reports', 'active');
  ?>
  <h4>Assign Application to Outsourced Users</h4>
  <hr>
  <div class="row-fluid">
    <div class="span12">
      <dl class="dl-horizontal">
        <dt>ID</dt>
        <dd>
          <?php echo h($user['User']['id']); ?>
          &nbsp;
        </dd>
        <dt>Username</dt>
        <dd><?php echo $user['User']['username']; ?> &nbsp; </dd>
        <dt>Name</dt>
        <dd><?php echo $user['User']['name']; ?> &nbsp; </dd>
        <dt>Email</dt>
        <dd><?php echo $user['User']['email']; ?> &nbsp; </dd>
      </dl>
      <?php if (count($user['ProtocolOutsource']) > 0) { ?>
        <h5 class="text-success">Assigned protocols</h5>
        <!-- <table class="table table-condensed"> -->
        <table class="table  table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>ECCT Reference No.</th>
              <th>Task</th>
              <th>Created</th>
              <th><i class="icon-link"></i></th>
            </tr>
          </thead>
          <?php
          $i = 0;
          foreach ($user['ProtocolOutsource'] as $ptr) {
            $i++;
            echo "<tr>";
            echo "<td>" . $i . "</td><td>" . $ptr['Application']['protocol_no'] . "</td>";
          ?>
            <td>
              <input type="checkbox" name="selected_protocols[]" value="<?php echo h($ptr['Outsource']['model_sae']); ?>" <?php echo ($ptr['Outsource']['model_sae'] == 1) ? 'checked' : '';  ?> disabled> SAE/SUSAR <br>
              <input type="checkbox" name="selected_protocols[]" value="<?php echo h($ptr['Outsource']['model_ciom']); ?>" <?php echo ($ptr['Outsource']['model_ciom'] == 1) ? 'checked' : ''; ?> disabled> CIOMS <br>
              <input type="checkbox" name="selected_protocols[]" value="<?php echo h($ptr['Outsource']['model_dev']); ?>" <?php echo ($ptr['Outsource']['model_dev'] == 1) ? 'checked' : ''; ?> disabled> Deviations <br>

              <?php
              if (count($ptr['Outsource']['PendingOutsourceRequest'])) { ?>
                <h5>New Requests <span><?php echo count($ptr['Outsource']['PendingOutsourceRequest']) ?> </span></h5>
                <table class="table  table-bordered table-striped">
          <thead>
          </thead>
          <tbody>
          <?php
            $cc = 0;
            foreach ($ptr['Outsource']['PendingOutsourceRequest'] as $request) {
              $cc++;

              // debug($request);
              // exit;
              
              ?>
             <tr>

             <td>
              <input type="checkbox" name="selected_protocols[]" value="<?php echo h($request['sae']); ?>" <?php echo ($request['sae'] == 1) ? 'checked' : '';  ?> disabled> SAE/SUSAR <br>
              <input type="checkbox" name="selected_protocols[]" value="<?php echo h($request['ciom']); ?>" <?php echo ($request['ciom'] == 1) ? 'checked' : ''; ?> disabled> CIOMS <br>
              <input type="checkbox" name="selected_protocols[]" value="<?php echo h($request['dev']); ?>" <?php echo ($request['dev'] == 1) ? 'checked' : ''; ?> disabled> Deviations <br>


                <!-- Show the attached files -->
                <?php
                    if (count($request['Attachment']) > 0) {
                    ?>
                        <div class="row-fluid">
                             
                            <div class="span10">
                                <h5>Supporting Documents </h5>

                                <table id="buildoutsourceform" class="table table-bordered  table-condensed table-striped">
                                    <thead>
                                        <tr id="attachmentsTableHeader">
                                            <th>#</th>
                                            <th width="45%">File</th>
                                            <th width="45%">Text Description</th>
                                            <th> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($request['Attachment'] as $i => $file) {
                                        ?> <tr>
                                                <td>
                                                    <span class="badge badge-info"><?php echo $i + 1; ?></span>
                                                </td>
                                                <td>
                                                    <?php
                                                    echo $this->Html->link(
                                                        __($file['basename']),
                                                        array('controller' => 'attachments', 'action' => 'download', $file['id'], 'full_base' => true),
                                                        array('class' => '')
                                                    );
                                                    ?>
                                                </td>
                                                <td></td>
                                                <td> <?php
                                                        echo $this->Html->link(
                                                            __('Download'),
                                                            array('controller' => 'attachments', 'action' => 'download', $file['id'], 'full_base' => true),
                                                            array('class' => 'btn btn-sm btn-info')
                                                        );
                                                        ?></td>
                                            </tr>

                                        <?php }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div> 

                    <?php  } 
                    
                    
                    echo  $this->Form->postLink(
                      __('<span class="label label-success">Approve</span>'),
                      array('controller'=>'protocol_outsources','action' => 'approve_other', $request['id']),
                      array('escape' => false),
                      __('Are you sure you want to approve this request?')
                    );
                   echo "&nbsp;";

                    echo$this->Form->postLink(
                      __('<span class="label label-important">Reject</span>'),
                      array('controller'=>'protocol_outsources','action' => 'reject_other', $request['id']),
                      array('escape' => false),
                      __('Are you sure you want to reject this request?')
                    )
                    ?>



                    <!-- End of file attachment -->

                    
             </td>
             </tr>

          
          
        <?php }  ?>

        </tbody>
                </table>

              <?php }
              ?>
            </td>


          <?php
            echo "<td>" . $ptr['created'] . "</td>";
            echo "<td>" . $this->Form->postLink(
              __('<span class="label label-important">Remove</span>'),
              array('action' => 'delete', $ptr['id']),
              array('escape' => false),
              __('Are you sure you want to remove protocol %s from user %s profile?', $ptr['Application']['protocol_no'], $user['User']['name'])
            ) . "</td>";
            echo "</tr>";
          }
          ?>
        </table>
      <?php } ?>


    </div>
  </div>
  <hr>