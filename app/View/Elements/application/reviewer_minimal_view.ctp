<div class="span8">
   <?php echo $this->fetch('form-actions'); ?>

   <div id="applicationPrintArea">
    <div class="vformbackp">
       <hr>
      <table style="width: 100%;">
        <tr>
          <td style="width: 25%;">Protocol No:</td>
          <td style="width: 25%;"><strong><?php echo __($application['Application']['protocol_no'], true) ?></strong></td>
          <td style="width: 25%;">Date of Protocol:</td>
          <td style="width: 25%;"><strong><?php echo __($application['Application']['date_of_protocol'], true) ?></strong></td>
        </tr>
      </table>
       <hr>
      <table style="width: 100%;">
        <tr>
          <td style="width: 25%;">Abstract of Study:</td>
          <td style="width: 75%;"><strong><?php echo $application['Application']['abstract_of_study'] ?></strong></td>
        </tr>
        <tr>
          <td style="width: 25%;">Study Title:</td>
          <td style="width: 75%;"><strong><?php echo $application['Application']['study_title'] ?></strong></td>
        </tr>
      </table>
      <hr>                                                                               
          <h4>Principal Investigator(s)</h4>                                                                                          
          <?php if (!empty($application['InvestigatorContact'])): ?>                                                                  
            <table class="table table-bordered table-striped table-condensed">                                                        
              <thead>                                                                                                                 
                <tr>                                                                                                                  
                  <th>#</th>                                                                                                          
                  <th>Name</th>                                                                                                       
                  <th>Qualification</th>                                                                                              
                  <th>Professional Address / Institution</th>                                                                         
                </tr>                                                                                                                 
              </thead>                                                                                                                
              <tbody>                                                                                                                 
                <?php foreach ($application['InvestigatorContact'] as $key => $investigator): ?>                                      
                  <tr>                                                                                                                
                    <td><?php echo $key + 1; ?></td>                                                                                  
                    <td>                                                                                                              
                      <strong>                                                                                                        
                        <?php echo trim($investigator['given_name'] . ' ' . $investigator['middle_name'] . ' ' .                      
  $investigator['family_name']); ?>                                                                                                   
                      </strong>                                                                                                       
                    </td>                                                                                                             
                    <td><?php echo h($investigator['qualification']); ?></td>                                                         
                    <td><?php echo nl2br(h($investigator['professional_address'])); ?></td>                                           
                  </tr>                                                                                                               
                <?php endforeach; ?>                                                                                                  
              </tbody>                                                                                                                
            </table>                                                                                                                  
          <?php else: ?>                                                                                                              
            <p class="muted"><em>No Principal Investigator details provided.</em></p>                                                 
          <?php endif; ?>                                                                                                             
          <hr>                                                                                                                        
                                                                                                                                                                                                  
          <h4>Study Site(s)</h4>                                                                                                      
          <?php if (!empty($application['Application']['single_site_member_state']) &&                                                
  $application['Application']['single_site_member_state'] === 'Yes' || !empty($application['Application']['location_of_area'])): ?>                                                                                                
            <table class="table table-bordered table-striped table-condensed">                                                        
              <thead>                                                                                                                 
                <tr>                                                                                                                  
                  <th>#</th>                                                                                                          
                  <th>Site Name</th>                                                                                                  
                  <th>Physical Address</th>                                                                                           
                  <th>Contact Person</th>                                                                                             
                                                                                                               
                </tr>                                                                                                                 
              </thead>                                                                                                                
              <tbody>                                                                                                                 
                <tr>                                                                                                                  
                  <td>1</td>                                                                                                          
                  <td><strong><?php echo h($application['Application']['location_of_area']); ?></strong></td>                         
                  <td><?php echo nl2br(h($application['Application']['single_site_physical_address'])); ?></td>                       
                  <td><?php echo h($application['Application']['single_site_contact_person']); ?></td>                                
                                       
                </tr>                                                                                                                  
              </tbody>                                                                                                                
            </table>                                                                                                                  
                                                                                                                                      
          <?php elseif (!empty($application['SiteDetail'])): ?>                                                                      
            <table class="table table-bordered table-striped table-condensed">                                                        
              <thead>                                                                                                                 
                <tr>                                                                                                                  
                  <th>#</th>                                                                                                          
                  <th>Site Name</th>                                                                                                  
                  <th>Physical Address</th>                                                                                           
                  <th>County</th>                                                                                                     
                  <th>Contact Person</th>                                                                                             
                </tr>                                                                                                                 
              </thead>                                                                                                                
              <tbody>                                                                                                                 
                <?php foreach ($application['SiteDetail'] as $key => $site): ?>                                                       
                  <tr>                                                                                                                
                    <td><?php echo $key + 1; ?></td>                                                                                  
                    <td><strong><?php echo h($site['site_name']); ?></strong></td>                                                    
                    <td><?php echo nl2br(h($site['physical_address'])); ?></td>                                                       
                    <td>                                                                                                              
                      <?php                                                                                                           
                        if (!empty($site['County']['name'])) {                                                                        
                          echo h($site['County']['name']);                                                                            
                        } elseif (!empty($site['county_id']) && !empty($counties[$site['county_id']])) {                              
                          echo h($counties[$site['county_id']]);                                                                      
                        }                                                                                                             
                      ?>                                                                                                              
                    </td>
                    <td><?php echo h($site['contact_person']); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
  
          <?php else: ?>
            <p class="muted"><em>No Study Site details provided.</em></p>
          <?php endif; ?>
          <hr>
    </div>
  </div>

</div>
