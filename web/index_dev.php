<?php

error_reporting(E_ALL);

use Symfony\Component\ClassLoader\DebugClassLoader;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\BaseHttpException;
use Symfony\Component\HttpKernel\NotFoundHttpException;


if(isset($_SERVER['HTTP_CLIENT_IP']) || isset($_SEVER['HTTP_X_FOWARDED_FOR']) || in_array(@$_SEVER['REMOTE_ADDR'], array(
    '127.0.0.1',
    '::1'
    ))
){
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to acces this file. Check'.basename(__FILE__).' for')
}

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;



use Silex\Extension\UrlGeneratorExtension;
use Silex\Extension\TwigExtension;
use Silex\Extension\SymfonyBridgesExtension;


$app = new Application();

// Debug Mode = TRUE (dev)
$app['debug'] = true;

$app->register(new UrlGeneratorExtension(), array());l
$app->register(new SymfonyBridgesExtension(), array(
    'symfony_briges.class_path'=> __DIR__.'/../vendor/symfony/src'
));
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
 * Dynamic Routes for Blogs Postsl
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

$app->get('/', function(){
	return 'Index';
});
$app->get('/hello', function() {
    return 'Hello!';
});

$app->run();