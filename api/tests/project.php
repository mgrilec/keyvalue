<?php

$test = new Test;

// create project
$f3->set('QUIET', true);
$f3->mock('POST /create', array('project_title' => 'test123'));
$project_id = $f3->get('RESPONSE');

$f3->mock('GET /projects');
$all_projects = json_decode($f3->get('RESPONSE'));
$f3->set('QUIET', false);

$test->expect(
  in_array($project_id, $all_projects),
  'Create project'
);

// delete project
$f3->set('QUIET', true);
$f3->mock('POST /delete', array('project_id' => $project_id));
$f3->set('QUIET', false);
$delete_result = json_decode($f3->get('RESPONSE'), true);
$test->expect(
  $delete_result['result'],
  'Delete project'
);

$f3->push('results', $test->results());

?>