<?php

$test = new Test;

// create a project
$title = rand();
$f3->set('QUIET', true);
$f3->mock('POST @project_create', array('project_title' => $title));
$f3->set('QUIET', false);
$id = json_decode($f3->get('RESPONSE'), true)['id'];

// get project
$f3->set('QUIET', true);
$f3->mock('GET @project_get', array('project_id' => $id));
$f3->set('QUIET', false);
$project = json_decode($f3->get('RESPONSE'), true)['data'];

// modify the project
$new_title = rand();
$project['title'] = $new_title;

// update the project
$f3->set('QUIET', true);
$f3->mock('POST @project_update', array('project' => $project));
$f3->set('QUIET', false);

// get project
$f3->set('QUIET', true);
$f3->mock('GET @project_get', array('project_id' => $id));
$f3->set('QUIET', false);
$project = json_decode($f3->get('RESPONSE'), true)['data'];

$test->expect(
	$project['title'] == $new_title, 
	"Update project"
);

// delete the project
$f3->set('QUIET', true);
$f3->mock('POST @project_delete', array('project_id' => $id));
$f3->set('QUIET', false);

// return results
$test_data = array();
$test_data['name'] = 'Simple update project';
$test_data['results'] = $test->results();
$test_data['status'] = $test->passed();
$f3->push('tests', $test_data);

?>