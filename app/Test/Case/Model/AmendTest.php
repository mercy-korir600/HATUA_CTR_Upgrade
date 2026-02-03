<?php
App::uses('Amend', 'Model');

/**
 * Amend Test Case
 *
 */
class AmendTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.amend',
		'app.amendment',
		'app.application',
		'app.user',
		'app.group',
		'app.county',
		'app.country',
		'app.review',
		'app.review_answer',
		'app.comment',
		'app.attachment',
		'app.active_inspector',
		'app.notification',
		'app.feedback',
		'app.study_monitor',
		'app.protocol_outsource',
		'app.outsource',
		'app.outsource_request',
		'app.trial_status',
		'app.investigator_contact',
		'app.application_stage',
		'app.pharmacist',
		'app.ethical_committee',
		'app.annual_letter',
		'app.amendment_letter',
		'app.termination',
		'app.amendment_approval',
		'app.organization',
		'app.site_detail',
		'app.placebo',
		'app.sponsor',
		'app.site_inspection',
		'app.site_answer',
		'app.sae',
		'app.suspected_drug',
		'app.route',
		'app.concomittant_drug',
		'app.sae_date',
		'app.ciom',
		'app.study_route',
		'app.manufacturer',
		'app.reassignment',
		'app.reminder',
		'app.participant_flow',
		'app.budget',
		'app.deviation',
		'app.safety_report',
		'app.multi_center'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Amend = ClassRegistry::init('Amend');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Amend);

		parent::tearDown();
	}

}
