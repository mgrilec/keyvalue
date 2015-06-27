<?php

$test = new Test;

// create a project
$f3->set('QUIET', true);
$f3->mock('POST @project_create', array('project_title' => 'testtest'));
$f3->set('QUIET', false);

$id = $f3->get('RESPONSE');

// check if the project is in the database
$created = $f3->get('project')->count(array('@id=?', $id)) > 0;

$test->expect(
	$created,
	'Create project'
);

// delete the project
$f3->set('QUIET', true);
$f3->mock('POST @project_delete', array('project_id' => $id));
$f3->set('QUIET', false);

// check if the project is in the database
$deleted = $f3->get('project')->count(array('@id=?', $id)) == 0;

$test->expect(
	$deleted,
	'Delete project'
);

// return results
$f3->push('results', $test->results());

?>