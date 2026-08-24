<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
/**
 * Capas Controller
 *
 * Dedicated listing/reference section for CAPA (Corrective and Preventive
 * Action) cases - one row per reviewer assignment that missed its
 * Review-stage deadline. Only `Capa.type = 'Initial'` rows are listed here
 * (one per case, by definition); each case's full detail/follow-up thread
 * lives on its own dedicated page now (view()/manager_view() below,
 * app/View/Capas/manager_view.ctp) - linked from here, and from the same
 * element used on the application view (app/View/Elements/capas/list.ctp)
 * - so this page stays a scannable index rather than duplicating that
 * detail.
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
        // Every row (Initial AND FollowUp) is listed here now - each on
        // its own line, with a Type column distinguishing them, so a
        // FollowUp is just as visible/searchable as the case it belongs
        // to (see app/View/Capas/manager_index.ctp).

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
            // 'Reviewer' is a belongsTo alias for User (see Capa.php), so
            // the query builder uses that alias as the table alias too -
            // conditions must match it, not 'User'.
            'conditions' => array('Reviewer.group_id' => array(3, 9), 'Reviewer.is_active' => 1),
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

    /**
     * A single CAPA "case"'s own dedicated page - full detail (description,
     * root cause, corrective/preventive action, target date, responsible
     * person) plus its whole follow-up thread and, if still open, the
     * add-follow-up form (posted to
     * ApplicationsController::manager_add_capa_followup(), which redirects
     * back here via $this->referer() - see that method). Replaces the old
     * capas/modal.ctp popup, which was cramped for a table this wide - see
     * app/View/Capas/manager_view.ctp.
     *
     * $id is the case's 'Initial' row id - the same id capas/trigger.ctp
     * has always kept as the case's stable identity (formerly used to key
     * the modal's DOM id; now used to build this page's URL instead).
     */
    public function view($id = null)
    {
        $initial = $this->Capa->find('first', array(
            'conditions' => array('Capa.id' => $id, 'Capa.type' => 'Initial'),
            'contain' => array('Application', 'Reviewer', 'CreatedBy'),
        ));
        if (empty($initial)) {
            $this->Session->setFlash(__('CAPA case not found.'), 'alerts/flash_error');
            $this->redirect(array('action' => 'index', 'manager' => true));
        }

        // Same case-grouping key used everywhere else (review_id +
        // source_stage - see Capa.php) - oldest first, so the Initial row
        // is index 0 and Capa::buildThread() can hang every follow-up off
        // it in reply order.
        $case = $this->Capa->find('all', array(
            'conditions' => array(
                'Capa.review_id' => $initial['Capa']['review_id'],
                'Capa.source_stage' => $initial['Capa']['source_stage'],
            ),
            'contain' => array('CreatedBy'),
            'order' => array('Capa.created' => 'ASC'),
        ));
        $latest = end($case);
        $status = !empty($latest['Capa']['status']) ? $latest['Capa']['status'] : 'Open';

        $this->set('initial', $initial);
        $this->set('case', $case);
        $this->set('status', $status);
    }

    public function manager_view($id = null)
    {
        $this->view($id);
    }

    private function csv_export($capas = '')
    {
        $this->response->download('capas_' . date('Ymd_Hi') . '.csv');
        $this->set(compact('capas'));
        $this->layout = false;
        $this->render('csv_export');
    }
}
