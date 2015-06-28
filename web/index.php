<?php

$f3 = require('fatfree/lib/base.php');
$f3->set('api', new Api('../api/'));
$f3->set('template', Template::instance());

$f3->route('GET /',
    function($f3, $params) {

    	// get all projects
    	$projects = $f3->get('api')->projects();
    	array_unshift($projects, array('create' => true));

    	// get key count
        for ($index = 0 ; $index < count($projects); $index++) {
            $count = $f3->get('api')->keys_count($projects[$index]['id']);
            $projects[$index]['count'] = $count;
        }

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
		$f3->get('api')->project_create($f3->get('REQUEST.project_title'), $f3->get('REQUEST.project_description'));
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

        echo $f3->get('template')->render('edit.html');
    }
);

$f3->run();

?>