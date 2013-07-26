<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.3                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2013                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

require_once 'CiviTest/CiviUnitTestCase.php';

/**
 *  Test APIv3 civicrm_contribute_* functions
 *
 *  @package CiviCRM_APIv3
 *  @subpackage API_ContributionSoft
 */

class api_v3_ContributionSoftTest extends CiviUnitTestCase {

  /**
   * Assume empty database with just civicrm_data
   */
  protected $_individualId; //the hard credit contact
  protected $_softIndividual1Id; //the first soft credit contact
  protected $_softIndividual2Id; //the second soft credit contact
  protected $_contributionId;
  protected $_financialTypeId = 1;
  protected $_apiversion = 3;
  protected $_entity = 'Contribution';
  public $debug = 0;
  protected $_params;
  public $_eNoticeCompliant = TRUE;

  function setUp() {
    parent::setUp();

    $this->_individualId = $this->individualCreate();
    $this->_softIndividual1Id = $this->individualCreate();
    $this->_softIndividual2Id = $this->individualCreate();
    $this->_contributionId = $this->contributionCreate($this->_individualId);

    $paymentProcessor = $this->processorCreate();
    $this->_params = array(
      'contact_id' => $this->_individualId,
      'receive_date' => '20120511',
      'total_amount' => 100.00,
      'financial_type_id'   => $this->_financialTypeId,
      'non_deductible_amount' => 10.00,
      'fee_amount' => 5.00,
      'net_amount' => 95.00,
      'source' => 'SSF',
      'contribution_status_id' => 1,
    );
    $this->_processorParams = array(
      'domain_id' => 1,
      'name' => 'Dummy',
      'payment_processor_type_id' => 10,
      'financial_account_id' => 12,
      'is_active' => 1,
      'user_name' => '',
      'url_site' => 'http://dummy.com',
      'url_recur' => 'http://dummy.com',
      'billing_mode' => 1,
    );
  }

  function tearDown() {
    $this->quickCleanup(array(
      'civicrm_contribution',
      'civicrm_event',
      'civicrm_contribution_page',
      'civicrm_participant',
      'civicrm_participant_payment',
      'civicrm_line_item',
      'civicrm_financial_trxn',
      'civicrm_financial_item',
      'civicrm_entity_financial_trxn',
      'civicrm_contact',
      'civicrm_contribution_soft'
    ));
  }

  /**
   * test get methods
   * @todo - this might be better broken down into more smaller tests
   */
  function testGetContributionSoft() {
    //We don't test for PCP fields because there's no PCP API, so we can't create campaigns.
    $p = array(
      'contribution_id' => $this->_contributionId,
      'contact_id' => $this->_softIndividual1Id,
      'amount' => 10.00,
      'currency' => 'USD',
    );

    $this->_softcontribution = $this->callAPISuccess('contribution_soft', 'create', $p);
    $params = array(
      'id' => $this->_softcontribution['id'],
    );
    $softcontribution = $this->callAPIAndDocument('contribution_soft', 'get', $params, __FUNCTION__, __FILE__);
    $this->assertEquals(1, $softcontribution['count']);
    $this->assertEquals($softcontribution['values'][$this->_softcontribution['id']]['contribution_id'], $this->_contributionId, 'In line ' . __LINE__);
    $this->assertEquals($softcontribution['values'][$this->_softcontribution['id']]['contact_id'], $this->_softIndividual1Id, 'In line ' . __LINE__);
    $this->assertEquals($softcontribution['values'][$this->_softcontribution['id']]['amount'], '10.00', 'In line ' . __LINE__);
    $this->assertEquals($softcontribution['values'][$this->_softcontribution['id']]['currency'], 'USD', 'In line ' . __LINE__);

    //create a second soft contribution on the same hard contribution - we are testing that 'id' gets the right soft contribution id (not the contribution id)
    $p['contact_id'] = $this->_softIndividual2Id;
    $this->_softcontribution2 =  $this->callAPISuccess('contribution_soft', 'create', $p);

    // now we have 2 - test getcount
    $softcontribution =  $this->callAPISuccess('contribution_soft', 'getcount', array());
    $this->assertEquals(2, $softcontribution);

    //check first contribution
    $result =  $this->callAPISuccess('contribution_soft', 'get', array(
      'id' => $this->_softcontribution['id'],
    ));
    $this->assertEquals(1, $result['count'], 'In line ' . __LINE__);
    $this->assertEquals($this->_softcontribution['id'], $result['id']);
    $this->assertEquals($this->_softcontribution['id'], $result['id'], print_r($softcontribution,true));

    //test id only format - second soft credit
    $resultID2 =  $this->callAPISuccess('contribution_soft', 'get', array(
      'id' => $this->_softcontribution2['id'],
      'format.only_id' => 1,
    ));
    $this->assertEquals($this->_softcontribution2['id'], $resultID2);

    //test get by contact id works
    $result =  $this->callAPISuccess('contribution_soft', 'get', array(
      'contact_id' => $this->_softIndividual2Id)
    );
    $this->assertEquals(1, $result['count'], 'In line ' . __LINE__);

    $this->callAPISuccess('contribution_soft', 'Delete', array(
      'id' => $this->_softcontribution['id'],
    ));
    // check one soft credit remains
    $expectedCount = 1;
    $this->callAPISuccess('contribution_soft', 'getcount', array(), $expectedCount);

    //check id is same as 2
    $this->assertEquals($this->_softcontribution2['id'], $this->callAPISuccess('contribution_soft', 'getvalue', array('return' => 'id' )));

    $this->callAPISuccess('ContributionSoft', 'Delete', array(
      'id' => $this->_softcontribution2['id'],
     ));
  }


