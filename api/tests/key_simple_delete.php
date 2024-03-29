<?php

$test = new Test;

// create a project
$f3->set('QUIET', true);
$f3->mock('POST @project_create', array('project' => array('title' => rand())));
$f3->set('QUIET', false);

$id = json_decode($f3->get('RESPONSE'), true)['result'];

// prepare key
$key = rand();
$value = rand();

// set keys
$f3->set('QUIET', true);
$f3->mock('POST @key_set', array('project' => array('id' => $id), 'keys' => array($key), 'values' => array($value)));
$f3->set('QUIET', false);

// check if key exists
$f3->set('QUIET', true);
$f3->mock('GET @key_exists(@project_id='.$id.', @key='.$key.')');
$f3->set('QUIET', false);

$response = json_decode($f3->get('RESPONSE'), true);

$test->expect(
	$response['result'],
	"Set key"
);

// delete key
$f3->set('QUIET', true);
$f3->mock('POST @key_delete', array('project' => array('id' => $id), 'keys' => array($key)));
$f3->set('QUIET', false);

// check if key exists
$f3->set('QUIET', true);
$f3->mock('GET @key_exists(@project_id='.$id.', @key='.$key.')');
$f3->set('QUIET', false);

$response = json_decode($f3->get('RESPONSE'), true);

$test->expect(
	!$response['result'],
	"Delete key"
);

// delete project
$f3->set('QUIET', true);
$f3->mock('POST @project_delete', array('project' => array('id' => $id)));
$f3->set('QUIET', false);

// return results
$test_data = array();
$test_data['category'] = "Keys/CRUD";
$test_data['name'] = 'Simple delete key';
$test_data['results'] = $test->results();
$test_data['status'] = $test->passed();
$f3->push('tests', $test_data);

?>