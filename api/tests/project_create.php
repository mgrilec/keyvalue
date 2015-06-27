<?php

$test = new Test;

// create a project
$f3->set('QUIET', true);
$f3->mock('POST @project_create', array('project_title' => 'testtest'));
$f3->set('QUIET', false);

$id = $f3->get('RESPONSE');

// get project from db
$project = $f3->get('project')->load(array('@id=?', $id));

// check if its in the database
$test->expect(
	!$project->dry(),
	'Simple create project'
);

// delete project from the database
if (!$project->dry()) {
	$project->erase();
	$project->save();
}

// return results
$f3->push('results', $test->results());

?>