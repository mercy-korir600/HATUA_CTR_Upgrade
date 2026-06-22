<?php
App::uses('AppController', 'Controller');
/**
 * PreviousDates Controller
 *
 * @property PreviousDate $PreviousDate
 */
class ReportsController extends AppController
{
  public $uses = array('SiteInspection', 'Application', 'Sae', 'Deviation');

  public $components = array('Search.Prg');


  /**
   * site inspections per month method
   *
   * @return void
   */
  public function si_per_month()
  {

    $criteria = array();
    if (!empty($this->request->data['Application']['start_date']) && !empty($this->request->data['Application']['end_date']))
      $criteria['SiteInspection.created between ? and ?'] = array(date('Y-m-d', strtotime($this->request->data['Application']['start_date'])), date('Y-m-d', strtotime($this->request->data['Application']['end_date'])));

    $data = $this->SiteInspection->find('all', array(
      'fields' => array('date_format(SiteInspection.created,"%M") as name', 'COUNT(*) as y'),
      'contain' => array(),
      'conditions' => $criteria,
      'group' => 'date_format(SiteInspection.created,"%M")'
    ));
    // $data = Hash::extract($data, '{n}.{n}');
    $this->set(compact('data'));
    $this->set('_serialize', 'data');
    $this->render('si_per_month');
  }
  public function inspector_si_per_month()
  {
    $this->si_per_month();
  }
  public function manager_si_per_month()
  {
    $this->si_per_month();
  }

  public function sae_per_month()
  {

    $criteria = array();
    if (!empty($this->request->data['Application']['start_date']) && !empty($this->request->data['Application']['end_date']))
      $criteria['Sae.created between ? and ?'] = array(date('Y-m-d', strtotime($this->request->data['Application']['start_date'])), date('Y-m-d', strtotime($this->request->data['Application']['end_date'])));

    $data = $this->Sae->find('all', array(
      'fields' => array('date_format(Sae.created,"%M") as name', 'COUNT(*) as y'),
      'contain' => array(),
      'conditions' => $criteria,
      'group' => 'date_format(Sae.created,"%M")'
    ));
    $this->set(compact('data'));
    $this->set('_serialize', 'data');
    $this->render('sae_per_month');
  }
  public function inspector_sae_per_month()
  {
    $this->sae_per_month();
  }
  public function manager_sae_per_month()
  {
    $this->sae_per_month();
  }

  public function dev_per_month()
  {

    $criteria = array();
    if (!empty($this->request->data['Application']['start_date']) && !empty($this->request->data['Application']['end_date']))
      $criteria['Deviation.created between ? and ?'] = array(date('Y-m-d', strtotime($this->request->data['Application']['start_date'])), date('Y-m-d', strtotime($this->request->data['Application']['end_date'])));
    $data = $this->Deviation->find('all', array(
      'fields' => array('date_format(Deviation.created,"%M") as name', 'COUNT(*) as y'),
      'contain' => array(),
      'conditions' => $criteria,
      'group' => 'date_format(Deviation.created,"%M")'
    ));
    // $data = Hash::extract($data, '{n}.{n}');
    $this->set(compact('data'));
    $this->set('_serialize', 'data');
    $this->render('dev_per_month');
  }
  public function inspector_dev_per_month()
  {
    $this->dev_per_month();
  }
  public function manager_dev_per_month()
  {
    $this->dev_per_month();
  }

  public function sae_by_type()
  {

    $criteria = array();
    if (!empty($this->request->data['Application']['start_date']) && !empty($this->request->data['Application']['end_date']))
      $criteria['Sae.created between ? and ?'] = array(date('Y-m-d', strtotime($this->request->data['Application']['start_date'])), date('Y-m-d', strtotime($this->request->data['Application']['end_date'])));

    $criteria['Sae.approved'] = array(1, 2);
    $data = $this->Sae->find('all', array(
      'fields' => array('Sae.form_type', 'Application.protocol_no', 'COUNT(*) as cnt'),
      'contain' => array('Application'),
      'conditions' => $criteria,
      'group' => array('Sae.form_type', 'Sae.id', 'Application.protocol_no'),
      'having' => array('COUNT(*) >' => 0),
    ));
    // debug($data);
    // exit;

    $this->set(compact('data'));
    $this->set('_serialize', 'data');
    $this->render('sae_by_type');
  }
  public function inspector_sae_by_type()
  {
    $this->sae_by_type();
  }
  public function manager_sae_by_type()
  {
    $this->sae_by_type();
  }

