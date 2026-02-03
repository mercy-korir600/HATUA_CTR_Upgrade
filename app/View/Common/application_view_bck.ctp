
<?php echo $this->fetch('amendment-lead'); ?>
  <hr style="margin:5px;">
  <?php echo $this->Session->flash(); ?>

<div class="row-fluid">
  <?php echo $this->fetch('view-sidebar'); ?>
  <div class="span10">
     <?php
        $ichecked = "&#x2611;";
        $nchecked = "&#x2610;";
        echo $this->fetch('form-header');
    ?>

    <!-- Hole:  Form actions -->
      <?php
           echo $this->fetch('form-actions');
       ?>
      <div id="tabs">

        <?php echo $this->fetch('tabs'); ?>

        <div id="tabs-1">
          <table class="table table-condensed">
            <thead>
            <tr><th class="table-label required"><p>Study Title: <span class="sterix">*</span></p></th></tr>
            </thead>
            <tbody>
            <tr><td><?php echo $application['Application']['study_title'] ?></td> </tr>
            <?php  echo $this->fetch('study_title');  ?>
            </tbody>
          </table>

          <table class="table table-condensed">
            <thead>
            <tr><th class="table-label required"><p>Abstract of the study: <span class="sterix">*</span></p></th></tr>
            </thead>
            <tbody>
            <tr><td><?php echo $application['Application']['abstract_of_study'] ?></td> </tr>
            <?php  echo $this->fetch('abstract_of_study');  ?>
            </tbody>
          </table>

          <table class="table  table-condensed">
            <tbody>
             <tr>
              <td class="table-label required"><p>Version No: <span class="sterix">*</span></p></td>
              <td><?php echo $application['Application']['version_no']?></td>
             </tr>
             <?php  echo $this->fetch('version_no');  ?>
             <tr>
              <td class="table-label required"><p>Date of Protocol <span class="sterix">*</span></p></td>
              <td><?php echo $application['Application']['date_of_protocol'] ?></td>
             </tr>
             <?php  echo $this->fetch('date_of_protocol');  ?>
            <tr>
              <td class="table-label required"><p>Study Drug <span class="sterix">*</span></p></td>
              <td><?php echo $application['Application']['study_drug'] ?></td>
            </tr>
            <?php  echo $this->fetch('study_drug');  ?>
            <tr>
              <td class="table-label required"><p>Disease condition being investigated <span class="sterix">*</span></p></td>
              <td><?php echo $application['Application']['disease_condition'] ?></td>
            </tr>
            <?php  echo $this->fetch('disease_condition');  ?>
            <tr>
              <td class="table-label required" rowspan="3"><p>Product Type <span class="sterix">*</span></p></td>
              <td>
                <p class="control-nolabel"><?php echo ($application['Application']['product_type_biologicals']   ? $ichecked : $nchecked ); ?> Biologicals
                </p>

                <p class="control-nolabel">
                <?php echo ($application['Application']['product_type_proteins']   ? $ichecked : $nchecked ); ?> Proteins
                <?php echo ($application['Application']['product_type_immunologicals']   ? $ichecked : $nchecked ); ?> Immunologicals
                <?php echo ($application['Application']['product_type_vaccines']   ? $ichecked : $nchecked ); ?> Vaccines
                <?php echo ($application['Application']['product_type_hormones']   ? $ichecked : $nchecked ); ?> Hormones
                <?php echo ($application['Application']['product_type_toxoid']   ? $ichecked : $nchecked ); ?> Toxoid
                </p>
              </td>
            </tr>
            <tr>
              <td>
                <p class="control-nolabel"><?php echo ($application['Application']['product_type_chemical']   ? $ichecked : $nchecked ); ?> Chemical</p>
                <p><?php echo $application['Application']['product_type_chemical_name'];?></p>
              </td>
            </tr>
            <tr>
              <td>
                <p class="control-nolabel"><?php echo ($application['Application']['product_type_medical_device']   ? $ichecked : $nchecked ); ?> Medical Device</p>
                <p><?php echo $application['Application']['product_type_medical_device_name'];?></p>
              </td>
            </tr>
            <?php  echo $this->fetch('product_type');  ?>
            <tr>
              <td class="table-label"><p>Date(s) ECCT approval of previous protocol(s)</p></td>
              <td><?php
                foreach($application['PreviousDate'] as $date) {
                  echo "<p>". $date['date_of_previous_protocol']."</p>";
                }
               ?></td>
            </tr>
            <tr>
              <td class="table-label required">
                <p>Approval Date of Protocol <span class="sterix">*</span></p>
              </td>
              <td>
                <p><?php echo $application['Application']['approval_date'];?></p>
              </td>
            </tr>
            <?php  echo $this->fetch('approval_date');  ?>
            </tbody>
          </table>

        </div>
        <div id="tabs-2">
                         <h5>2.0 CO-ORDINATING INVESTIGATOR (<em>for multicentre trials in Kenya</em>) </h5>
          <table class="table  table-condensed">
            <tbody>
            <tr>
              <td class="table-label required"><p>Given name <span class="sterix">*</span></p></td>
              <td><?php echo $application['Application']['investigator1_given_name']?></td>
            </tr>
            <tr>
              <td class="table-label"><p>Middle name, if applicable</p></td>
              <td><?php echo $application['Application']['investigator1_middle_name'] ?></td>
            </tr>
            <tr>
              <td class="table-label required"><p>Family name <span class="sterix">*</span></p></td>
              <td><?php echo $application['Application']['investigator1_family_name'] ?></td>
            </tr>
            <tr>
              <td class="table-label required"><p>Qualification<span class="sterix">*</span></p></td>
              <td><?php echo $application['Application']['investigator1_qualification'] ?></td>
            </tr>
            <tr>
              <td class="table-label required"> <p>Professional address <span class="sterix">*</span></p> </td>
              <td>
                <p><?php echo $application['Application']['investigator1_professional_address'];?></p>
              </td>
            </tr>
            </tbody>
          </table>
           <h5>2.1 PRINCIPAL INVESTIGATOR (<small>for multicentre trial; where necessary, Click button to add more -
<button type="button" class="btn-mini" id="addPIContact" title="add contact">Add Contact</button></small>) </h5>
          <?php foreach ($application['InvestigatorContact'] as $investigatorContact) { ?>
          <span class="badge badge-info"><?php echo $investigatorContact['id']?></span>
          <table class="table  table-condensed">
            <tbody>
            <tr>
              <td class="table-label required"><p>Given name <span class="sterix">*</span></p></td>
              <td><?php echo $investigatorContact['given_name']?></td>
            </tr>
            <tr>
              <td class="table-label"><p>Middle name, if applicable</p></td>
              <td><?php echo $investigatorContact['middle_name'] ?></td>
            </tr>
            <tr>
              <td class="table-label required"><p>Family name <span class="sterix">*</span></p></td>
              <td><?php echo $investigatorContact['family_name'] ?></td>
            </tr>
            <tr>
              <td class="table-label required"><p>Qualification<span class="sterix">*</span></p></td>
              <td><?php echo $investigatorContact['qualification'] ?></td>
            </tr>
            <tr>
              <td class="table-label required"> <p>Professional address <span class="sterix">*</span></p> </td>
              <td>
                <p><?php echo $investigatorContact['professional_address'];?></p>
              </td>
            </tr>
            </tbody>
          </table>
          <hr>
          <?php } ?>
        </div>
        <div id="tabs-3" >
           <h5>3.0 SPONSOR DETAILS (<small>where necessary, Click button to add more -
<button type="button" class="btn-mini" id="addSponsorDetail" title="add detail">Add Detail</button></small>) </h5>
          <?php foreach ($application['Sponsor'] as $sponsor) { ?>
          <span class="badge badge-info"><?php echo $sponsor['id']?></span>
          <table class="table  table-condensed">
            <tbody>
            <tr>
              <td class="table-label required"><p>Sponsor <span class="sterix">*</span></p></td>
              <td><?php echo $sponsor['sponsor']?></td>
            </tr>
            <tr>
              <td class="table-label"><p>Contact Person </p></td>
              <td><?php echo $sponsor['contact_person'] ?></td>
            </tr>
            <tr>
              <td class="table-label required"><p>Address <span class="sterix">*</span></p></td>
              <td><?php echo $sponsor['address'] ?></td>
            </tr>
            <tr>
              <td class="table-label required"><p>Telephone Number <span class="sterix">*</span></p></td>
              <td><?php echo $sponsor['telephone_number'] ?></td>
            </tr>
            <tr>
              <td class="table-label"><p>Fax Number</p></td>
              <td><?php echo $sponsor['fax_number'] ?></td>
            </tr>

            <tr>
              <td class="table-label required"><p>Mobile phone number<span class="sterix">*</span></p></td>
              <td><?php echo $sponsor['cell_number'] ?></td>
            </tr>

            <tr>
              <td class="table-label required"><p>Email Address<span class="sterix">*</span></p></td>
              <td><?php echo $sponsor['email_address'] ?></td>
            </tr>
            </tbody>
          </table>
          <hr>
          <?php } ?>
        </div>
        <div id="tabs-4">
          <h5>4.0 PARTICIPANTS (SUBJECTS)</h5>
          <table class="table  table-condensed">
            <tbody>
            <tr>
              <td class="table-label required"><p>Expected Number of participants in Kenya <span class="sterix">*</span></p></td>
              <td><?php echo $application['Application']['number_participants']?></td>
            </tr>
            <tr>
              <td class="table-label"><p>Total enrolment in each Kenyan site: (if competitive enrolment, state minimum and maximum number per site.) </p></td>
              <td><?php echo $application['Application']['total_enrolment_per_site'] ?></td>
            </tr>
            <tr>
              <td class="table-label required"><p>Total participants worldwide <span class="sterix">*</span></p></td>
              <td><?php echo $application['Application']['total_participants_worldwide'] ?></td>
            </tr>
            </tbody>
          </table>
          <hr>
          <h5>4.1 AGE SPAN</h5>
          <table class="table  table-condensed">
            <tbody>
            <tr>
              <td class="table-label required"><p>Less than 18 years?  <span class="sterix">*</span></p></td>
              <td><?php echo $application['Application']['population_less_than_18_years']?></td>
            </tr>
            </tbody>
          </table>
          <div class="ctr-groups">
            <p class="topper"><em class="text-success">If Yes, Specify</em></p>
            <table class="table  table-condensed">
              <tbody>
              <tr>
                <td class="table-label required"><p>In Utero <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['population_utero']?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Preterm Newborn Infants (up to gestational age &lt; 37 weeks) <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['population_preterm_newborn']?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Newborn (0-28 days) <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['population_newborn']?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Infant and toddler (29 days - 23 months) <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['population_infant_and_toddler']?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Children (2-12 years) <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['population_children']?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Adolescent (13-17 years) <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['population_adolescent']?></td>
              </tr>
              </tbody>
            </table>
          </div>

          <table class="table  table-condensed">
            <tbody>
            <tr>
              <td class="table-label required"><p>18 Years and over <span class="sterix">*</span></p></td>
              <td><?php echo $application['Application']['population_above_18']?></td>
            </tr>
            </tbody>
          </table>
          <div class="ctr-groups">
            <p class="topper"><em class="text-success">If Yes, Specify</em></p>
            <table class="table  table-condensed">
              <tbody>
              <tr>
                <td class="table-label required"><p>Adult (18-65 years) <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['population_adult']?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Elderly (&gt; 65 years) <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['population_elderly']?></td>
              </tr>
              </tbody>
            </table>
          </div>
          <hr>
          <h5>4.2 GROUP OF TRIAL SUBJECTS</h5>
          <hr>
            <table class="table  table-condensed">
              <tbody>
              <tr>
                <td class="table-label required"><p>Healthy volunteers <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['subjects_healthy']?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Specific vulnerable populations <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['subjects_vulnerable_populations']?></td>
              </tr>
              </tbody>
            </table>
          <div class="ctr-groups">
            <p class="topper"><em class="text-success">Specific vulnerable populations</em></p>
            <table class="table  table-condensed">
              <tbody>
              <tr>
                <td class="table-label required"><p>Patients <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['subjects_patients']?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Women of child bearing potential <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['subjects_women_child_bearing']?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Women of child bearing potential using contraception <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['subjects_women_using_contraception']?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Pregnant women <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['subjects_pregnant_women']?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Nursing Women <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['subjects_nursing_women']?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Emergency situation <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['subjects_emergency_situation']?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Subjects incapable of giving consent personally <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['subjects_incapable_consent']?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>If yes, specify <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['subjects_specify']?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Others <span class="sterix">*</span></p></td>
                <td><?php echo $application['Application']['subjects_others']?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>If yes, specify</p></td>
                <td><?php echo $application['Application']['subjects_others_specify']?></td>
              </tr>
              </tbody>
            </table>
          </div>
          <hr>
          <h5>4.3 GENDER</h5>
            <table class="table  table-condensed">
              <tbody>
              <tr>
                <td class="table-label"><p></p></td>
                <td><p><?php echo ($application['Application']['gender_female']   ? $ichecked : $nchecked ); ?> Female</p></td>
              </tr>
              <tr>
                <td class="table-label"><p></p></td>
                <td><p><?php echo ($application['Application']['gender_male']   ? $ichecked : $nchecked ); ?> Male</p></td>
              </tr>
              </tbody>
            </table>
        </div>
        <div id="tabs-5">
          <h5>TICK AND PROVIDE NECESSARY DETAILS AS APPROPRIATE</h5>
          <hr>
          <h5>5.0 Number of Sites</h5>
          <table class="table  table-condensed">
            <tbody>
            <tr>
              <td class="table-label"><p>Single site in Kenya  <span class="sterix">*</span></p></td>
              <td><p><?php echo $application['Application']['single_site_member_state'] ; ?> </p></td>
            </tr>
            <tr>
              <td class="table-label"><p>If yes, name of site</p></td>
              <td><p><?php echo $application['Application']['location_of_area'] ; ?> </p></td>
            </tr>
            </tbody>
          </table>
          <div class="ctr-groups">
             <table class="table  table-condensed">
              <tbody>
            <tr>
              <td class="table-label"><p>Multiple sites in Kenya <span class="sterix">*</span></p></td>
              <td><p><?php echo $application['Application']['multiple_sites_member_state'] ; ?> </p></td>
            </tr>
            <tr>
              <td class="table-label"><p>Number of sites anticipated in Kenya</p></td>
              <td><p><?php echo $application['Application']['number_of_sites'] ; ?> </p></td>
            </tr>
                </tbody>
             </table>
               <hr>
            <h5 class="controls">Details of Site(s) (<small>Repeat as necessary <button title="add contact" id="addSiteDetail" class="btn-mini multiple_sites_member_state_f" type="button">Add Site</button></small>)</h5>
            <?php foreach ($application['SiteDetail'] as $siteDetail) { ?>
            <span class="badge badge-info"><?php echo $siteDetail['id']?></span>
            <table class="table  table-condensed">
              <tbody>
              <tr>
                <td class="table-label required"><p>Name of Site </p></td>
                <td><?php echo $siteDetail['site_name']?></td>
              </tr>
              <tr>
                <td class="table-label"><p>Physical address </p></td>
                <td><?php echo $siteDetail['physical_address'] ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Contact details <small style="font-weight:normal;"><em>(tel.no, p.o box..) </em></small></p></td>
                <td><?php echo $siteDetail['contact_details'] ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Contact person</p></td>
                <td><?php echo $siteDetail['contact_person'] ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>County</p></td>
                <td><?php if($siteDetail['county_id']) echo $counties[$siteDetail['county_id']]; ?></td>
              </tr>
              </tbody>
            </table>
            <hr>
            <?php } ?>
          </div>
          <table class="table  table-condensed">
              <tbody>
            <tr>
              <td class="table-label"><p>Multiple Countries <span class="sterix">*</span></p></td>
              <td><p><?php echo $application['Application']['multiple_countries'] ; ?> </p></td>
            </tr>
            <tr>
              <td class="table-label"><p>Number of states anticipated in the trial</p></td>
              <td><p><?php echo $application['Application']['multiple_member_states'] ; ?> </p></td>
            </tr>
            <tr>
              <td class="table-label"><p>If yes above, list the countries</p></td>
              <td><p><?php echo $application['Application']['multi_country_list'] ; ?> </p></td>
            </tr>
            <tr>
              <td class="table-label"><p>Does this trial have a data monitoring committee?  <span class="sterix">*</span> </p></td>
              <td><p><?php echo $application['Application']['data_monitoring_committee'] ; ?> </p></td>
            </tr>
                </tbody>
           </table>
                            <hr>
                            <h5>5.1</h5>
                            <table class="table  table-condensed">
              <tbody>
            <tr>
              <td class="table-label"><p>Capacity of Site(s) <span class="sterix">*</span></p></td>
              <td><p><?php echo $application['Application']['staff_numbers'] ; ?> </p></td>
            </tr>
                </tbody>
           </table>
        </div>
        <div id="tabs-6">
          <h5>6.0 ORGANISATIONS TO WHOM THE SPONSOR HAS TRANSFERRED TRIAL RELATED DUTIES AND FUNCTIONS (<small>repeat as needed for multiple organisations
          - <button type="button" class="btn-mini" id="addOrganization" title="add organization">Add Organization</button></small>) </h5>
          <div class="ctr-groups">
            <p class="control-nolabel required">Has the sponsor transferred any major or all the sponsor&rsquo;s trial related duties and functions to another organisation or third party?</p>
            <p><?php echo $application['Application']['organisations_transferred_']; ?></p>

            <small><em>Repeat as necessary for multiple organizations</em></small><br>
            <?php foreach ($application['Organization'] as $organization) { ?>
            <span class="badge badge-info"><?php echo $organization['id']?></span>
            <table class="table  table-condensed">
              <tbody>
              <tr>
                <td class="table-label required"><p>Organization <span class="sterix">*</span></p></td>
                <td><?php echo $organization['organization']?></td>
              </tr>
              <tr>
                <td class="table-label"><p>Name of contact person <span class="sterix">*</span></p></td>
                <td><?php echo $organization['contact_person'] ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Physical and postal address<span class="sterix">*</span></p></td>
                <td><?php echo $organization['address'] ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Telephone Number <span class="sterix">*</span></p></td>
                <td><?php echo $organization['telephone_number'] ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Email Address <span class="sterix">*</span></p></td>
                <td><?php echo ""?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>All tasks of the sponsor <span class="sterix">*</span></p></td>
                <td><?php echo $organization['all_tasks']; ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Monitoring  <span class="sterix">*</span></p></td>
                <td><?php echo $organization['monitoring']; ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Regulatory (e.g. preparation of applications to CA and ethics committee) <span class="sterix">*</span></p></td>
                <td><?php echo $organization['regulatory']; ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Investigator Recruitment <span class="sterix">*</span></p></td>
                <td><?php echo $organization['investigator_recruitment']; ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>IVRS &mdash; treatment randomisation <span class="sterix">*</span></p></td>
                <td><?php echo $organization['ivrs_treatment_randomisation']; ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Data management <span class="sterix">*</span></p></td>
                <td><?php echo $organization['data_management']; ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>E-data capture  <span class="sterix">*</span></p></td>
                <td><?php echo $organization['e_data_capture']; ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>SUSAR reporting <span class="sterix">*</span></p></td>
                <td><?php echo $organization['susar_reporting']; ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Quality assurance auditing <span class="sterix">*</span></p></td>
                <td><?php echo $organization['quality_assurance_auditing']; ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Statistical analysis <span class="sterix">*</span></p></td>
                <td><?php echo $organization['statistical_analysis']; ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Medical Writing <span class="sterix">*</span></p></td>
                <td><?php echo $organization['medical_writing']; ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Other duties subcontracted <span class="sterix">*</span></p></td>
                <td><?php echo $organization['other_duties']; ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>If yes to other, please specify</p></td>
                <td><?php echo $organization['other_duties_specify']; ?></td>
              </tr>
              </tbody>
            </table>
            <hr>
             <?php } ?>
          </div>
        </div>
        <div id="tabs-7">
          <table class="table  table-condensed">
              <tbody>
            <tr>
              <td class="table-label"><h5>7.0 PRINCIPAL INCLUSION CRITERIA <span class="sterix">*</span></h5></td>
              <td><p><?php echo $application['Application']['principal_inclusion_criteria'] ; ?> </p></td>
            </tr>
            <tr>
              <td class="table-label"><h5>7.1 PRINCIPAL EXCLUSION CRITERIA <span class="sterix">*</span></h5></td>
              <td><p><?php echo $application['Application']['principal_exclusion_criteria'] ; ?> </p></td>
            </tr>
            <tr>
              <td class="table-label"><h5>7.2 PRIMARY END POINTS <span class="sterix">*</span></h5></td>
              <td><p><?php echo $application['Application']['primary_end_points'] ; ?> </p></td>
            </tr>
                </tbody>
           </table>
        </div>
        <div id="tabs-8">
          <hr>
          <h5>8.0 SCOPE OF THE TRIAL -  <span class="sterix">*</span> <small>Tick all boxes where applicable</small></h5>
          <table class="table  table-condensed">
            <tbody>
            <tr>
              <td><p class="control-nolabel"><?php echo ($application['Application']['scope_diagnosis']   ? $ichecked : $nchecked ); ?> Diagnosis</p></td>
              <td><p class="control-nolabel"><?php echo ($application['Application']['scope_prophylaxis']   ? $ichecked : $nchecked ); ?> Prophylaxis</p></td>
            </tr>
            <tr>
              <td><p class="control-nolabel"><?php echo ($application['Application']['scope_therapy']   ? $ichecked : $nchecked ); ?> Therapy</p></td>
              <td><p class="control-nolabel"><?php echo ($application['Application']['scope_safety']   ? $ichecked : $nchecked ); ?>Safety </p></td>
            </tr>
            <tr>
              <td><p class="control-nolabel"><?php echo ($application['Application']['scope_efficacy']   ? $ichecked : $nchecked ); ?>Efficacy </p></td>
              <td><p class="control-nolabel"><?php echo ($application['Application']['scope_pharmacokinetic']   ? $ichecked : $nchecked ); ?> Pharmacokinetic</p></td>
            </tr>
            <tr>
              <td><p class="control-nolabel"><?php echo ($application['Application']['scope_pharmacodynamic']   ? $ichecked : $nchecked ); ?> Pharmacodynamic</p></td>
              <td><p class="control-nolabel"><?php echo ($application['Application']['scope_bioequivalence']   ? $ichecked : $nchecked ); ?> Bioequivalence</p></td>
            </tr>
            <tr>
              <td><p class="control-nolabel"><?php echo ($application['Application']['scope_dose_response']   ? $ichecked : $nchecked ); ?> Dose Response </p></td>
              <td><p class="control-nolabel"><?php echo ($application['Application']['scope_pharmacogenetic']   ? $ichecked : $nchecked ); ?> Pharmacogenetic</p></td>
            </tr>
            <tr>
              <td><p class="control-nolabel"><?php echo ($application['Application']['scope_pharmacogenomic']   ? $ichecked : $nchecked ); ?> Pharmacogenomic</p></td>
              <td><p class="control-nolabel"><?php echo ($application['Application']['scope_pharmacoecomomic']   ? $ichecked : $nchecked ); ?> Pharmacoecomomic</p></td>
            </tr>
            <tr>
              <td><p class="control-nolabel"><?php echo ($application['Application']['scope_others']   ? $ichecked : $nchecked ); ?> Others</p></td>
              <td><p><?php echo $application['Application']['scope_others_specify']   ?> </p></td>
            </tr>
            </tbody>
          </table>
          <hr>
          <h5>8.1 TRIAL TYPE AND PHASE  <span class="sterix">*</span></h5>
          <table class="table  table-condensed">
            <tbody>
            <tr>
              <td><p class="control-nolabel"><?php echo ($application['Application']['trial_human_pharmacology']   ? $ichecked : $nchecked ); ?> Human pharmacology  (Phase I) </p>
              <p >Is it:</td>
            </tr>
            <tr>
              <td><p class="control-nolabel"><?php echo ($application['Application']['trial_administration_humans']   ? $ichecked : $nchecked ); ?> First administration to humans</p></td>
            </tr>
            <tr>
              <td><p class="control-nolabel"><?php echo ($application['Application']['trial_bioequivalence_study']   ? $ichecked : $nchecked ); ?> Bioequivalence study  </p></td>
            </tr>
            <tr>
              <td><p class="control-nolabel"><?php echo ($application['Application']['trial_other']   ? $ichecked : $nchecked ); ?> Other</p>
                <p><?php echo $application['Application']['trial_other_specify'] ; ?> </p></td>
            </tr>
            <tr>
              <td><p class="control-nolabel"><?php echo ($application['Application']['trial_therapeutic_exploratory']   ? $ichecked : $nchecked ); ?> Therapeutic exploratory  (Phase II)  </p></td>
            </tr>
            <tr>
              <td><p class="control-nolabel"><?php echo ($application['Application']['trial_therapeutic_confirmatory']   ? $ichecked : $nchecked ); ?> Therapeutic confirmatory (Phase III) </p></td>
            </tr>
            <tr>
              <td><p class="control-nolabel"><?php echo ($application['Application']['trial_therapeutic_use']   ? $ichecked : $nchecked ); ?> Therapeutic use (Phase IV) </p></td>
            </tr>
            </tbody>
          </table>
        </div>
        <div id="tabs-9">
          <h5>9.0 DESIGN OF THE TRIAL</h5>
          <hr>
          <table class="table  table-condensed">
            <tbody>
            <tr>
              <td class="table-label"><p>Controlled <span class="sterix">*</span></p></td>
              <td><p><?php echo $application['Application']['design_controlled'] ; ?> </p></td>
            </tr>
            </tbody>
          </table>
          <div class="ctr-groups">
            <p class="topper"><em class="text-success">If Yes, Specify</em></p>

            <table class="table  table-condensed">
              <tbody>
              <tr>
                <td class="table-label"><p>Randomised <span class="sterix">*</span></p></td>
                <td><p><?php echo $application['Application']['design_controlled_randomised'] ; ?> </p></td>
              </tr>
              <tr>
                <td class="table-label"><p>Open <span class="sterix">*</span></p></td>
                <td><p><?php echo $application['Application']['design_controlled_open'] ; ?> </p></td>
              </tr>
              <tr>
                <td class="table-label"><p>Single Blind  <span class="sterix">*</span></p></td>
                <td><p><?php echo $application['Application']['design_controlled_single_blind'] ; ?> </p></td>
              </tr>
              <tr>
                <td class="table-label"><p>Double Blind <span class="sterix">*</span></p></td>
                <td><p><?php echo $application['Application']['design_controlled_double_blind'] ; ?> </p></td>
              </tr>
              <tr>
                <td class="table-label"><p>Parallel group <span class="sterix">*</span></p></td>
                <td><p><?php echo $application['Application']['design_controlled_parallel_group'] ; ?> </p></td>
              </tr>
              <tr>
                <td class="table-label"><p>Cross over <span class="sterix">*</span></p></td>
                <td><p><?php echo $application['Application']['design_controlled_cross_over'] ; ?> </p></td>
              </tr>
              <tr>
                <td class="table-label"><p>Other <span class="sterix">*</span></p></td>
                <td><p><?php echo $application['Application']['design_controlled_other'] ; ?> </p></td>
              </tr>
              <tr>
                <td class="table-label"><p>If yes to other, specify </p></td>
                <td><p><?php echo $application['Application']['design_controlled_specify'] ; ?> </p></td>
              </tr>
              <tr>
                <td class="table-label"><p>If controlled, specify the comparator</p></td>
                <td><p><?php echo $application['Application']['design_controlled_comparator'] ; ?> </p></td>
              </tr>
              <tr>
                <td class="table-label"><p>Other medicinal product(s) </p></td>
                <td><p><?php echo $application['Application']['design_controlled_other_medicinal'] ; ?> </p></td>
              </tr>
              <tr>
                <td class="table-label"><p>Placebo</p></td>
                <td><p><?php echo $application['Application']['design_controlled_placebo'] ; ?> </p></td>
              </tr>
              <tr>
                <td class="table-label"><p>Other</p></td>
                <td><p><?php echo $application['Application']['design_controlled_medicinal_other'] ; ?> </p></td>
              </tr>
              <tr>
                <td class="table-label"><p>If yes to other, specify</p></td>
                <td><p><?php echo $application['Application']['design_controlled_medicinal_specify'] ; ?> </p></td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div id="tabs-10">
          <hr>
          <h5>10.0 INFORMATION ON PLACEBO (<small>if relevant; repeat as necessary - <button type="button" class="btn-mini" id="addPlacebo" title="add placebo">Add</button></small>) </h5>
          <table class="table  table-condensed">
            <tbody>
            <tr>
              <td class="table-label required"><p>Is there a placebo?  <span class="sterix">*</span></p></td>
              <td><p><?php echo $application['Application']['placebo_present'] ; ?> </p></td>
            </tr>
            </tbody>
          </table>
          <div class="ctr-groups">
            <?php foreach ($application['Placebo'] as $placebo) { ?>
            <span class="badge badge-info"><?php echo $placebo['id']?></span>
            <table class="table  table-condensed">
              <tbody>
              <tr>
                <td class="table-label required"><p>Pharmaceutical form </p></td>
                <td><?php echo $placebo['pharmaceutical_form']?></td>
              </tr>
              <tr>
                <td class="table-label"><p>Route of administration </p></td>
                <td><?php echo $placebo['route_of_administration'] ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Composition, apart from active substance(s) </p></td>
                <td><?php echo $placebo['composition'] ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>Is it otherwise identical to the INDP?</p></td>
                <td><?php echo $placebo['identical_indp'] ?></td>
              </tr>
              <tr>
                <td class="table-label required"><p>If not, specify major ingredients</p></td>
                <td><?php echo $placebo['major_ingredients']; ?></td>
              </tr>
              </tbody>
            </table>
            <hr>
            <?php } ?>
          </div>
        </div>
        <div id="tabs-11">
          <h5>11.0 OTHER DETAILS</h5>
          <hr>
          <table class="table table-condensed">
            <thead>
            <tr class="control-nolabel"><th><h5> 11.1 If the trial is to be conducted in Kenya and not in the host country of the applicant / sponsor, provide an explanation <span class="sterix">*</span></h5></th></tr>
            </thead>
            <tbody>
            <tr><td><?php echo $application['Application']['other_details_explanation'] ?></td> </tr>
            </tbody>
          </table>
          <table class="table table-condensed">
            <thead>
            <tr class="control-nolabel"><th><h5> 11.2 Estimated duration of trial <span class="sterix">*</span></h5></th></tr>
            </thead>
            <tbody>
            <tr><td><?php echo $application['Application']['estimated_duration'] ?></td>  </tr>
            </tbody>
          </table>
          <table class="table table-condensed">
            <thead>
            <tr class="control-nolabel"><th><h5> 11.3 Name other Regulatory Authorities to  which applications to do this trial have been submitted, but approval has not yet been granted. Include date(s) of application: <span class="sterix">*</span></h5></th></tr>
            </thead>
            <tbody>
            <tr><td><?php echo $application['Application']['other_details_regulatory_notapproved'] ?></td>  </tr>
            </tbody>
          </table>
          <table class="table table-condensed">
            <thead>
            <tr class="control-nolabel"><th><h5> 11.4 Name other Regulatory Authorities which have approved this trial, date(s) of approval and number of sites per country.  <span class="sterix">*</span></h5></th></tr>
            </thead>
            <tbody>
            <tr><td><?php echo $application['Application']['other_details_regulatory_approved'] ?></td> </tr>
            </tbody>
          </table>
          <table class="table table-condensed">
            <thead>
            <tr class="control-nolabel"><th><h5> 11.5 if applicable, name other Regulatory  Authorities or Ethics Committees which have rejected this trial and give reasons for rejection:<span class="sterix">*</span></h5></th></tr>
            </thead>
            <tbody>
            <tr><td><?php echo $application['Application']['other_details_regulatory_rejected'] ?></td> </tr>
            </tbody>
          </table>
          <table class="table table-condensed">
            <thead>
            <tr class="control-nolabel"><th><h5> 11.6 If applicable, details of and reasons for this trial having been halted at any stage by other Regulatory Authorities: <span class="sterix">*</span></h5></th></tr>
            </thead>
            <tbody>
            <tr><td><?php echo $application['Application']['other_details_regulatory_halted'] ?></td> </tr>
            </tbody>
          </table>
        </div>

        <div id="tabs-12">
          <h5>CHECKLIST <span class="sterix">*</span></h5>
          <table class="table  table-condensed">
            <tbody>
            <tr>
              <td style="width:5%;"><p  class="control-checklabel">1. </p></td>
              <td><p class="control-nolabel required"><?php echo ($application['Application']['applicant_covering_letter']   ? $ichecked : $nchecked ); ?> Cover Letter <span class="sterix">*</span>
                <?php
                  if (!empty($application['CoverLetter'][0]['id']) && !empty($application['CoverLetter'][0]['basename'])) {
                  echo $this->Html->link(__($application['CoverLetter'][0]['basename']),
                    array('controller' => 'attachments', 'admin' => false, 'action' => 'download', $application['CoverLetter'][0]['id']),
                    array('class' => 'checkcontrols btn btn-info'));
                    }
                ?>
                </p></td>
            </tr>
            <tr>
              <td><p  class="control-checklabel">2. </p></td>
              <td><p class="control-nolabel required"><?php echo ($application['Application']['applicant_protocol']   ? $ichecked : $nchecked ); ?> Protocol <span class="sterix">*</span>
                <?php
                  if (!empty($application['Protocol'][0]['id']) && !empty($application['Protocol'][0]['basename'])) {
                  echo $this->Html->link(__($application['Protocol'][0]['basename']),
                    array('controller' => 'attachments', 'admin' => false, 'action' => 'download', $application['Protocol'][0]['id']),
                    array('class' => 'checkcontrols btn btn-info'));
                    }
                ?>
                </p></td>
            </tr>
            <tr>
              <td><p  class="control-checklabel">3. </p></td>
              <td><p class="control-nolabel required"><?php echo ($application['Application']['applicant_patient_information']   ? $ichecked : $nchecked ); ?> Patient Information leaflet and Informed consent form <span class="sterix">*</span>
                <?php
                  if (!empty($application['PatientLeaflet'][0]['id']) && !empty($application['PatientLeaflet'][0]['basename'])) {
                  echo $this->Html->link(__($application['PatientLeaflet'][0]['basename']),
                    array('controller' => 'attachments', 'admin' => false, 'action' => 'download', $application['PatientLeaflet'][0]['id']),
                    array('class' => 'checkcontrols btn btn-info'));
                    }
                ?>
                </p></td>
            </tr>
            <tr>
              <td><p  class="control-checklabel">4. </p></td>
              <td><p class="control-nolabel required"> <?php echo ($application['Application']['applicant_investigators_brochure']   ? $ichecked : $nchecked ); ?> Investigators Brochure/Package inserts or Investigational Medicinal Product Dossier (IMPD) <span class="sterix">*</span>
                <?php
                  if (!empty($application['Brochure'][0]['id']) && !empty($application['Brochure'][0]['basename'])) {
                  echo $this->Html->link(__($application['Brochure'][0]['basename']),
                    array('controller' => 'attachments', 'admin' => false, 'action' => 'download', $application['Brochure'][0]['id']),
                    array('class' => 'checkcontrols btn btn-info'));
                    }
                ?>
                </p></td>
            </tr>
            <tr>
              <td><p  class="control-checklabel">5. </p></td>
              <td><p class="control-nolabel required"><?php echo ($application['Application']['applicant_gmp_certificate']   ? $ichecked : $nchecked ); ?> GMP certificate of the investigational product <span class="sterix">*</span>
                <?php
                  if (!empty($application['GmpCertificate'][0]['id']) && !empty($application['GmpCertificate'][0]['basename'])) {
                  echo $this->Html->link(__($application['GmpCertificate'][0]['basename']),
                    array('controller' => 'attachments', 'admin' => false, 'action' => 'download', $application['GmpCertificate'][0]['id']),
                    array('class' => 'checkcontrols btn btn-info'));
                    }
                ?>
                </p></td>
            </tr>
            <tr>
              <td><p  class="control-checklabel">6. </p></td>
              <td><p class="control-nolabel required"><?php echo ($application['Application']['applicant_investigators_cv']   ? $ichecked : $nchecked ); ?> Signed investigator(s) CV(s) <span class="sterix">*</span>
                <?php
                  if (!empty($application['Cv'][0]['id']) && !empty($application['Cv'][0]['basename'])) {
                  echo $this->Html->link(__($application['Cv'][0]['basename']),
                    array('controller' => 'attachments', 'admin' => false, 'action' => 'download', $application['Cv'][0]['id']),
                    array('class' => 'checkcontrols btn btn-info'));
                    }
                ?>
                </p></td>
            </tr>
            <tr>
              <td><p  class="control-checklabel">7. </p></td>
              <td><p class="control-nolabel required"><?php echo ($application['Application']['applicant_financial_declaration']   ? $ichecked : $nchecked ); ?> Financial declaration by Sponsor and/or PI <span class="sterix">*</span>
                <?php
                  if (!empty($application['Finance'][0]['id']) && !empty($application['Finance'][0]['basename'])) {
                  echo $this->Html->link(__($application['Finance'][0]['basename']),
                    array('controller' => 'attachments', 'admin' => false, 'action' => 'download', $application['Finance'][0]['id']),
                    array('class' => 'checkcontrols btn btn-info'));
                    }
                ?>
                </p></td>
            </tr>
            <tr>
              <td><p  class="control-checklabel">8. </p></td>
              <td><p class="control-nolabel required"><?php echo ($application['Application']['applicant_signed_declaration']   ? $ichecked : $nchecked ); ?> Signed Declaration by Sponsor or Principal investigator.  <span class="sterix">*</span>
                <?php
                  if (!empty($application['Declaration'][0]['id']) && !empty($application['Declaration'][0]['basename'])) {
                  echo $this->Html->link(__($application['Declaration'][0]['basename']),
                    array('controller' => 'attachments', 'admin' => false, 'action' => 'download', $application['Declaration'][0]['id']),
                    array('class' => 'checkcontrols btn btn-info'));
                    }
                ?>
                </p></td>
            </tr>
            <tr>
              <td><p  class="control-checklabel">9. </p></td>
              <td><p class="control-nolabel required"><?php echo ($application['Application']['applicant_indemnity_cover']   ? $ichecked : $nchecked ); ?> Indemnity cover and Insurance Certificate for the participants <span class="sterix">*</span>
                <?php
                  if (!empty($application['IndemnityCover'][0]['id']) && !empty($application['IndemnityCover'][0]['basename'])) {
                  echo $this->Html->link(__($application['IndemnityCover'][0]['basename']),
                    array('controller' => 'attachments', 'admin' => false, 'action' => 'download', $application['IndemnityCover'][0]['id']),
                    array('class' => 'checkcontrols btn btn-info'));
                    }
                ?>
                </p></td>
            </tr>
            <tr>
              <td><p  class="control-checklabel">10. </p></td>
              <td><p class="control-nolabel required"><?php echo ($application['Application']['applicant_opinion_letter']   ? $ichecked : $nchecked ); ?> Copy of favourable opinion letter from the local Institutional Review Board (IRB)
                    and Ethics committee. <span class="sterix">*</span>
                <?php
                  if (!empty($application['OpinionLetter'][0]['id']) && !empty($application['OpinionLetter'][0]['basename'])) {
                  echo $this->Html->link(__($application['OpinionLetter'][0]['basename']),
                    array('controller' => 'attachments', 'admin' => false, 'action' => 'download', $application['OpinionLetter'][0]['id']),
                    array('class' => 'checkcontrols btn btn-info'));
                    }
                ?>
                </p></td>
            </tr>
            <tr>
              <td><p  class="control-checklabel">11. </p></td>
              <td><p class="control-nolabel"><?php echo ($application['Application']['applicant_approval_letter']   ? $ichecked : $nchecked ); ?> Copy of approval letter(s) from collaborating institutions or other regulatory authorities, if applicable
                <?php
                  if (!empty($application['ApprovalLetter'][0]['id']) && !empty($application['ApprovalLetter'][0]['basename'])) {
                  echo $this->Html->link(__($application['ApprovalLetter'][0]['basename']),
                    array('controller' => 'attachments', 'admin' => false, 'action' => 'download', $application['ApprovalLetter'][0]['id']),
                    array('class' => 'checkcontrols btn btn-info'));
                    }
                ?>
                </p></td>
            </tr>
            <tr>
              <td><p  class="control-checklabel">12. </p></td>
              <td><p class="control-nolabel required"><?php echo ($application['Application']['applicant_statement']   ? $ichecked : $nchecked ); ?> A signed statement by the applicant indicating that all information contained in, or
                    referenced by, the application is complete and accurate and is not false or misleading. <span class="sterix">*</span>
                <?php
                  if (!empty($application['Statement'][0]['id']) && !empty($application['Statement'][0]['basename'])) {
                  echo $this->Html->link(__($application['Statement'][0]['basename']),
                    array('controller' => 'attachments', 'admin' => false, 'action' => 'download', $application['Statement'][0]['id']),
                    array('class' => 'checkcontrols btn btn-info'));
                    }
                ?>
                </p></td>
            </tr>
            <tr>
              <td><p  class="control-checklabel">13. </p></td>
              <td><p class="control-nolabel"><?php echo ($application['Application']['applicant_participating_countries']   ? $ichecked : $nchecked ); ?> Where the trial is part of an international study, sufficient information regarding the other participating countries and the scope of the study in these countries.
                <?php
                  if (!empty($application['ParticipatingStudy'][0]['id']) && !empty($application['ParticipatingStudy'][0]['basename'])) {
                  echo $this->Html->link(__($application['ParticipatingStudy'][0]['basename']),
                    array('controller' => 'attachments', 'admin' => false, 'action' => 'download', $application['ParticipatingStudy'][0]['id']),
                    array('class' => 'checkcontrols btn btn-info'));
                    }
                ?>
                </p></td>
            </tr>
            <tr>
              <td><p  class="control-checklabel">14. </p></td>
              <td><p class="control-nolabel"><?php echo ($application['Application']['applicant_addendum']   ? $ichecked : $nchecked ); ?> For multicentre/multi-site studies, an addendum for each of the sites should be provided upon initial application.
                <?php
                  if (!empty($application['Addendum'][0]['id']) && !empty($application['Addendum'][0]['basename'])) {
                  echo $this->Html->link(__($application['Addendum'][0]['basename']),
                    array('controller' => 'attachments', 'admin' => false, 'action' => 'download', $application['Addendum'][0]['id']),
                    array('class' => 'checkcontrols btn btn-info'));
                    }
                ?>
                </p></td>
            </tr>
            <tr>
              <td><p  class="control-checklabel">15. </p></td>
              <td><p class="control-nolabel required"><?php echo ($application['Application']['applicant_fees']   ? $ichecked : $nchecked ); ?> Fees - <small>A receipt to the sum of US$ 1000.00 (or equivalent in Kenya Shillings) per
              proposal towards payment of application fees (paid at the PPB&rsquo;s accounts office). </small> <span class="sterix">*</span>
                <?php
                  if (!empty($application['Fee'][0]['id']) && !empty($application['Fee'][0]['basename'])) {
                  echo $this->Html->link(__($application['Fee'][0]['basename']),
                    array('controller' => 'attachments', 'admin' => false, 'action' => 'download', $application['Fee'][0]['id']),
                    array('class' => 'checkcontrols btn btn-info'));
                    }
                ?>
                </p></td>
            </tr>
            </tbody>
          </table>
        </div>
        <div id="tabs-13">
          <h5>DECLARATION BY APPLICANT</h5>
          <hr>
          <p>We, the undersigned have submitted all requested and required documentation, and have disclosed all
            information which may influence the approval of this application. </p>

          <p>We, the undersigned, agree to ensure that if the above-said clinical trial is approved, it will be conducted
            according to the submitted protocol and South African legal, ethical and regulatory requirements. </p>

          <table class="table  table-condensed">
            <tbody>
            <tr>
              <td class="table-label required"><p>Applicant (local contact) <span class="sterix">*</span></p></td>
              <td><p><?php echo $application['Application']['declaration_applicant'] ; ?> </p></td>
            </tr>
            <tr>
              <td class="table-label required"><p> </p></td>
              <td><p><?php echo $application['Application']['declaration_date1'] ; ?> </p></td>
            </tr>
            <tr>
              <td class="table-label required"><p>National Principal Investigator / National Co-ordinator / Other (state designation) </p></td>
              <td><p><?php echo $application['Application']['declaration_principal_investigator'] ; ?> </p></td>
            </tr>
            <tr>
              <td class="table-label required"><p></p></td>
              <td><p><?php echo $application['Application']['declaration_date2'] ; ?> </p></td>
            </tr>
            </tbody>
          </table>
        </div>
        <div id="tabs-14">
          <h5>Do you have files that you would like to attach? click on the button to add them:
          <button type="button" class="btn-mini" id="addAttachment" title="click to add row"><i class="icon-plus"></i></button>
            </h5>
            <p class="muted">Files may include pictures, scanned documents, pdf, word documents<p>
                <table id="buildattachmentsform"  class="table table-bordered  table-condensed table-striped">
              <thead>
                <tr id="attachmentsTableHeader">
                <th>#</th>
                <th>File</th>
                <th>Description of contents</th>
                <th>  </th>
                </tr>
              </thead>
              <tbody>
              <?php
                // pr($application['Attachment']);
                for ($i = 0; $i <= count($application['Attachment'])-1; $i++) {
              ?>
                <tr>
                <td><?php echo $i+1; ?></td>
                <td><?php
                  echo $this->Html->link(__($application['Attachment'][$i]['basename']),
                    array('controller' => 'attachments', 'admin' => false, 'action' => 'download',
                      $application['Attachment'][$i]['id']), array('class' => 'btn btn-info'));

                  ?>
                </td>
                <td>
                  <?php
                    echo $application['Attachment'][$i]['description'];
                  ?>
                </td>
                <td>

                </td>
                </tr>
                <?php } ; ?>
              </tbody>
            </table>
          <hr>
        </div>
      </div>

  </div>

</div>

<?php  echo $this->fetch('endjs');  ?>
