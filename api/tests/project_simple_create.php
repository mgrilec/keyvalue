<?php

$test = new Test;

// setup a project
$project = array('title' => rand(), 'description' => rand(), 'color' => rand());

// create a project
$f3->set('QUIET', true);
$f3->mock('POST @project_create', array('project' => $project));
$f3->set('QUIET', false);

$id = json_decode($f3->get('RESPONSE'), true)['result'];

// see if it exists
$f3->set('QUIET', true);
$f3->mock('GET @project_exists(@project_id='.$id.')');
$f3->set('QUIET', false);

// run test
$response = json_decode($f3->get('RESPONSE'), true);
$test->expect($response['result'], 'Project exists');

// see if it has the right fields
$f3->set('QUIET', true);
$f3->mock('GET @project_get(@project_id='.$id.')');
$f3->set('QUIET', false);

// run test
$response = json_decode($f3->get('RESPONSE'), true);
$new_project = $response['result'];

$test->expect(!array_diff($project, $new_project), 'Project has same fields');

// delete project
$f3->set('QUIET', true);
$f3->mock('POST @project_delete', array('project' => array('id' => $id)));
$f3->set('QUIET', false);

// return results
$test_data = array();
$test_data['category'] = "Projects/CRUD";
$test_data['name'] = 'Simple create project';
$test_data['results'] = $test->results();
$test_data['status'] = $test->passed();
$f3->push('tests', $test_data);

?>