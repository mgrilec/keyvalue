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

$f3->route('POST /update', 
    function($f3, $params) {

        // update project
        $project = $f3->get('REQUEST.project');
        $f3->get('api')->project_update($project);
        $f3->reroute('/'.$project['id']);

        // add new keys
        $new_count = min($f3->get('REQUEST.new_keys'), $f3->get('REQUEST.new_values'));
        for ($index = 0; $index < $new_count; $index++) {
            
        }
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

    	// get project
        $project = $f3->get('api')->project_get($params['project_id']);
        $project['keys'] = $f3->get('api')->keys_get_all($project['id']);
        echo $f3->get('template')->render('edit.html', 'text/html', array('project' => $project));
    }
);

$f3->run();

?>