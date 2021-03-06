<?php

/*
 
 */
function survey_get_example(){
$params = array(
  'title' => 'survey title',
  'activity_type_id' => '35',
  'max_number_of_contacts' => 12,
  'instructions' => 'Call people, ask for money',
  'version' => 3,
  'debug' => 0,
);

  $result = civicrm_api( 'survey','get',$params );

  return $result;
}

/*
 * Function returns array of result expected from previous function
 */
function survey_get_expectedresult(){

  $expectedResult = array(
  'is_error' => 0,
  'version' => 3,
  'count' => 1,
  'id' => 1,
  'values' => array(
      '1' => array(
          'id' => '1',
          'title' => 'survey title',
          'activity_type_id' => '35',
          'instructions' => 'Call people, ask for money',
          'max_number_of_contacts' => '12',
          'is_active' => '1',
          'is_default' => 0,
          'created_date' => '20120130621222105',
          'bypass_confirm' => 0,
        ),
    ),
);

  return $expectedResult  ;
}


/*
* This example has been generated from the API test suite. The test that created it is called
*
* testGetSurvey and can be found in
* http://svn.civicrm.org/civicrm/trunk/tests/phpunit/CiviTest/api/v3/SurveyTest.php
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