<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
/**
 * Capas Controller
 *
 * Dedicated listing/reference section for CAPA (Corrective and Preventive
 * Action) cases - one row per reviewer assignment that missed its
 * Review-stage deadline. Only `Capa.type = 'Initial'` rows are listed here
 * (one per case, by definition); each case's full follow-up thread is
 * reachable from the same popup used on the application view - see
 * app/View/Elements/capas/list.ctp - so this page stays a scannable index
 * rather than duplicating that detail.
 *
 * Filter by reviewer, status, reference/protocol number, or date opened
 * (Search.Prg + Capa::$filterArgs - same pattern as Sae/Budget/AuditTrail
 * elsewhere in this app), and export the filtered list to Excel (CSV).
 *
 * @property Capa $Capa
 */
class CapasController extends AppController
{
    public $paginate = array();
    public $components = array('Search.Prg');
    public $presetVars = true;

    public function index()
    {
        $this->Prg->commonProcess();
        $page_options = array('25' => '25', '50' => '50', '100' => '100');
        if (!empty($this->passedArgs['start_date']) || !empty($this->passedArgs['end_date'])) {
            $this->passedArgs['range'] = true;
        }
        if (isset($this->passedArgs['pages']) && !empty($this->passedArgs['pages'])) {
            $this->paginate['limit'] = $this->passedArgs['pages'];
        } else {
            $this->paginate['limit'] = reset($page_options);
        }

        $criteria = $this->Capa->parseCriteria($this->passedArgs);
        // Only 'Initial' rows represent a CAPA case in this list - a
        // case's follow-ups are shown inside its popup, not as their own
        // rows here (see app/Model/Capa.php for the type='Initial' vs
        // 'FollowUp' modelling).
        $criteria['Capa.type'] = 'Initial';

        $this->paginate['conditions'] = $criteria;
        $this->paginate['order'] = array('Capa.created' => 'desc');
        $this->paginate['contain'] = array('Application', 'Reviewer');

        // in case of csv export
        if (isset($this->request->params['ext']) && $this->request->params['ext'] == 'csv') {
            $this->csv_export($this->Capa->find(
                'all',
                array(
                    'conditions' => $this->paginate['conditions'],
                    'order' => $this->paginate['order'],
                    'contain' => $this->paginate['contain'],
                )
            ));
        }
        // end csv export

        $capas = Sanitize::clean($this->paginate(), array('encode' => false));

        // Bulk-fetch the full thread (Initial + FollowUps) for just this
        // page's cases, grouped by review_id - lets the list reuse the
        // exact same title+popup element already used on the application
        // view (app/View/Elements/capas/list.ctp) for a "View / Follow up"
        // action, without a second round trip per row.
        $reviewIds = Hash::extract($capas, '{n}.Capa.review_id');
        $capasByReview = array();
        if (!empty($reviewIds)) {
            $threadRows = $this->Capa->find('all', array(
                'conditions' => array('Capa.review_id' => $reviewIds),
                'contain' => array('CreatedBy'),
                'order' => array('Capa.created' => 'ASC'),
            ));
            foreach ($threadRows as $row) {
                $capasByReview[$row['Capa']['review_id']][] = $row;
            }
        }

        $reviewers = $this->Capa->Reviewer->find('list', array(
            'conditions' => array('User.group_id' => array(3, 9), 'User.is_active' => 1),
        ));

        $this->set('page_options', $page_options);
        $this->set('reviewers', $reviewers);
        $this->set('capasByReview', $capasByReview);
        $this->set('capas', $capas);
    }

    public function manager_index()
    {
        $this->index();
    }

    private function csv_export($capas = '')
    {
        $this->response->download('capas_' . date('Ymd_Hi') . '.csv');
        $this->set(compact('capas'));
        $this->layout = false;
        $this->render('csv_export');
    }
}
