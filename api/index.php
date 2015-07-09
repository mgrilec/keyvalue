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

// sets keys
$f3->route('POST @key_set: /keys/set', 'Keys->Set');

// check if a key exists
$f3->route('GET @key_exists: /keys/@project_id/@key/exists', 'Keys->Exists');

// get key count
$f3->route('GET @key_count: /keys/@project_id/count', 'Keys->Count');

// gets a single key
$f3->route('GET @key_get: /keys/@project_id/@key', 'Keys->Get');

// gets all keys
$f3->route('GET @keys: /keys/@project_id', 'Keys->GetAll');

// unset a key
$f3->route('POST @key_delete: /keys/delete', 'Keys->Delete');

// create a new project
$f3->route('POST @project_create: /projects/create', 'Projects->Create');

// check if a project exists
$f3->route('GET @project_exists: /projects/@project_id/exists', 'Projects->Exists');

// get a single project
$f3->route('GET @project_get: /projects/@project_id', 'Projects->Get');

// get all projects
$f3->route('GET @projects: /projects', 'Projects->GetAll');

// update a project
$f3->route('POST @project_update: /projects/@project_id/update', 'Projects->Update');

// delete a project
$f3->route('POST @project_delete: /projects/delete', 'Projects->Delete');

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

		// sort test by category
		$f3->set('categories', array());
		foreach ($f3->get('tests') as $test) {
			$f3->push('categories.' . $test['category'], $test);
		}

		$f3->clear('tests');

		// display results
		$template = new Template;
		echo $template->render('ui/tests.html');

		// cleanup
		$f3->clear('results');
	}
);

$f3->run();

?>