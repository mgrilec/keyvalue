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
	'Create project'
);

// delete project from the database
if (!$project->dry()) {
	$project->erase();
	$project->save();
}

$test_data = array();
$test_data['name'] = 'Simple create project';
$test_data['results'] = $test->results();
$test_data['status'] = $test->passed();


// return results
$f3->push('tests', $test_data);

?>