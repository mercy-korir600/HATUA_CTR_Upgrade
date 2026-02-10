<?php
$this->Html->script('multi/checklists', array('inline' => false));
$add_checklist = '<p><button class="btn btn-mini tiptip add-checklist" data-original-title="Add a file"
                                style="margin-left:10px;" type="button">&nbsp;<i class="icon-plus-sign"></i>&nbsp; </button>';
$num = 0;
?>
<h5>CHECKLIST <span class="sterix">*</span></h5>
<h5>All submitted documents should be version referenced and dated.?</h5>

<hr>
<?php
$medals = $this->requestAction('/pockets/checklist/protocol');
$reqs = $this->requestAction('/pockets/lchecklist/protocol');
$group_data = array();
foreach ($medals as $lad => $medal) {
    $num++;
    ($reqs[$lad]) ? $req = '<span class="sterix">*</span>' : $req = '';
    echo $this->Form->input($lad, array(
        'label' => array('class' => 'control-checklabel', 'text' => $num . '.'),
        'error' => array('attributes' => array('class' => 'checkcontrols help-block')),
        'class' => false,
        'hiddenField' => false,
        'between' => '<div class="checkcontrols"><input type="hidden" value="0" id="ApplicationApplicantProtocol_" name="data[Application][' . $lad . ']">
                                            <label class="checkbox required pull-left">',
        'after' => $medal . ' ' . $req . ' </label>' . $add_checklist,
    ));
?>
    <div id="Checklist" class="checkcontrols" title="<?php echo $lad ?>">
        <?php
        if (isset($this->request->data['Checklist'])) {
            $totalMatchingItems = 0;
            $itemsInSection = [];
            
            foreach ($this->request->data['Checklist'] as  $protocol) {
                $totalItems = count($protocol);
                
                 // Initialize the counter
                if ($protocol['group'] === $lad) {
                    $totalMatchingItems++;
                    $itemsInSection[] = $protocol; 
                }
            }
            foreach ($itemsInSection as $bKey => $protocol) {
                $isLastItem = ($bKey + 1 === $totalMatchingItems); // Check if this is the last item
            
                   
                    echo '<div style="margin-top: 5px; margin-bottom: 5px;">';
                    echo $this->Form->input('Checklist.' . $bKey . '.id');
                    echo $this->Form->input('Checklist.' . $bKey . '.basename', array('type' => 'hidden'));
                    echo $this->Form->input('Checklist.' . $bKey . '.model', array('type' => 'hidden'));
                    // echo $this->Form->input('Checklist.'.$bKey.'.group', array('type'=>'hidden'));
                    echo $this->Form->input('Checklist.' . $bKey . '.file_date', array('type' => 'hidden'));
                    echo $this->Form->input('Checklist.' . $bKey . '.version_no', array('type' => 'hidden'));
                    if (!empty($protocol['id']) && !empty($protocol['basename'])) {
                        echo $this->Html->link(
                            __($protocol['basename']),
                            array('controller' => 'attachments',   'action' => 'download', $protocol['id']),
                            array('class' => 'btn btn-info')
                        );
                        echo "&nbsp;<span>version: " . $protocol['version_no'] . "</span>";
                        echo "&nbsp;<span>date: " . $protocol['file_date'] . "</span>";

                        echo '<button value="' . $protocol['id'] . '" type="button" class="btn btn-mini btn-danger delete_file_link">
                        &nbsp;<i class="icon-trash"></i>&nbsp;</button>';
                        // Add this button if it's the last item in the lisr
                      if($isLastItem){
                        echo '<button class="btn btn-mini tiptip sample" data-original-title="Add a file"
                                style="margin-left:10px;" type="button">&nbsp;<i class="icon-plus-sign"></i>&nbsp; </button>';
                      }
                    } 
                    else {
                        echo $this->Form->input('Checklist.' . $bKey . '.model', array('type' => 'hidden', 'value' => 'Checklist'));
                        echo $this->Form->input('Checklist.' . $bKey . '.group', array('type' => 'hidden', 'value' => $lad));
                        echo $this->Form->input('Checklist.' . $bKey . '.description', array('type' => 'hidden', 'value' => $medal));
                        echo $this->Form->input('Checklist.' . $bKey . '.pocket_name', array('type' => 'hidden', 'value' => $lad));
                        echo $this->Form->input('Checklist.' . $bKey . '.dirname', array('type' => 'hidden'));
                        echo $this->Form->input('Checklist.' . $bKey . '.checksum', array('type' => 'hidden'));
                        echo $this->Form->input('Checklist.' . $bKey . '.file', array(
                            'type' => 'file',
                            'label' => false,
                            'div' => false,
                            'error' => array('attributes' => array('class' => 'error help-block')),
                            'between' => false,
                            'after' => '<span id="ProtocolHelp' . $bKey . '" class="help-inline"> Upload! </span>'
                        ));
                    }
                    echo "</div>";
                
            
        }}
        ?>
    </div>
    </div>
<?php
        }
    
?>
