<?php

$f3 = require('fatfree/lib/base.php');
$f3->set('api', new Api('../api/'));
$f3->set('template', Template::instance());

$f3->route('GET /',
    function($f3, $params) {

    	// get all projects
    	$projects = $f3->get('api')->projects();
    	array_unshift($projects, array('create' => true));

    	$rows = array();
    	for ($i = 0; $i < count($projects); $i++) {
    		$row = floor($i / 3);
    		$rows[$row][] = $projects[$i];
    	}

        echo $f3->get('template')->render('projects.html', 'text/html', array('rows' => $rows));
    }
);

$f3->route('POST /create', 
	function($f3, $params) {
		// create new project
		$response = $f3->get('api')->project_create($f3->get('POST.project_title'));
		$id = $response['id'];

		$f3->reroute('/');
	}
);

$f3->route('GET /@project_id/delete',
	function($f3, $params) {
		// delete project
		$f3->get('api')->project_delete($params['project_id']);
		$f3->reroute('/');
	}
);

$f3->route('GET /@project_id',
    function($f3, $params) {

    	// get params
    	$project_id = $params['project_id'];

    	// check project id
    	if (ctype_digit($project_id)) {
    		$project_id = intval($project_id);
    	}
    	else {
    		$f3->reroute('/');
		}

        echo $f3->get('template')->render('edit.html');
    }
);

$f3->run();

?>