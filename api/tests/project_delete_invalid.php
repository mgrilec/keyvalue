<?php

$test = new Test;

// delete a invalid project
$f3->set('QUIET', true);
$f3->mock('POST @project_delete', array('project_id' => rand()));
$f3->set('QUIET', false);

$response = json_decode($f3->get('RESPONSE'), true);

$test->expect($response['result'] == false, 'Delete invalid project');

// return results
$test_data = array();
$test_data['name'] = 'Delete invalid project';
$test_data['results'] = $test->results();
$test_data['status'] = $test->passed();
$f3->push('tests', $test_data);

?>