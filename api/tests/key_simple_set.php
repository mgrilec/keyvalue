<?php

$test = new Test;

// create a project
$f3->set('QUIET', true);
$f3->mock('POST @project_create', array('project_title' => 'testtest'));
$f3->set('QUIET', false);

$id = $f3->get('RESPONSE');

// prepare key
$key = rand();
$value = rand();

// set keys
$f3->set('QUIET', true);
$f3->mock('POST @key_set', array('project_id' => $id, 'keys' => array($key), 'values' => array($value)));
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

// unset key
$f3->set('QUIET', true);
$f3->mock('POST @key_delete', array('project_id' => $id, 'keys' => array($key)));
$f3->set('QUIET', false);

// delete project
$f3->set('QUIET', true);
$f3->mock('POST @project_delete', array('project_id' => $id));
$f3->set('QUIET', false);

// return results
$test_data = array();
$test_data['name'] = 'Simple set key';
$test_data['results'] = $test->results();
$test_data['status'] = $test->passed();
$f3->push('tests', $test_data);

?>