<?php

// hashids
$f3->set('hashids', new Hashids("Tb3xRxkE"));

// database
$db = new DB\Jig ('db/');
$f3->set('project', new DB\Jig\Mapper($db, 'projects'));
$f3->set('key', new DB\Jig\Mapper($db, 'keys'));

function project_exists($f3, $project_id) {
	return $f3->get('project')->count(array('@id=?', $project_id)) > 0;
}

?>