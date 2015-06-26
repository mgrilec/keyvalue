<?php
	$f3 = require('fatfree/lib/base.php');

	$f3->route('GET /',
	    function($f3) {
	    	$template = new Template;
	        echo $template->render('projects.html');
	    }
	);

	$f3->route('GET /@project_id',
	    function($f3, $params) {

	    	// prepare template
	    	$template = new Template;

	    	// get params
	    	$project_id = $params['project_id'];

	    	// check project id
	    	if (ctype_digit($project_id)) {
	    		$project_id = intval($project_id);
	    	}
	    	else {
	    		$f3->reroute('/');
    		}

	    	$template = new Template;
	        echo $template->render('project.html');
	    }
	);

	$f3->run();
?>