<?php
	$f3 = require('fatfree/lib/base.php');
	$f3->set('api', new Api('../api/'));
	$f3->set('template', Template::instance());

	$f3->route('GET /',
	    function($f3) {

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