  ///////////////// civicrm_contribution_soft
  function testCreateEmptyParamsContributionSoft() {
    $softcontribution = $this->callAPIFailure('contribution_soft', 'create', array(),
      'Mandatory key(s) missing from params array: contribution_id, amount, contact_id'
    );
  }

  function testCreateParamsWithoutRequiredKeysContributionSoft() {
    $softcontribution = $this->callAPIFailure('contribution_soft', 'create', array(),
      'Mandatory key(s) missing from params array: contribution_id, amount, contact_id'
    );
  }

  function testCreateContributionSoftInvalidContact() {

    $params = array(
      'contact_id' => 999,
      'contribution_id' => $this->_contributionId,
      'amount' => 10.00,
      'currency' => 'USD',
    );

    $softcontribution = $this->callAPIFailure('contribution_soft', 'create', $params,
      'contact_id is not valid : 999'
    );
  }

  function testCreateContributionSoftInvalidContributionId() {

    $params = array(
      'contribution_id' => 999999,
      'contact_id' => $this->_softIndividual1Id,
      'amount' => 10.00,
      'currency' => 'USD',
    );

    $softcontribution = $this->callAPIFailure('contribution_soft', 'create', $params,
      'contribution_id is not valid : 999999'
    );
  }

  /*
   * Function tests that additional financial records are created when fee amount is recorded
   */
  function testCreateContributionSoft() {
    $params = array(
      'contribution_id' => $this->_contributionId,
      'contact_id' => $this->_softIndividual1Id,
      'amount' => 10.00,
      'currency' => 'USD',
    );

    $softcontribution = $this->callAPIAndDocument('contribution_soft', 'create', $params, __FUNCTION__, __FILE__);
    $this->assertEquals($softcontribution['values'][$softcontribution['id']]['contribution_id'], $this->_contributionId, 'In line ' . __LINE__);
    $this->assertEquals($softcontribution['values'][$softcontribution['id']]['contact_id'], $this->_softIndividual1Id, 'In line ' . __LINE__);
    $this->assertEquals($softcontribution['values'][$softcontribution['id']]['amount'], '10.00', 'In line ' . __LINE__);
    $this->assertEquals($softcontribution['values'][$softcontribution['id']]['currency'], 'USD', 'In line ' . __LINE__);
  }

  //To Update Soft Contribution
  function testCreateUpdateContributionSoft() {
    //create a soft credit
    $params = array(
      'contribution_id' => $this->_contributionId,
      'contact_id' => $this->_softIndividual1Id,
      'amount' => 10.00,
      'currency' => 'USD',
    );

    $softcontribution = $this->callAPISuccess('contribution_soft', 'create', $params);
    $softcontributionID = $softcontribution['id'];

    $old_params = array(
      'contribution_soft_id' => $softcontributionID,
    );
    $original = $this->callAPISuccess('contribution_soft', 'get', $old_params);
    //Make sure it came back
    $this->assertEquals($original['id'], $softcontributionID, 'In line ' . __LINE__);
    //set up list of old params, verify
    $old_contribution_id = $original['values'][$softcontributionID]['contribution_id'];
    $old_contact_id = $original['values'][$softcontributionID]['contact_id'];
    $old_amount = $original['values'][$softcontributionID]['amount'];
    $old_currency = $original['values'][$softcontributionID]['currency'];

    //check against original values
    $this->assertEquals($old_contribution_id, $this->_contributionId, 'In line ' . __LINE__);
    $this->assertEquals($old_contact_id, $this->_softIndividual1Id, 'In line ' . __LINE__);
    $this->assertEquals($old_amount, 10.00, 'In line ' . __LINE__);
    $this->assertEquals($old_currency, 'USD', 'In line ' . __LINE__);
    $params = array(
      'id' => $softcontributionID,
      'contribution_id' => $this->_contributionId,
      'contact_id' => $this->_softIndividual1Id,
      'amount' => 7.00,
      'currency' => 'CAD',
    );

    $softcontribution = $this->callAPISuccess('contribution_soft', 'create', $params);

    $new_params = array(
      'id' => $softcontribution['id'],
    );
    $softcontribution = $this->callAPISuccess('contribution_soft', 'get', $new_params);
    //check against original values
    $this->assertEquals($softcontribution['values'][$softcontributionID]['contribution_id'], $this->_contributionId, 'In line ' . __LINE__);
    $this->assertEquals($softcontribution['values'][$softcontributionID]['contact_id'], $this->_softIndividual1Id, 'In line ' . __LINE__);
    $this->assertEquals($softcontribution['values'][$softcontributionID]['amount'], 7.00, 'In line ' . __LINE__);
    $this->assertEquals($softcontribution['values'][$softcontributionID]['currency'], 'CAD', 'In line ' . __LINE__);

    $params = array(
      'id' => $softcontributionID,
    );
    $result = $this->callAPISuccess('contribution_soft', 'delete', $params);
  }

