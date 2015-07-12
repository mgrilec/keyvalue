<?php

$test = new Test;

// create a project
$f3->set('QUIET', true);
$f3->mock('POST @project_create', array('project' => array('title' => rand())));
$f3->set('QUIET', false);

$id = json_decode($f3->get('RESPONSE'), true)['result'];

// prepare keys
$keys = array(rand(), rand());
$values = array(rand(), rand());

// set keys
$f3->set('QUIET', true);
$f3->mock('POST @key_set', array('project' => array('id' => $id), 'keys' => $keys, 'values' => $values));
$f3->set('QUIET', false);

// get all keys
$f3->set('QUIET', true);
$f3->mock('GET @keys(@project_id='.$id.')');
$f3->set('QUIET', false);

$saved = json_decode($f3->get('RESPONSE'), true)['result'];
$saved_keys = array_keys($saved);
$saved_values = array_values($saved);

$test->expect(
	!array_diff($saved_keys, $keys) && !array_diff($saved_values, $values),
	"Set multiple keys"
);

// delete project
$f3->set('QUIET', true);
$f3->mock('POST @project_delete', array('project' => array('id' => $id)));
$f3->set('QUIET', false);

// return results
$test_data = array();
$test_data['category'] = "Keys/CRUD";
$test_data['name'] = 'Multi set key';
$test_data['results'] = $test->results();
$test_data['status'] = $test->passed();
$f3->push('tests', $test_data);

?>