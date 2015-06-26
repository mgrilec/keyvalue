<?php

$f3 = require("include/fatfree/lib/base.php");

// optimus
require("include/optimus.php");
$f3->set('optimus', new Optimus(14278211, 48684651, 1792568627));
function optimus_encode($f3, $id) 
{ 
	return $f3->get('optimus')->encode(hexdec($_id)); 
}

// database
$db = new DB\Jig ('db/');
$f3->set('project', new DB\Jig\Mapper($db, 'projects'));
$f3->set('key', new DB\Jig\Mapper($db, 'keys'));

$f3->route('GET /',
    function() {
        echo 'Hello, world!';
    }
);

// get keys from a project
$f3->route('GET /get/@key',
    function($f3) {
        echo $f3->get('PARAMS.key');
    }
);

// sets keys to a project
$f3->route('POST /set', 
	function($f3, $params) {

		$project_id = $f3->get('POST.project_id');

		// TODO: validate project id

		$keys = $f3->get('POST.keys');
		$values = $f3->get('POST.values');
		foreach ($keys as $key) {

		}

	}
);

// creates a new project
$f3->route('POST /create', 
	function($f3, $params) {
		$project = $f3->get('project');

		// create new project entry
		$project->reset();
		$project->title = $f3->get('POST.project_title');
		$project->save();

		// return project id
		echo optimus_encode($f3, $project->_id);
	}
);

// lists all projects
$f3->route('GET /projects', 
	function($f3) {
		$project = $f3->get('project');
		$projects = $project->find();
		$data = array();

		foreach($projects as $p) {

			// turn mapper to array
			$row = $p->cast();

			print_r($f3->get('optimus.encode'));

			// transform the id field
			$row['id'] = optimus_encode($f3, $project->_id);

			// unset old id
			unset($row['_id']);

			// set to data
			$data[] = $row;
		}

		echo json_encode($data);
	}
);

// test for setting values
$f3->route('GET /test/set', 
	function($f3) {
		$template = new Template;
		echo $template->render('test/set.html');
	}
);

// test for creating a project
$f3->route('GET /test/create', 
	function($f3) {
		$template = new Template;
		echo $template->render('test/create.html');
	}
);

$f3->run();

?>