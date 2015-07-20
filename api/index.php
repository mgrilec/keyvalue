<?php

$f3 = require("fatfree/lib/base.php");

// read globals
$f3->config('globals.ini');

// include config
require("config.php");

// read routes
$f3->config('routes.ini');

// home page
$f3->route('GET /',
    function($f3) {
        $data = array(
        	'version' => $f3->get('version'),
        	'build' => $f3->get('build')
        );

        echo json_encode($data);
    }
);

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