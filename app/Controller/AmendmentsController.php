<?php
App::uses('AppController', 'Controller');
/**
 * Amendments Controller
 *
 * @property Amendment $Amendment
 */
class AmendmentsController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function applicant_index() {
		$this->Amendment->recursive = 0;
		$this->set('amendments', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Amendment->id = $id;
		if (!$this->Amendment->exists()) {
			throw new NotFoundException(__('Invalid amendment'));
		}
		$this->set('amendment', $this->Amendment->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function applicant_add($id = null) {
		$this->Amendment->Application->id = $id;

		$application = $this->_isNewAmndt($id);
		$this->set('application', $application);

		if ($this->request->is('post')) {
			$errors = $this->_validateAmendmentInitialStep($this->request->data);
			$this->_normalizeAmendmentInitialAttachments($this->request->data);

			if (!empty($errors)) {
				$this->Session->setFlash(implode('<br>', $errors), 'alerts/flash_error');
				return;
			}

			$this->request->data['Amendment'] = array(
				'application_id' => $id,
				'submitted' => 0
			);
			if (empty($this->request->data['Amend']) || !is_array($this->request->data['Amend'])) {
				$this->request->data['Amend'] = array();
			}
			$this->request->data['Amend']['application_id'] = $id;
			$this->request->data['Amend']['submitted'] = 0;

			$this->Amendment->create();
			if ($this->Amendment->saveAssociated($this->request->data, array('validate' => 'first', 'deep' => true))) {
				$this->_syncAmendmentCoverLetterChecklistEntry(
					(int) $this->Amendment->id,
					(int) $application['Application']['id']
				);
				$data = array(
					'id' => $this->Amendment->id,
					'application_id' => $application['Application']['id'],
					'user_id' => $this->Auth->User('id'),
					'protocol_no' => $application['Application']['protocol_no']
				);
				CakeResque::enqueue('default', 'NotificationShell', array('newAmndtNotifyApplicant', $data));
				$this->Session->setFlash(__('Amendment Step 1 completed. Continue with the full amendment form.'), 'alerts/flash_success');
				return $this->redirect(array('action' => 'edit', $this->Amendment->id));
			}

			$modelErrors = array();
				$errorSets = array(
					$this->Amendment->validationErrors,
					$this->Amendment->Amend->validationErrors,
					$this->Amendment->Attachment->validationErrors,
					$this->Amendment->CoverLetter->validationErrors,
				);
			foreach ($errorSets as $errorSet) {
				if (empty($errorSet) || !is_array($errorSet)) {
					continue;
				}
				foreach ($errorSet as $fieldErrors) {
					if (is_array($fieldErrors)) {
						foreach ($fieldErrors as $fieldError) {
							$modelErrors[] = $fieldError;
						}
					} else {
						$modelErrors[] = $fieldErrors;
					}
				}
			}
			if (!empty($modelErrors)) {
				$this->Session->setFlash(implode('<br>', array_unique($modelErrors)), 'alerts/flash_error');
			} else {
				$this->Session->setFlash(__('The amendment could not be saved. Please, try again.'), 'alerts/flash_error');
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function applicant_edit($id = null) {
		$this->Amendment->id = $id;
		if (!$this->Amendment->exists()) {
			$this->Session->setFlash(__('Invalid Amendment.'), 'alerts/flash_error');
			$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
		}
		$amndt = $this->Amendment->find('first', array('conditions' => array('Amendment.id' => $id), 'contain' => array()));
		/*$application = $this->Amendment->Application->find('first', array(
		  'conditions' => array('Application.id' => $amndt['Amendment']['application_id']),
		  'contain' => array(
		  	'Amendment' => array('Attachment', 'CoverLetter'),
		  	'InvestigatorContact', 'Sponsor', 'SiteDetail', 'Organization', 'Placebo', 'Attachment', 'AnnualApproval',
		  	'Review' => array('conditions' => array('Review.type' => 'ppb_comment')))
		));*/
		$contains = $this->a_contain;
		$contains['Amendment'] =  array('Attachment', 'CoverLetter', 'Amend');
		$contains['Review'] = array('conditions' => array('Review.type' => 'ppb_comment'));
		$application = $this->Amendment->Application->find('first', array(
		  'conditions' => array('Application.id' => $amndt['Amendment']['application_id']),
		  'contain' => $contains
		));
		
		$this->_isEditAmndt($application['Application']['user_id'], $application['Application']['id'], $amndt['Amendment']['submitted']);

		if ($this->request->is('post') || $this->request->is('put')) {
			if (isset($this->request->data['cancelReport'])) {
				$this->Session->setFlash(__('Amendment cancelled. You may edit and submit it later.'), 'alerts/flash_info');
				$this->redirect(array('controller' => 'applications', 'action' => 'view', $amndt['Amendment']['application_id']));
			}
			$validate = false;
			if (isset($this->request->data['submitReport'])) {
				$validate = 'first';
				$this->request->data['Amendment']['submitted'] = 1;
				$this->request->data['Amendment']['date_submitted'] = date('Y-m-d H:i:s');
			}

			$filedata = $this->request->data;
			unset($filedata['Amendment']);
			if(empty($this->request->data)) {
				$this->Session->setFlash(__('The file(s) you provided could not be saved. Kindly ensure that the file(s) are less than
					18 MB in size. <small>If they are larger, compress (zip,tar...) them to the required size first</small>'), 'alerts/flash_error');
				$this->redirect(array('action' => 'edit', $id));
			}
				elseif (!$this->Amendment->saveAll($filedata, array(
					'validate' => 'only',
					'fieldList' => array(
						'Attachment' => 'file'
					)))) {
					$this->Session->setFlash(__('The file(s) is not valid. If the file(s) are more than
						18 MB in size please compress them to below 18 MB first.'), 'alerts/flash_error');
				} else {
					if ($this->Amendment->saveAssociated($this->request->data, array('validate' => $validate, 'deep' => true))) {
						$this->_syncAmendmentCoverLetterChecklistEntry(
							(int) $this->Amendment->id,
							(int) $amndt['Amendment']['application_id']
						);
						if ($validate) {
							$this->Amendment->Amend->updateAll(
								array(
									'Amend.submitted' => 1,
									'Amend.date_submitted' => "'" . date('Y-m-d H:i:s') . "'"
								),
								array('Amend.amendment_id' => $this->Amendment->id)
							);
						}
						if ($validate) {
							$this->Session->setFlash(__('You have successfully submitted the amendment to PPB. PPB will review
								this amendment and notify you on the progress. You can view the progress of the application by clicking on
								&#39;my applications&#39; on the dashboard menu. Thank you.'), 'alerts/flash_success');
						// CakeResque::enqueue('default', 'NotificationShell', array('submitAmndtNotifyManagersReviewers',
						// 	$amndt['Amendment']['application_id']));
						$this->redirect(array('controller' => 'applications', 'action' => 'view', $amndt['Amendment']['application_id']));
					} else {
						$this->Session->setFlash(__('The amendment has been saved'), 'alerts/flash_success');
						$this->redirect(array('action' => 'edit', $this->Amendment->id));
					}
				} else {
					$this->Session->setFlash(__('The amendment could not be saved. Please, try again.'), 'alerts/flash_error');
				}
			}
		} else {
			$this->request->data = $amndt;
		}
		$this->set('application', $application);
	}

/**
 * delete method
 *
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function applicant_delete($id = null) {
		// if (!$this->request->is('post')) {
		// 	throw new MethodNotAllowedException();
		// }
		$this->Amendment->id = $id;
		if (!$this->Amendment->exists()) {
			throw new NotFoundException(__('Invalid amendment'));
		}
		if ($this->Amendment->delete()) {
			$this->Session->setFlash(__('Amendment deleted'), 'alerts/flash_success');
			$this->redirect(array('controller' => 'applications', 'action' => 'view', $this->Amendment->field('application_id')));
		}
		$this->Session->setFlash(__('Amendment was not deleted'), 'alerts/flash_error');
		$this->redirect(array('action' => 'index'));
	}

/**
* Utility Methods
*/
	protected function _isNewAmndt($id) {
		$application = $this->Amendment->Application->find('first', array(
			'conditions' => array('Application.id' => $id),
			'fields' => array('Application.id', 'Application.submitted', 'Application.user_id', 'Application.protocol_no'),
			'contain' => array('Amendment' => array('conditions' => array('Amendment.submitted' => 0), 'fields' => 'Amendment.id')),
		));
		if (empty($application)) {
			$this->Session->setFlash(__('Application not found.'), 'alerts/flash_error');
			$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
		} elseif ($application['Application']['user_id'] != $this->Auth->User('id')) {
			$this->Session->setFlash(__('You do not have permission to access this resource.'), 'alerts/flash_error');
			$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
		} elseif (!empty($application['Amendment'])) {
			$this->Session->setFlash(__('You have an unsubmitted amendment for this application. Please edit and submit the
				pending amendment
				before you create a new one.'), 'alerts/flash_error');
			$this->redirect(array('controller' => 'amendments', 'action' => 'edit', $application['Amendment'][0]['id']));
		} elseif ($application['Application']['submitted'] != 1) {
			$this->Session->setFlash(__('You cannot amend this application because it has not been submitted to PPB.'),
				'alerts/flash_error');
			$this->redirect(array('controller' => 'applications', 'action' => 'edit', $id));
		}
		return $application;
	}

	protected function _isEditAmndt($user_id, $application_id, $amndt_submitted) {
		if($user_id != $this->Auth->User('id')) {
			$this->Session->setFlash(__('You do not have permission to access this amendment'), 'alerts/flash_error');
			$this->redirect(array('controller' => 'applications', 'action' => 'index'));
		} elseif ($amndt_submitted) {
			$this->Session->setFlash(__('Amendment already submitted to PPB. You may create a new amendment.'), 'alerts/flash_info');
			$this->redirect(array('controller' => 'applications', 'action' => 'view', $application_id));
		}
	}

	protected function _syncAmendmentCoverLetterChecklistEntry($amendmentId, $applicationId) {
		$amendmentId = (int) $amendmentId;
		$applicationId = (int) $applicationId;
		if ($amendmentId < 1 || $applicationId < 1) {
			return false;
		}

		$coverRow = $this->Amendment->CoverLetter->find('first', array(
			'conditions' => array(
				'CoverLetter.model' => 'Amendment',
				'CoverLetter.group' => 'cover_letter',
				'CoverLetter.foreign_key' => $amendmentId
			),
			'order' => array('CoverLetter.created' => 'DESC', 'CoverLetter.id' => 'DESC'),
			'recursive' => -1
		));
		if (empty($coverRow['CoverLetter'])) {
			return false;
		}
		$cover = $coverRow['CoverLetter'];

		$sequence = (int) $this->Amendment->find('count', array(
			'conditions' => array(
				'Amendment.application_id' => $applicationId,
				'Amendment.id <=' => $amendmentId
			),
			'recursive' => -1
		));
		if ($sequence < 1) {
			$sequence = (int) $this->Amendment->find('count', array(
				'conditions' => array('Amendment.application_id' => $applicationId),
				'recursive' => -1
			));
		}
		if ($sequence < 1) {
			$sequence = 1;
		}
		$amendmentYear = 'amd-' . $sequence;

		$pocketName = $this->_resolveAmendmentCoverPocketName();
		$attachmentModel = $this->Amendment->Attachment;

		$existing = $attachmentModel->find('first', array(
			'conditions' => array(
				'Attachment.model' => 'AmendmentChecklist',
				'Attachment.foreign_key' => $applicationId,
				'Attachment.year' => $amendmentYear,
				'Attachment.pocket_name' => $pocketName
			),
			'order' => array('Attachment.id' => 'DESC'),
			'recursive' => -1
		));
		if (!empty($existing['Attachment']['id'])) {
			return true;
		}

		$derivedFileDate = date('Y-m-d');
		if (!empty($cover['created']) && strtotime($cover['created']) !== false) {
			$derivedFileDate = date('Y-m-d', strtotime($cover['created']));
		}

		$description = trim((string) (isset($cover['description']) ? $cover['description'] : ''));
		if ($description === '') {
			$description = 'Cover letter';
		}

		$saveData = array(
			'Attachment' => array(
				'model' => 'AmendmentChecklist',
				'foreign_key' => $applicationId,
				'group' => $pocketName,
				'pocket_name' => $pocketName,
				'year' => $amendmentYear,
				'description' => $description,
				'dirname' => $cover['dirname'],
				'basename' => $cover['basename'],
				'checksum' => $cover['checksum'],
				'version_no' => !empty($cover['version_no']) ? $cover['version_no'] : '',
				'file_date' => $derivedFileDate
			)
		);

		if (!empty($cover['filesize'])) {
			$saveData['Attachment']['filesize'] = $cover['filesize'];
		}
		if (!empty($cover['mimetype'])) {
			$saveData['Attachment']['mimetype'] = $cover['mimetype'];
		}

		$attachmentModel->create();
		return (bool) $attachmentModel->save($saveData, array('validate' => false));
	}

	protected function _resolveAmendmentCoverPocketName() {
		$this->loadModel('Pocket');
		$pockets = $this->Pocket->find('list', array(
			'fields' => array('Pocket.name', 'Pocket.content'),
			'conditions' => array('Pocket.type' => 'amendment'),
			'order' => array('Pocket.item_number' => 'ASC'),
			'recursive' => -1
		));

		if (empty($pockets)) {
			return 'cover_letter';
		}

		reset($pockets);
		$fallbackKey = key($pockets);

		foreach ($pockets as $pocketKey => $pocketLabel) {
			$keyLower = strtolower((string) $pocketKey);
			$labelLower = strtolower(trim(strip_tags((string) $pocketLabel)));
			if (
				strpos($keyLower, 'cover') !== false
				|| strpos($labelLower, 'cover letter') !== false
				|| strpos($labelLower, 'covering letter') !== false
			) {
				return $pocketKey;
			}
		}

		return !empty($fallbackKey) ? $fallbackKey : 'cover_letter';
	}

	protected function _normalizeAmendmentInitialAttachments(&$data) {
		$requiredFields = array(
			'cover_letter',
			'summary',
			'reason',
			'objectives_impacts',
			'endpoints_impacts',
			'safety_impacts'
		);

		foreach ($requiredFields as $field) {
			if (isset($data['Amend'][$field]) && is_string($data['Amend'][$field])) {
				$data['Amend'][$field] = trim($data['Amend'][$field]);
			}
		}

		$coverRows = array();
		if (!empty($data['CoverLetter']) && is_array($data['CoverLetter'])) {
			$coverRows = $data['CoverLetter'];
		}

		$normalizedCover = array();
		foreach ($coverRows as $row) {
			$file = isset($row['file']) ? $row['file'] : null;
			$hasFile = is_array($file)
				&& !empty($file['name'])
				&& isset($file['error'])
				&& (int) $file['error'] !== 4;
			if (!$hasFile) {
				continue;
			}

			$description = isset($row['description']) ? trim((string) $row['description']) : '';
			if ($description === '') {
				$description = 'Cover letter';
			}

			$normalizedCover[] = array(
				'model' => 'Amendment',
				'group' => 'cover_letter',
				'description' => $description,
				'file' => $file
			);
			break;
		}
		$data['CoverLetter'] = $normalizedCover;

		$rows = array();
		if (!empty($data['Attachment']) && is_array($data['Attachment'])) {
			$rows = $data['Attachment'];
		}

		$normalized = array();
		foreach ($rows as $index => $row) {
			$file = isset($row['file']) ? $row['file'] : null;
			$hasFile = is_array($file)
				&& !empty($file['name'])
				&& isset($file['error'])
				&& (int) $file['error'] !== 4;
			if (!$hasFile) {
				continue;
			}

			$description = isset($row['description']) ? trim((string) $row['description']) : '';

			$normalized[] = array(
				'model' => 'Amendment',
				'group' => 'attachment',
				'description' => $description,
				'file' => $file
			);
		}

		$data['Attachment'] = $normalized;
	}

	protected function _validateAmendmentInitialStep($data) {
		$errors = array();
		$requiredLabels = array(
			'cover_letter' => 'Cover letter',
			'summary' => 'Summary of the proposed amendments',
			'reason' => 'Reason for the amendment',
			'objectives_impacts' => 'Impact of the amendment on the original study objectives',
			'endpoints_impacts' => 'Impact of the amendments on the study endpoints and data generated',
			'safety_impacts' => 'Impact of the proposed amendments on the safety and wellbeing of study participants',
		);

		foreach ($requiredLabels as $field => $label) {
			$value = isset($data['Amend'][$field]) ? $data['Amend'][$field] : '';
			if (!$this->_hasMeaningfulText($value)) {
				$errors[] = __('Please provide: %s.', $label);
			}
		}

		$coverLetterFile = null;
		if (isset($data['CoverLetter'][0]['file'])) {
			$coverLetterFile = $data['CoverLetter'][0]['file'];
		}
		$hasCoverLetter = is_array($coverLetterFile)
			&& !empty($coverLetterFile['name'])
			&& isset($coverLetterFile['error'])
			&& (int) $coverLetterFile['error'] !== 4;

		if (!$hasCoverLetter) {
			$errors[] = __('Cover letter upload is required before continuing.');
		}

		return $errors;
	}

	protected function _hasMeaningfulText($value) {
		$text = trim((string) $value);
		if ($text === '') {
			return false;
		}

		$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
		$text = str_replace("\xC2\xA0", ' ', $text);
		$text = strip_tags($text);
		$text = preg_replace('/\s+/', ' ', $text);

		return trim($text) !== '';
	}
}
