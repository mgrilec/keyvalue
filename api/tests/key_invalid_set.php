<?php

$test = new Test;

// create a project
$f3->set('QUIET', true);
$f3->mock('POST @project_create', array('project' => array('title' => rand())));
$f3->set('QUIET', false);

$id = json_decode($f3->get('RESPONSE'), true)['result'];

// prepare key
$key = rand();
$invalid_key = '';
$value = rand();
$invalid_value = '';

// set key with invalid key
$f3->set('QUIET', true);
$f3->mock('POST @key_set', array('project_id' => $id, 'keys' => array($invalid_key), 'values' => array($value)));
$f3->set('QUIET', false);

// check if key exists
$f3->set('QUIET', true);
$f3->mock('GET @key_exists(@project_id='.$id.', @key='.$key.')');
$f3->set('QUIET', false);

$response = json_decode($f3->get('RESPONSE'), true);

$test->expect(
	!$response['result'],
	"Set key with invalid key"
);

// set key with invalid value
$f3->set('QUIET', true);
$f3->mock('POST @key_set', array('project_id' => $id, 'keys' => array($key), 'values' => array($invalid_value)));
$f3->set('QUIET', false);

// check if key exists
$f3->set('QUIET', true);
$f3->mock('GET @key_exists(@project_id='.$id.', @key='.$key.')');
$f3->set('QUIET', false);

$response = json_decode($f3->get('RESPONSE'), true);

$test->expect(
	!$response['result'],
	"Set key with invalid value"
);

// delete project
$f3->set('QUIET', true);
$f3->mock('POST @project_delete', array('project' => array('id' => $id)));
$f3->set('QUIET', false);

// return results
$test_data = array();
$test_data['category'] = "Keys";
$test_data['name'] = 'Invalid key set';
$test_data['results'] = $test->results();
$test_data['status'] = $test->passed();
$f3->push('tests', $test_data);

?>