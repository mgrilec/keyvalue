<?php

$test = new Test;

// create a project
$f3->set('QUIET', true);
$f3->mock('POST @project_create', array('project_title' => 'testtest'));
$f3->set('QUIET', false);

$id = $f3->get('RESPONSE');

// get all projects
$f3->set('QUIET', true);
$f3->mock('GET @projects');
$f3->set('QUIET', false);

$projects = json_decode($f3->get('RESPONSE'), true);

$exists = false;
foreach ($projects as $project) {
	if ($project['id'] == $id) {
		$exists = true;
		break;
	}
}

// check if project exists
$test->expect($exists, 'Create project');

// delete project from the database
$f3->get('project')->load(array('@id=?', $id))->erase();

// return results
$test_data = array();
$test_data['name'] = 'Simple create project';
$test_data['results'] = $test->results();
$test_data['status'] = $test->passed();
$f3->push('tests', $test_data);

?>