  public function dev_by_study()
  {
    $criteria = array();
    if (!empty($this->request->data['Application']['start_date']) && !empty($this->request->data['Application']['end_date']))
      $criteria['Deviation.created between ? and ?'] = array(date('Y-m-d', strtotime($this->request->data['Application']['start_date'])), date('Y-m-d', strtotime($this->request->data['Application']['end_date'])));

    $criteria['Deviation.status'] = 'Submitted';
    $data = $this->Deviation->find('all', array(
      'fields' => array('Deviation.deviation_type', 'Application.protocol_no', 'COUNT(*) as cnt'),
      'contain' => array('Application'),
      'conditions' => $criteria,
      'group' => array('Deviation.deviation_type', 'Deviation.id', 'Application.protocol_no'),
      'having' => array('COUNT(*) >' => 0),
    ));


    
    $criteria = array_merge($criteria, array('Deviation.deviation_type_dev IS NOT NULL'));
    $data1 = $this->Deviation->find('all', array(
      'fields' => array('Deviation.deviation_type_dev ', 'Application.protocol_no', 'COUNT(*) as cnt'),
      'contain' => array('Application'),
      'conditions' => $criteria,
      'group' => array('Deviation.deviation_type_dev', 'Deviation.id', 'Application.protocol_no'),
      'having' => array('COUNT(*) >' => 0),
    ));
    // debug($data1);
    // exit;
    $this->set(compact('data'));
    $this->set(compact('data1'));
    $this->set('_serialize', 'data','data1');
    $this->render('dev_by_study');
  }
  public function inspector_dev_by_study()
  {
    $this->dev_by_study();
  }
  public function manager_dev_by_study()
  {
    $this->dev_by_study();
  }

  public function manager_protocols_by_distribution()
  {
    $this->protocols_by_distribution();
  }

  public function manager_protocols_by_placebo()
  {
    $this->protocols_by_placebo();
  }

