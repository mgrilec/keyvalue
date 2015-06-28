<?php

$test = new Test;

// create a project
$f3->set('QUIET', true);
$f3->mock('POST @project_create', array('project_title' => 'testtest'));
$f3->set('QUIET', false);

$id = $f3->get('RESPONSE');

// prepare keys
$key = 'test_key_a';
$value = 'a';

// set keys
$f3->set('QUIET', true);
$f3->mock('POST @key_set', array('project_id' => $id, 'keys' => array($key), 'values' => array($value)));
$f3->set('QUIET', false);

// get keys
$f3->set('QUIET', true);
$f3->mock('GET @key_get(@project_id='.$id.', @key='.$key.')');
$response = $f3->get('RESPONSE');
$f3->set('QUIET', false);

$test->expect(
	$response == $value,
	"Set key"
);

// delete key from the database
$f3->get('key')->load(array('@project_id=? and @key=?', $id, $key))->erase();

// delete project from the database
$f3->get('project')->load(array('@id=?', $id))->erase();

// return results
$test_data = array();
$test_data['name'] = 'Simple set key';
$test_data['results'] = $test->results();
$test_data['status'] = $test->passed();
$f3->push('tests', $test_data);

?>