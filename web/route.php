<?php 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});




// TEST

$app->get('/test',function() use($app)
{
return $app->json(array('yosh'=>'now it works'));
});

// INTERRO

$app->get('/interros/{idMatiere}',function($idMatiere) use($app)
{

return $app->json($app['db']->fetchAll('select  i.id, nom,  avg(note)*100 as "note" from interro i left join session s on i.id = s.idInterro   where i.idMatiere = ? group by nom order by i.id , s.idInterro, s.id ',array((int)$idMatiere)));
});

$app->get('/interro/{id}',function($id) use($app)
{
	$sql = "select q.idInterro, q.question, q.answer, q.id from interro i join question q on i.id = q.idInterro and i.id= ?";
    $post = $app['db']->fetchAll($sql, array((int) $id));
return $app->json($post);
});

$app->post('/addInterro', function (Request $request) use($app) {
    $nom = $request->get('nom');
    $idMatiere = $request->get('idMatiere');
    $app['db']->insert('interro', array('nom'=>$nom,'idMatiere'=>$idMatiere));

    return $app->json(array('id'=>$app['db']->lastInsertId()));
});

$app->delete('/interro/{id}',function($id) use($app)
{
    $app['db']->executeQuery("PRAGMA foreign_keys = ON");
    $post = $app['db']->delete('interro', array('id' => $id));
    return $app->json($post);
});


// QUESTIONS


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



// SESSION 
$app->get('/session/{id}',function($id) use($app)
{
	$sql = "select note, date from session where idInterro= ? order by id desc limit 3";
    $post = $app['db']->fetchAll($sql, array((int) $id));
return $app->json($post);
});

$app->post('/addSession', function (Request $request) use($app) {
    $idInterro = $request->get('idInterro');
    $score = $request->get('score');
    $app['db']->insert('session', array('idInterro'=>$idInterro,'note'=>$score,'date'=>date("Y-n-j")));

    return $app->json(array('id'=>$app['db']->lastInsertId()));
});



// MATIERE

$app->get('/matieres',function() use($app)
{
return $app->json($app['db']->fetchAll('select  id, nom from matiere'));
});


$app->post('/matiere', function(Request $request) use($app){
    $nom = $request->get('nom');
    $ok = $app['db']->insert('matiere', array('nom'=>$nom));
    return $app->json(array('id'=>$app['db']->lastInsertId()));

});


$app->delete('/matiere/{id}', function($id) use($app){
    $app['db']->executeQuery("PRAGMA foreign_keys = ON");
    $post = $app['db']->delete('matiere', array('id' => $id));
    return $app->json($post);

});