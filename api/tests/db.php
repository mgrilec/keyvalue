<?php

$db = new DB\Jig ('db/');
$f3->set('project', new DB\Jig\Mapper($db, 'projects'));
$f3->set('key', new DB\Jig\Mapper($db, 'keys'));

?>