<?php

$test = new Test;

// create a project
$f3->set('QUIET', true);
$f3->mock('POST @project_create', array('project_title' => ''));
$f3->set('QUIET', false);

$response = json_decode($f3->get('RESPONSE'), true);

$test->expect(isset($response['error']), 'Create project with empty name');

// return results
$test_data = array();
$test_data['category'] = "Projects";
$test_data['name'] = 'Create project with empty name';
$test_data['results'] = $test->results();
$test_data['status'] = $test->passed();
$f3->push('tests', $test_data);

?>