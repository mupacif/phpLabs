<?php 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->post('/addInterro', function (Request $request) use($app) {
    $nom = $request->get('nom');
    $app['db']->insert('interro', array('nom'=>$nom));

    return new Response("ok",200);
});


$app->post('/addQuestion', function (Request $request) use($app) {
    $question = $request->get('question');
    $answer = $request->get('answer');
    $interro = $request->get('idInterro');
    $app['db']->insert('question', array('question'=>$question,'answer'=>$answer,'idInterro'=>$interro));

    return new Response("ok",200);
});