<?php 
  echo $this->Session->flash();
?>

  <br>
  <div class="row-fluid">
    <div class="span12">      
        <?php 
          if($redir == 'applicant' || $redir == 'outsource') {
              echo $this->Html->link(__('<i class="icon-upload"></i> Upload CIOM'),
                        array('controller' => 'cioms', 'action' => 'add', $application['Application']['id']),
                        array('escape' => false, 'class' => 'btn btn-inverse'));
          }
        ?>
    </div>
  </div>
  <br>
    <table class="table table-condensed table-bordered" style="margin-bottom: 2px;">
      <thead>
        <tr>
          <th>#</th>
          <th>Filename</th>
          <th>Created</th>
          <th><?php echo __('Actions'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
          $ciomNo = 1;
          foreach ($application['Ciom'] as $akey => $ciom) {
        ?>
          <tr>
            <td><?php echo $ciomNo++; ?></td>
            <td>
              <?php 
                // echo h($ciom['Ciom']['basename']); 
                // echo $this->Html->link(
                //     $ciom['basename'],
                //     str_replace('/var/www/ctr/app/webroot', '', $ciom['file']),
                //     array('class' => 'button', 'target' => '_blank')
                // );
              echo $this->Html->link(__($ciom['basename']), array('controller' => 'cioms', 'action' => 'download', $ciom['id'], 'admin' => false), array('escape' => false));
              ?>
            </td>
            <td><?php echo $ciom['created'] ?></td> 
            <td>
              <?php echo $this->Html->link(__('<label class="label label-info">View</label>'), array( 'controller' => 'cioms', 'action' => 'view', $ciom['id']), array('escape' => false)); ?>                     
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>


  <br>
  <hr>
