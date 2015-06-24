<?php

$f3 = require("fatfree/lib/base.php");

$f3->route('GET /',
    function() {
        echo 'Hello, world!';
    }
);

$f3->route('GET /get/@key',
    function($f3) {
        echo $f3->get('PARAMS.key');
    }
);

$f3->run();

?>