<?php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();


$app->get('/', function(){
	return 'Index';
});
$app->get('/hello', function() {
    return 'Hello!';
});

$app->run();