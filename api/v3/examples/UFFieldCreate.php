<?php

/*
 
 */
function uf_field_create_example(){
$params = array(
  'field_name' => 'phone',
  'field_type' => 'Contact',
  'visibility' => 'Public Pages and Listings',
  'weight' => 1,
  'label' => 'Test Phone',
  'is_searchable' => 1,
  'is_active' => 1,
  'location_type_id' => 1,
  'phone_type_id' => 1,
  'version' => 3,
  'uf_group_id' => 11,
);

  $result = civicrm_api( 'uf_field','create',$params );

  return $result;
}

/*
 * Function returns array of result expected from previous function
 */
function uf_field_create_expectedresult(){

  $expectedResult = array(
  'is_error' => 0,
  'version' => 3,
  'count' => 1,
  'id' => 1,
  'values' => array(
      '1' => array(
          'id' => '1',
          'uf_group_id' => '11',
          'field_name' => 'phone',
          'is_active' => '1',
          'is_view' => '',
          'is_required' => '',
          'weight' => '1',
          'help_post' => '',
          'help_pre' => '',
          'visibility' => 'Public Pages and Listings',
          'in_selector' => '',
          'is_searchable' => '1',
          'location_type_id' => '1',
          'phone_type_id' => '1',
          'label' => 'Test Phone',
          'field_type' => 'Contact',
          'is_reserved' => '',
          'is_multi_summary' => '',
        ),
    ),
);

  return $expectedResult  ;
}


/*
* This example has been generated from the API test suite. The test that created it is called
*
* testCreateUFField and can be found in
* http://svn.civicrm.org/civicrm/trunk/tests/phpunit/CiviTest/api/v3/UFFieldTest.php
*
* You can see the outcome of the API tests at
* http://tests.dev.civicrm.org/trunk/results-api_v3
*
* To Learn about the API read
* http://book.civicrm.org/developer/current/techniques/api/
*
* and review the wiki at
* http://wiki.civicrm.org/confluence/display/CRMDOC/CiviCRM+Public+APIs
*
* Read more about testing here
* http://wiki.civicrm.org/confluence/display/CRM/Testing
*
* API Standards documentation:
* http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
*/