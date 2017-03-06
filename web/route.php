<?php 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});


$app->post('/addSession', function (Request $request) use($app) {
    $idInterro = $request->get('idInterro');
    $score = $request->get('score');
    $app['db']->insert('session', array('idInterro'=>$idInterro,'note'=>$score,'date'=>date("Y-n-j")));

    return $app->json(array('id'=>$app['db']->lastInsertId()));
});
$app->post('/addInterro', function (Request $request) use($app) {
    $nom = $request->get('nom');
    $app['db']->insert('interro', array('nom'=>$nom));

    return $app->json(array('id'=>$app['db']->lastInsertId()));
});


$app->get('/test',function() use($app)
{
return $app->json(array('yosh'=>'now it works'));
});

$app->get('/interro/{id}',function($id) use($app)
{
	$sql = "select q.idInterro, q.question, q.answer, q.id from interro i join question q on i.id = q.idInterro and i.id= ?";
    $post = $app['db']->fetchAll($sql, array((int) $id));
return $app->json($post);
});

$app->get('/session/{id}',function($id) use($app)
{
	$sql = "select note, date from session where idInterro= ? order by id desc limit 3";
    $post = $app['db']->fetchAll($sql, array((int) $id));
return $app->json($post);
});


$app->get('/interros',function() use($app)
{

return $app->json($app['db']->fetchAll('select  i.id, nom,  avg(note)*100 as "note" from interro i left join session s on i.id = s.idInterro   group by nom order by i.id , s.idInterro, s.id '));
});

$app->post('/addQuestion', function (Request $request) use($app) {
    $question = $request->get('question');
    $answer = $request->get('answer');
    $interro = $request->get('idInterro');
    $ok = $app['db']->insert('question', array('question'=>$question,'answer'=>$answer,'idInterro'=>$interro));

    return  $app->json(array('id'=>$app['db']->lastInsertId()));
});


$app->post('/setQuestion', function (Request $request) use($app) {
    $idQuestion = $request->get('idQuestion');
    $answer = $request->get('answer');
    $ok = $app['db']->executeUpdate('UPDATE question SET answer = ? WHERE id = ?', array($answer, $idQuestion));

    return new Response("back:"+$ok,200);
});