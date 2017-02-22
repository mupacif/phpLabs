<?php

/*namespace MicroCMS\Tests;*/

require_once __DIR__.'/../vendor/autoload.php';

use Silex\WebTestCase;

class AppTest extends WebTestCase
{

    public function testAddInterro()
    {
        $client = $this->createClient();
    $client->request('POST', '/addInterro', 
         array('nom' => 'dumbo'));

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

      public function testAddQuestion()
    {
        $client = $this->createClient();
    $client->request('POST', '/addQuestion', 
         array('question' => 'who I am?','answer'=>'dumbo','idInterro'=>'1'));

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * {@inheritDoc}
     */
    public function createApplication()
    {


$app= new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider());
$app['db.options'] = array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/testDb.db',
    );


require __DIR__.'/route.php';

        
        // Generate raw exceptions instead of HTML pages if errors occur
        unset($app['exception_handler']);
        // Simulate sessions for testing
        $app['session.test'] = true;
        // Enable anonymous access to admin zone
        $app['security.access_rules'] = array();

        return $app;
    }

    
}