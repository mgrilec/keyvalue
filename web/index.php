<?php

$f3 = require('fatfree/lib/base.php');
$f3->set('api', new Api('../api/'));
$f3->set('UI', './templates/');
$f3->set('template', Template::instance());

$f3->route('GET /',
    function($f3, $params) {

        echo $f3->get('template')->render('template.html', 'text/html', array('content' => 'projects.html', 'js' => array('js/masonry.min.js', 'js/projects.js')));
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

        // $keys = $f3->get('REQUEST.keys');
        // $new_keys = $f3->get('REQUEST.new_keys');
        // $new_values = $f3->get('REQUEST.new_values');

        // $set_keys = array();
        // $set_values = array();
        // $unset_keys = array();
        // if ($keys) {
        //     foreach ($keys as $old_key => $value) {
        //         $new_key = $value['key'];
        //         $new_value = $value['value'];

        //         if ($old_key != $new_key) {
        //             $unset_keys[] = $old_key;
        //         }
            
        //         $set_keys[] = $new_key;
        //         $set_values[] = $new_value;
        //     }
        // }

        // // merge set with new keys
        // $set_keys = array_merge($set_keys, $new_keys);
        // $set_values = array_merge($set_values, $new_values);

        // // unset keys
        // $f3->get('api')->keys_delete($project['id'], $unset_keys);

        // // set keys
        // $f3->get('api')->keys_set($project['id'], $set_keys, $set_values);
        $f3->reroute('/'.$project['id']);
    }
);

$f3->route('GET /@project_id/delete',
	function($f3, $params) {

		// delete project
		$f3->get('api')->project_delete($params['project_id']);
		$f3->reroute('/');
	}
);

$f3->route('POST /@project_id/delete_key',
    function($f3, $params) {

        $keys = array();
        foreach ($f3->get('REQUEST.keys') as $key => $value) {
            $keys[] = $key;
        }

        $f3->get('api')->keys_delete($params['project_id'], $keys);
        $f3->reroute('/'.$params['project_id']);
    }
);

$f3->route('POST /@project_id/add_key',
    function($f3, $params) {

        $f3->get('api')->keys_set($params['project_id'], $f3->get('REQUEST.keys'), $f3->get('REQUEST.values'));
        $f3->reroute('/'.$params['project_id']);
    }
);

$f3->route('GET /@project_id',
    function($f3, $params) {

    	// get project
        $project = $f3->get('api')->project_get($params['project_id']);
        $project['keys'] = $f3->get('api')->keys_get_all($project['id']);
        echo $f3->get('template')->render('template.html', 'text/html', array('project' => $project, 'content' => 'edit.html'));
    }
);

$f3->run();

?>