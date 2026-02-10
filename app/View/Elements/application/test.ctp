<?php
        echo $this->Form->create('Application', array(
          'url' => array_merge(array('action' => 'workflow'), $this->params['pass']),
          'class' => 'ctr-groups', 'style'=>array('padding:9px;', 'background-color: #F5F5F5'),
        ));
      ?>
      <div class="row-fluid">
        <div class="span12">
          <table class="table table-condensed table-bordered" style="margin-bottom: 2px;">
            <thead>
            <tr>
              <th style="width: 15%;">
              <?php


                echo $this->Form->input('protocol_no',
                    array('div' => false, 'class' => 'span12 unauthorized_index', 'label' => array('class' => 'required', 'text' => 'ECCT Reference No.')));
              ?>
              </th>
              <th>
              <?php
                echo $this->Form->input('filter', array('div' => false, 'class' => 'span12 unauthorized_index',
                  'label' => array('class' => 'required', 'text' => 'Study Title'),
                  'type' => 'text',
                  ));
              ?>
              </th>
              <th>
              <?php
                echo $this->Form->input('investigator', array('div' => false, 'class' => 'span12 unauthorized_index',
                  'label' => array('class' => 'required', 'text' => 'Principal Investigator(s)'),
                  'type' => 'text',
                  ));
              ?>
              </th>
              <th>
              <?php
                echo $this->Form->input('sites', array('div' => false, 'class' => 'span12 unauthorized_index',
                  'label' => array('class' => 'required', 'text' => 'Name of Site / County'),
                  'type' => 'text',
                  ));
              ?>
              </th>
              <th>
                <?php
                  echo $this->Form->input('pages', array(
                    'type' => 'select', 'div' => false, 'class' => 'span12', 'selected' => $this->request->params['paging']['Application']['limit'],
                    'empty' => true,
                    'options' => $page_options,
                    'label' => array('class' => 'required', 'text' => 'Pages'),
                  ));
                ?>
              </th>
              <th rowspan="2" style="width: 14%;">
                <?php
                  echo $this->Form->button('<i class="icon-search icon-white"></i> Search', array(
                      'class' => 'btn btn-inverse', 'div' => 'control-group', 'div' => false,
                      'style' => array('margin-bottom: 5px')
                  ));

                  echo $this->Html->link('<i class="icon-remove"></i> Clear', array('action' => 'index'), array('class' => 'btn', 'escape' => false, 'style' => array('margin-bottom: 5px')));
                  echo "<br>";
                  // if($redir) echo $this->Html->link('<i class="icon-file-alt"></i> Excel', array('action' => 'index', 'ext' => 'csv'), array('class' => 'btn btn-success', 'escape' => false));
                  if($redir) echo $this->Html->link('<i class="icon-file-alt"></i> Excel', array('action' => 'index', 'ext' => 'csv', '?' => $this->request->query), array('class' => 'btn btn-success', 'escape' => false));;
                ?>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr class="searchmore" style="display: none;">
              <td colspan="2">
              <?php
                echo $this->Form->input('start_date',
                  array('div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index', 'after' => '-to-',
                      'label' => array('class' => 'required', 'text' => 'Application Submission Dates'), 'placeHolder' => 'Start Date'));
                echo $this->Form->input('end_date',
                  array('div' => false, 'type' => 'text', 'class' => 'input-small unauthorized_index',
                       'after' => '<a style="font-weight:normal" onclick="$(\'.unauthorized_index\').val(\'\');" >
                            <em class="accordion-toggle">clear!</em></a>',
                      'label' => false, 'placeHolder' => 'End Date'));
              ?>
              </td>
              <td>
                <?php
                  //pr($this->request->params); //REMEMBER to limit this for administrators, managers and inspector only
                  if($this->fetch('is-admin') == 'true' || $this->fetch('is-manager') == 'true' || $this->fetch('is-inspector') == 'true') {
                      echo $this->Form->input('users', array(
                        'type' => 'select', 'div' => false, 'class' => 'span12',
                        'empty' => true,
                        'options' => $users,
                        'label' => array('class' => 'required', 'text' => 'Reviewers'),
                      ));
                  }
                ?>
              </td>
              <td colspan="3">
                <?php
                  //pr($this->request->params); //REMEMBER to limit this for administrators, managers and inspector only
                  if($this->fetch('is-admin') == 'true' || $this->fetch('is-manager') == 'true' || $this->fetch('is-inspector') == 'true') {
                      echo $this->Form->input('ercs', array(
                        'type' => 'select', 'div' => false, 'class' => 'span12',
                        'empty' => true,
                        'options' => $ercs,
                        'label' => array('class' => 'required', 'text' => 'ERCs'),
                      ));
                  }
                ?>
              </td>
            </tr>
            <tr class="searchmore" style="display: none;">
              <td colspan="2">
              <?php
                if($this->fetch('is-admin') == 'true' || $this->fetch('is-manager') == 'true' || $this->fetch('is-inspector') == 'true') {
                  echo $this->Form->input('disease_condition',
                      array('div' => false, 'class' => 'span12 unauthorized_index', 'label' => array('class' => 'required', 'text' => 'Disease Condition')));
                }
              ?>
              </td>
              <td>
                
              </td>
              <td>
                
              </td>
              <td>
               
              </td>
              <td>
               
              </td>              
            </tr>
          </tbody>
         </table>
         <a href="#"  id='moresearch' class="muted"><small><i class="icon-caret-right"></i> Extended search...</small></a>
      </div>
      </div>
        <p>
          <?php
            echo $this->Paginator->counter(array(
            'format' => __('Page <span class="badge">{:page}</span> of <span class="badge">{:pages}</span>,
                    showing <span class="badge">{:current}</span> Applications out of
                    <span class="badge badge-inverse">{:count}</span> total, starting on record <span class="badge">{:start}</span>,
                    ending on <span class="badge">{:end}</span>')
            ));
          ?>
          </p>
    <?php echo $this->Form->end(); ?>
          <div class="pagination">
            <ul>
            <?php
              echo $this->Paginator->prev('&laquo;', array('tag' => 'li', 'escape' => false), null, array('class' => 'disabled', 'tag' => 'li', 'escape' => false));
              echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentClass' => 'active'));
              echo $this->Paginator->next('&raquo;', array('tag' => 'li', 'escape' => false), null, array('class' => 'disabled', 'tag' => 'li', 'escape' => false ));
            ?>
            </ul>
          </div>

