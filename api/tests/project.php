<?php

$test = new Test;

// create project
$f3->set('QUIET', true);
$f3->mock('POST @project_create', array('project_title' => 'test123'));
$project_id = $f3->get('RESPONSE');

$f3->mock('GET @projects');
$all_projects = json_decode($f3->get('RESPONSE'), true);
$f3->set('QUIET', false);

$project_created = false;
foreach ($all_projects as $p) {
	if ($p['id'] == $project_id) {
		$project_created = true;
		break;
	}
}

$test->expect(
  $project_created,
  'Create project'
);

// delete project
$f3->set('QUIET', true);
$f3->mock('POST @project_delete', array('project_id' => $project_id));
$f3->set('QUIET', false);

$delete_result = json_decode($f3->get('RESPONSE'), true);

$test->expect(
  $delete_result['result'],
  'Delete project'
);

$f3->push('results', $test->results());

?>