<?php 
require_once __DIR__.'/../vendor/autoload.php';

$app= new Silex\Application();

$app->get('/allo',function(){
return 'Hello world';
});

$app->run();