  public function protocols_by_placebo()
  {
    $criteria = array();
    if (!empty($this->request->data['Application']['start_date']) && !empty($this->request->data['Application']['end_date']))
      $criteria['Application.created between ? and ?'] = array(date('Y-m-d', strtotime($this->request->data['Application']['start_date'])), date('Y-m-d', strtotime($this->request->data['Application']['end_date'])));

    $criteria['Application.submitted'] = 1;

    $data = $this->Application->find('all', array(
      'fields' => array('IF(Application.placebo_present IS NULL or Application.placebo_present = "", "No", Application.placebo_present) as placebo_present', 'COUNT(*) as cnt'),
      'contain' => array(), 'recursive' => -1,
      'conditions' => $criteria,
      'group' => array('IF(Application.placebo_present IS NULL or Application.placebo_present = "", "No", Application.placebo_present)'),
      'having' => array('COUNT(*) >' => 0),
    ));

    $this->set(compact('data'));
    $this->set('_serialize', 'data');
    $this->render('protocols_by_placebo');
  }
  public function manager_protocols_by_design_type()
  {
    $this->protocols_by_design_type();
  }
  public function protocols_by_design_type()
  {
    $criteria = array();
    if (!empty($this->request->data['Application']['start_date']) && !empty($this->request->data['Application']['end_date']))
      $criteria['Application.created between ? and ?'] = array(date('Y-m-d', strtotime($this->request->data['Application']['start_date'])), date('Y-m-d', strtotime($this->request->data['Application']['end_date'])));

    $criteria['Application.submitted'] = 1;


    $data = $this->Application->find('all', array(
      'fields' => array(
        '((case 
            when Application.design_controlled = "No" then "Uncontrolled" 
            when Application.design_controlled_randomised = "Yes" then "Randomised"  
            when Application.design_controlled_open = "Yes" then "Open"
            when Application.design_controlled_single_blind = "Yes" then "Single Blind"
            when Application.design_controlled_double_blind = "Yes" then "Double Blind"
            when Application.design_controlled_parallel_group = "Yes" then "Parallel group" 
            when Application.design_controlled_cross_over = "Yes" then "Cross over" 
            else "Unk" 
        end)) AS design_controlled',
        'COUNT(*) as cnt'
      ),
      'contain' => array(),
      'conditions' => $criteria,
      'group' => array(
        '((case 
            when Application.design_controlled = "No" then "Uncontrolled" 
            when Application.design_controlled_randomised = "Yes" then "Randomised"   
            when Application.design_controlled_open = "Yes" then "Open"
             when Application.design_controlled_single_blind = "Yes" then "Single Blind"
             when Application.design_controlled_double_blind = "Yes" then "Double Blind"
             when Application.design_controlled_parallel_group = "Yes" then "Parallel group"   
             when Application.design_controlled_cross_over = "Yes" then "Cross over"   
            else "Unk" 
        end))'
      ),
      'having' => array('COUNT(*) >' => 0),
    ));


    $this->set(compact('data'));
    $this->set('_serialize', 'data');

    $this->render('protocols_by_design_type');
  }
  public function manager_protocols_by_clinical_trial()
  {
    $this->protocols_by_clinical_trial();
  }
  public function protocols_by_clinical_trial()
  {
    $criteria = array();
    if (!empty($this->request->data['Application']['start_date']) && !empty($this->request->data['Application']['end_date']))
      $criteria['Application.created between ? and ?'] = array(date('Y-m-d', strtotime($this->request->data['Application']['start_date'])), date('Y-m-d', strtotime($this->request->data['Application']['end_date'])));

    $criteria['Application.approved'] = array(1, 2);
    $data = $this->Application->find('all', array(
      'fields' => array('((case when Application.scope_diagnosis then "Diagnosis" 
                                      when Application.scope_prophylaxis then "Prophylaxis"  
                                      when Application.scope_therapy then "Therapy"  
                                      when Application.scope_safety then "Safety"  
                                      when Application.scope_efficacy then "Efficacy"  
                                      when Application.scope_pharmacokinetic then "Pharmacokinetic"  
                                      when Application.scope_pharmacodynamic then "Pharmacodynamic"  
                                      else "Unk" end)) AS TrialPhase', 'COUNT(*) as cnt'),
      'contain' => array(),
      'conditions' => $criteria,
      'group' => array('((case when Application.scope_diagnosis then "Diagnosis" 
                                      when Application.scope_prophylaxis then "Prophylaxis"   
                                       when Application.scope_therapy then "Therapy"  
                                      when Application.scope_safety then "Safety"  
                                      when Application.scope_efficacy then "Efficacy"  
                                      when Application.scope_pharmacokinetic then "Pharmacokinetic"  
                                      when Application.scope_pharmacodynamic then "Pharmacodynamic"  
                                      else "Unk" end))'),
      'having' => array('COUNT(*) >' => 0),
    ));


    $this->set(compact('data'));
    $this->set('_serialize', 'data');

    $this->render('protocols_by_clinical_trial');
  }

  public function protocols_by_distribution()
  {
    $criteria = array();
    $criteria['Application.approved'] = array(1, 2);
    if (!empty($this->request->data['Application']['start_date']) && !empty($this->request->data['Application']['end_date']))
      $criteria['Application.created between ? and ?'] = array(date('Y-m-d', strtotime($this->request->data['Application']['start_date'])), date('Y-m-d', strtotime($this->request->data['Application']['end_date'])));

    $data = $this->Application->find('all', array(
      'fields' => array(
        '((case 
              when Application.gender_male = 1 AND Application.gender_female = 0 then "Male" 
              when Application.gender_female = 1 AND Application.gender_male = 0 then "Female"  
              when Application.gender_male = 1 AND Application.gender_female = 1 then "Male & Female"
              else "Unk" 
          end)) AS TrialPhase',
        'COUNT(*) as cnt'
      ),
      'contain' => array(),
      'conditions' => $criteria,
      'group' => array(
        '((case 
              when Application.gender_male = 1 AND Application.gender_female = 0 then "Male" 
              when Application.gender_female = 1 AND Application.gender_male = 0 then "Female"  
              when Application.gender_male = 1 AND Application.gender_female = 1 then "Male & Female"
              else "Unk" 
          end))'
      ),
      'having' => array('COUNT(*) >' => 0),
    ));


    $this->set(compact('data'));
    $this->set('_serialize', 'data');

    $this->render('protocols_by_distribution');
  }

  public function protocols_by_status()
  {

    $criteria = array();
    $criteria['Application.approved'] = array(1, 2); 
    $criteria = array_merge($criteria, array('Application.trial_status_id IS NOT NULL'));
    if (!empty($this->request->data['Application']['start_date']) && !empty($this->request->data['Application']['end_date']))
      $criteria['Application.date_submitted between ? and ?'] = array(date('Y-m-d', strtotime($this->request->data['Application']['start_date'])), date('Y-m-d', strtotime($this->request->data['Application']['end_date'])));
    $data = $this->Application->find('all', array(
      'fields' => array('TrialStatus.name', 'COUNT(*) as cnt'),
      'contain' => array('TrialStatus'),
      'conditions' => $criteria,
      'group' => array('TrialStatus.name', 'TrialStatus.id'),
      'having' => array('COUNT(*) >' => 0),
    ));

    $this->set(compact('data'));
    $this->set('_serialize', 'data');
    $this->render('protocols_by_status');
  }
  public function inspector_protocols_by_status()
  {
    $this->protocols_by_status();
  }
  public function manager_protocols_by_status()
  {
    $this->protocols_by_status();
  }

  public function protocols_by_phase()
  {

    $criteria = array(); 
    $criteria['Application.approved'] = array(1, 2);
    if (!empty($this->request->data['Application']['start_date']) && !empty($this->request->data['Application']['end_date']))
      $criteria['Application.date_submitted between ? and ?'] = array(date('Y-m-d', strtotime($this->request->data['Application']['start_date'])), date('Y-m-d', strtotime($this->request->data['Application']['end_date'])));

    $data = $this->Application->find('all', array(
      'fields' => array('((case when Application.trial_human_pharmacology then "Phase I" 
                                      when Application.trial_therapeutic_exploratory then "Phase II" 
                                      when Application.trial_therapeutic_confirmatory then "Phase III" 
                                      when Application.trial_therapeutic_use then "Phase IV" 
                                      else "Unk" end)) AS TrialPhase', 'COUNT(*) as cnt'),
      'contain' => array(),
      'conditions' => $criteria, 
      'group' => array('((case when Application.trial_human_pharmacology then "Phase I" 
                                      when Application.trial_therapeutic_exploratory then "Phase II" 
                                      when Application.trial_therapeutic_confirmatory then "Phase III" 
                                      when Application.trial_therapeutic_use then "Phase IV" 
                                      else "Unk" end))'),
      'having' => array('COUNT(*) >' => 0),
    ));

    $this->set(compact('data'));
    $this->set('_serialize', 'data');
    $this->render('protocols_by_phase');
  }
  public function inspector_protocols_by_phase()
  {
    $this->protocols_by_phase();
  }
  public function manager_protocols_by_phase()
  {
    $this->protocols_by_phase();
  }

  /**
   * view method
   *
   * @throws NotFoundException
   * @param string $id
   * @return void
   */
  public function view($id = null)
  {
    $this->PreviousDate->id = $id;
    if (!$this->PreviousDate->exists()) {
      throw new NotFoundException(__('Invalid previous date'));
    }
    $this->set('previousDate', $this->PreviousDate->read(null, $id));
  }

  /**
   * add method
   *
   * @return void
   */
  public function add()
  {
    if ($this->request->is('post')) {
      $this->PreviousDate->create();
      if ($this->PreviousDate->save($this->request->data)) {
        $this->Session->setFlash(__('The previous date has been saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The previous date could not be saved. Please, try again.'));
      }
    }
    $applications = $this->PreviousDate->Application->find('list');
    $this->set(compact('applications'));
  }

  /**
   * edit method
   *
   * @throws NotFoundException
   * @param string $id
   * @return void
   */
  public function edit($id = null)
  {
    $this->PreviousDate->id = $id;
    if (!$this->PreviousDate->exists()) {
      throw new NotFoundException(__('Invalid previous date'));
    }
    if ($this->request->is('post') || $this->request->is('put')) {
      if ($this->PreviousDate->save($this->request->data)) {
        $this->Session->setFlash(__('The previous date has been saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The previous date could not be saved. Please, try again.'));
      }
    } else {
      $this->request->data = $this->PreviousDate->read(null, $id);
    }
    $applications = $this->PreviousDate->Application->find('list');
    $this->set(compact('applications'));
  }

  /**
   * delete method
   *
   * @throws MethodNotAllowedException
   * @throws NotFoundException
   * @param string $id
   * @return void
   */
  public function delete($id = null)
  {
    if (!$this->request->is('post')) {
      throw new MethodNotAllowedException();
    }
    $this->PreviousDate->id = $id;
    if (!$this->PreviousDate->exists()) {
      throw new NotFoundException(__('Invalid previous date'));
    }
    if (!$this->PreviousDate->isOwnedBy($id, $this->Auth->user('id'))) {
      $this->set('message', 'You do not have permission to access this resource');
      $this->set('_serialize', 'message');
    } else {
      if ($this->PreviousDate->delete()) {
        $this->set('message', 'Previous ECCT date deleted');
        $this->set('_serialize', 'message');
      } else {
        $this->set('message', 'Previous date was not deleted');
        $this->set('_serialize', 'message');
      }
    }
  }
  
  /**
     * Tally number of applications per year and calculate average number per year
     */
    public function protocols_per_year()
    {
        $criteria = array();
        $criteria['Application.deleted'] = 0; // Filter out deleted applications

        // 1. Detect user role (Group 2 = Manager, Group 1 = Admin)
        $group_id = $this->Auth->user('group_id');
        $isManager = ($group_id == 2);

        // 2. Read the filter target (submitted, unsubmitted, all)
        // If the user is a manager, force the target to 'submitted'.
        if ($isManager) {
            $filter = 'submitted';
        } else {
            $filter = isset($this->request->data['Application']['filter_target'])
                ? $this->request->data['Application']['filter_target']
                : 'all';
        }

        // 3. Add conditions based on the filter target
        if ($filter === 'submitted') {
            $criteria['Application.submitted'] = 1;
        } elseif ($filter === 'unsubmitted') {
            $criteria['Application.submitted'] = 0;
        } // For 'all', we don't append a condition on Application.submitted so both are returned

        // Apply date filter if provided
        if (
            !empty($this->request->data['Application']['start_date']) &&
            !empty($this->request->data['Application']['end_date'])
        ) {
            $criteria['Application.created BETWEEN ? AND ?'] = array(
                date('Y-m-d', strtotime($this->request->data['Application']['start_date'])),
                date('Y-m-d', strtotime($this->request->data['Application']['end_date']))
            );
        }

        // Fetch yearly application counts
        $data = $this->Application->find('all', array(
            'fields' => array(
                'YEAR(Application.created) AS year',
                'COUNT(*) AS cnt'
            ),
            'conditions' => $criteria,
            'group' => array('YEAR(Application.created)'),
            'order' => array('year DESC'),
            'recursive' => -1
        ));

        // Calculate average number of applications per year
        $totalApplications = 0;
        $totalYears = count($data);

        foreach ($data as $row) {
            $totalApplications += $row[0]['cnt'];
        }

        $average = ($totalYears > 0) ? ($totalApplications / $totalYears) : 0;

        // Pass variables to view
        $this->set(compact('data', 'average', 'filter', 'isManager'));
        $this->set('_serialize', array('data', 'average', 'filter', 'isManager'));
        $this->render('protocols_per_year');
    }

    public function manager_protocols_per_year()
    {
        $this->protocols_per_year();
    }

    public function inspector_protocols_per_year()
    {
        $this->protocols_per_year();
    }

    // Add this wrapper so admins can access the same report action under the admin prefix
    public function admin_protocols_per_year()
    {
        $this->protocols_per_year();
    }                                                                                                                                                                                        


}