  ///////////////// civicrm_contribution_soft_delete methods
  function testDeleteEmptyParamsContributionSoft() {
    $params = array();
    $softcontribution = $this->callAPIFailure('contribution_soft', 'delete', $params);
  }

  function testDeleteWrongParamContributionSoft() {
    $params = array(
      'contribution_source' => 'SSF',
    );
    $softcontribution = $this->callAPIFailure('contribution_soft', 'delete', $params);
  }

  function testDeleteContributionSoft() {
    //create a soft credit
    $params = array(
      'contribution_id' => $this->_contributionId,
      'contact_id' => $this->_softIndividual1Id,
      'amount' => 10.00,
      'currency' => 'USD',
    );

    $softcontribution = $this->callAPISuccess('contribution_soft', 'create', $params);
    $softcontributionID = $softcontribution['id'];
    $params = array(
      'id' => $softcontributionID,
    );
    $result = $this->callAPIAndDocument('contribution_soft', 'delete', $params, __FUNCTION__, __FILE__);
  }

  ///////////////// civicrm_contribution_search methods

  /**
   *  Test civicrm_contribution_search with empty params.
   *  All available contributions expected.
   */
  function testSearchEmptyParams() {
    $p = array(
      'contribution_id' => $this->_contributionId,
      'contact_id' => $this->_softIndividual1Id,
      'amount' => 10.00,
      'currency' => 'USD',
    );
    $softcontribution = $this->callAPISuccess('contribution_soft', 'create', $p);

    $result = $this->callAPISuccess('contribution_soft', 'get', array());
    // We're taking the first element.
    $res = $result['values'][$softcontribution['id']];

    $this->assertEquals($p['contribution_id'], $res['contribution_id'], 'In line ' . __LINE__);
    $this->assertEquals($p['contact_id'], $res['contact_id'], 'In line ' . __LINE__);
    $this->assertEquals($p['amount'], $res['amount'], 'In line ' . __LINE__);
    $this->assertEquals($p['currency'], $res['currency'], 'In line ' . __LINE__);
  }

  /**
   *  Test civicrm_contribution_soft_search. Success expected.
   */
  function testSearch() {
    $p1 = array(
      'contribution_id' => $this->_contributionId,
      'contact_id' => $this->_softIndividual1Id,
      'amount' => 10.00,
      'currency' => 'USD',
    );
    $softcontribution1 = $this->callAPISuccess('contribution_soft', 'create', $p1);

    $p2 = array(
      'contribution_id' => $this->_contributionId,
      'contact_id' => $this->_softIndividual2Id,
      'amount' => 25.00,
      'currency' => 'CAD',
    );
    $softcontribution2 = $this->callAPISuccess('contribution_soft', 'create', $p2);

    $params = array(
      'id' => $softcontribution2['id'],
    );
    $result = $this->callAPISuccess('contribution_soft', 'get', $params);
    $res = $result['values'][$softcontribution2['id']];

    $this->assertEquals($p2['contribution_id'], $res['contribution_id'], 'In line ' . __LINE__);
    $this->assertEquals($p2['contact_id'], $res['contact_id'], 'In line ' . __LINE__);
    $this->assertEquals($p2['amount'], $res['amount'], 'In line ' . __LINE__);
    $this->assertEquals($p2['currency'], $res['currency'], 'In line ' . __LINE__ );
  }
}

