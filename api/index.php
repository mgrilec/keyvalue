<?php

$f3 = require("include/fatfree/lib/base.php");

// optimus
require("include/optimus.php");
$f3->set('optimus', new Optimus(14278211, 48684651, 1792568627));
function optimus_encode($f3, $id) 
{
	return $f3->get('optimus')->encode(hexdec(explode('.', $id)[0])); 
}

// database
$db = new DB\Jig ('db/');
$f3->set('project', new DB\Jig\Mapper($db, 'projects'));
$f3->set('key', new DB\Jig\Mapper($db, 'keys'));

// autoload
$f3->set('AUTOLOAD', 'handlers/');

function project_exists($f3, $project_id) {
	return $f3->get('project')->count(array('@id=?', $project_id)) > 0;
}

// home page
$f3->route('GET /',
    function() {
        echo 'Hello, world!';
    }
);

// get all keys from a project
$f3->route('GET /@project_id', 
	function($f3) {

		// get all keys from project
		$keys = $f3->get('key')->find(array('@project_id=?', $f3->get['REQUEST.project_id']));

		// build data
		$data = array();
		foreach ($keys as $key) {
			$row = $key->cast();
			unset($row["_id"]);
			$data[] = $row;
		}

		echo json_encode($data);
	}
);

// sets keys to a project
$f3->route('POST /set', 
	function($f3) {

		$project_id = $f3->get('REQUEST.project_id');

		// validate project id
		if (!project_exists($project_id)) {
			die();
		}

		// get mapper
		$key = $f3->get('key');

		// save each key
		$keys = $f3->get('REQUEST.keys');
		$values = $f3->get('REQUEST.values');
		$count = min(count($keys), count($values));
		for ($index = 0; $index < $count; $index++) {
			$key->reset();
			$key->project_id = $project_id;
			$key->key = $keys[$index];
			$key->value = $values[$index];
			$key->save();
		}

		// return number of keys saved
		echo $count;
	}
);

// get all projects
$f3->route('GET @projects: /projects', 'Projects->All');

// create a new project
$f3->route('POST @project_create: /project/create', 'Projects->Create');

// delete a project
$f3->route('POST @project_delete: /project/delete', 'Projects->Delete');

// tests
$f3->route('GET /test', 
	function($f3) {

		// prepare results
		$f3->set('results', array());

		// run tests
		foreach (glob("tests/*.php") as $filename)
		{
		    include $filename;
		}
		
		// display results
		$template = new Template;
		echo $template->render('ui/tests.html');

		// cleanup
		$f3->clear('results');
	}
);

$f3->run();

?>