<?php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\BaseHttpException;
use Symfony\Component\HttpKernel\NotFoundHttpException;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Extension\UrlGeneratorExtension;
use Silex\Extension\TwigExtension;
use Silex\Extension\SymfonyBridgesExtension;

$app = new Silex\Application();

// Debug Mode = TRUE (dev)
$app['debug'] = true;

$app->register(new FormServiceProvider());

$app->register(new TwigServiceProvider(),[
    'twig.path' => __DIR__.'/../view',
]);


// Controllers Code

$blogPosts = array(
	1 => array(
		'date'      => '2014-05-06',
        'author'    => '@j05145',
        'title'     => 'Using Silex',
        'body'      => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellat, a, quae quis quod soluta porro dolores? Illum, beatae quaerat ut quam consequuntur debitis distinctio sit voluptate eum non maxime accusantium.',
	),
);

$app->get('/blog', function() use ($blogPosts) {
    $output = '';
    foreach ($blogPosts as $post) {
        $output .= $post['title'];
        $output .= '<br />';
    }

    return $output;
});


/**
 * Dynamic Routes for Blogs Posts
 * Passing {id} value as an argument  to the closure
 * abort() Method is used if requested post doesn't exist
 */
$app->get('/blog/show/{id}', function (Silex\Application $app, $id) use ($blogPosts) {
    if (!isset($blogPosts[$id])) {
        $app->abort(404, "El post $id no existe.");
    }
 
    $post = $blogPosts[$id];
 
    return  "<h1>{$post['title']}</h1>".
            "<p>{$post['body']}</p>";
});



/**
 * User login
 */
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbhost' => 'localhost',
        'dbname' => 'mydbname',
        'user' => 'root',
        'password' => '',
    ),
));

/*$app->register(new Silex\Provider\SessionServiceProvider());*/



$app->get('/', function(Silex\Application $app){
	$twigvars = array();

    $twigvars['title'] = "Silex App";

    $twigvars['content'] = "Lorem  ipsum    ";

    return $app['twig']->render('index.html.twig', $twigvars);
});
$app->get('/hello', function() {
    return 'Hello!';
});

$app->run();