<?php

$test = new Test;

// create a project
$f3->set('QUIET', true);
$f3->mock('POST @project_create', array('project_title' => rand()));
$f3->set('QUIET', false);

$id = json_decode($f3->get('RESPONSE'), true)['id'];

// delete the project
$f3->set('QUIET', true);
$f3->mock('POST @project_delete', array('project_id' => $id));
$f3->set('QUIET', false);

// check if the project exists
$f3->set('QUIET', true);
$f3->mock('GET @project_exists(@project_id='.$id.')');
$f3->set('QUIET', false);

$response = json_decode($f3->get('RESPONSE'), true);

$test->expect(!$response['result'], 'Delete project');

// return results
$test_data = array();
$test_data['name'] = 'Simple delete project';
$test_data['results'] = $test->results();
$test_data['status'] = $test->passed();
$f3->push('tests', $test_data);

?>