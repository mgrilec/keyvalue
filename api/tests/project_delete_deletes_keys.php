<?php

$test = new Test;

// create a project
$f3->set('QUIET', true);
$f3->mock('POST @project_create', array('project_title' => rand()));
$f3->set('QUIET', false);

$id = json_decode($f3->get('RESPONSE'), true)['id'];

// set some keys
$f3->set('QUIET', true);
$f3->mock('POST @key_set', array('keys' => array(rand()), 'values' => array(rand())));
$f3->set('QUIET', false);

// delete the project
$f3->set('QUIET', true);
$f3->mock('POST @project_delete', array('project_id' => $id));
$f3->set('QUIET', false);

// check if any keys exist
$f3->set('QUIET', true);
$f3->mock('GET @key_count(@project_id='.$id.')');
$f3->set('QUIET', false);

$response = json_decode($f3->get('RESPONSE'), true);

$test->expect($response['count'] == 0, 'Delete project keys');

// return results
$test_data = array();
$test_data['category'] = "Projects/CRUD";
$test_data['name'] = 'Delete project deletes keys';
$test_data['results'] = $test->results();
$test_data['status'] = $test->passed();
$f3->push('tests', $test_data);

?>