<?php

$test = new Test;

// create a project
$f3->set('QUIET', true);
$f3->mock('POST @project_create', array('project_title' => 'testtest'));
$f3->set('QUIET', false);

$id = $f3->get('RESPONSE');

// delete the project
$f3->set('QUIET', true);
$f3->mock('POST @project_delete', array('project_id' => $id));
$f3->set('QUIET', false);

// check if the project is in the database
$deleted = $f3->get('project')->count(array('@id=?', $id)) == 0;

$test->expect($deleted, 'Delete project');

// return results
$test_data = array();
$test_data['name'] = 'Simple delete project';
$test_data['results'] = $test->results();
$test_data['status'] = $test->passed();
$f3->push('tests', $test_data);

?>