<?php
use App\Middleware\AuthMiddleware;

$app->group('', function() use ($app) {
	$app->get('/', 'HomeController:index')->setName('home');
});

$app->group('/auth', function() use ($app) {
	$app->get('', 'AuthController:signin')->setName('signin');
	$app->post('', 'AuthController:postSignin');
})->add($container->get('csrf'));