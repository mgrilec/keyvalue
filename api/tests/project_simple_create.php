<?php

$test = new Test;

// create a project
$f3->set('QUIET', true);
$f3->mock('POST @project_create', array('project_title' => rand()));
$f3->set('QUIET', false);

$id = $f3->get('RESPONSE');

// see if it exists
$f3->set('QUIET', true);
$f3->mock('GET @project_exists(@project_id='.$id.')');
$f3->set('QUIET', false);

$response = json_decode($f3->get('RESPONSE'), true);

// check if project exists
$test->expect($response['result'], 'Create project');

// delete project
$f3->set('QUIET', true);
$f3->mock('POST @project_delete', array('project_id' => $id));
$f3->set('QUIET', false);

// return results
$test_data = array();
$test_data['name'] = 'Simple create project';
$test_data['results'] = $test->results();
$test_data['status'] = $test->passed();
$f3->push('tests', $test_data);

?>