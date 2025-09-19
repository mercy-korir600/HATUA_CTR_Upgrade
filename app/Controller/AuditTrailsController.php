<?php
App::uses('AppController', 'Controller');
App::uses('String', 'Utility');
App::uses('ThemeView', 'View');
App::uses('HtmlHelper', 'View/Helper');
App::uses('Sanitize', 'Utility');
App::uses('CakeTime', 'Utility');
/**
 * AuditTrails Controller
 *  @property AuditTrail $AuditTrail
 */
class AuditTrailsController extends AppController {

/**
 * Scaffold
 *
 * @var mixed
 */
	public $scaffold;
	public $paginate = array();
    public $components = array('Search.Prg');
    public $presetVars = true;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow();
    }
	public function admin_index()
    {
		$this->Prg->commonProcess();
        $page_options = array('50' => '50', '100' => '100', '500' => '500');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) $this->passedArgs['range'] = true;
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) $this->paginate['limit'] = $this->passedArgs['pages'];
        else $this->paginate['limit'] = reset($page_options);

        $criteria = $this->AuditTrail->parseCriteria($this->passedArgs); 
        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('AuditTrail.created' => 'desc');
        
        //in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->AuditTrail->find(
                'all',
                array('conditions' => $this->paginate['conditions'], 'order' => $this->paginate['order'])
            ));
        }
        //end pdf export

        $this->set('page_options', $page_options);
        $this->set('audits', Sanitize::clean($this->paginate(), array('encode' => false)));
 
	}
	private function csv_export($audits = '')
    { 
        $this->response->download('audits_' . date('Ymd_Hi') . '.csv'); // <= setting the file name
        $this->set(compact('audits'));
        $this->layout = false;
        $this->render('csv_export');
    }
	public function admin_delete($id=null)
	{
		if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->AuditTrail->id = $id;
        if (!$this->AuditTrail->exists()) {
            throw new NotFoundException(__('Invalid AuditTrail'));
        }
        if ($this->AuditTrail->delete()) {
            $this->Session->setFlash(__('AuditTrail deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('AuditTrail was not deleted'));
        $this->redirect(array('action' => 'index'));
	}

}
