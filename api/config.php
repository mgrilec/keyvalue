<?php

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

function project_exists($f3, $project_id) {
	return $f3->get('project')->count(array('@id=?', $project_id)) > 0;
}

?>