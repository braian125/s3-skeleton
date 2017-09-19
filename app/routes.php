<?php
use App\Middleware\AuthMiddleware;

$app->group('', function() use ($app) {
	$app->get('/', 'HomeController:index')->setName('home');
}); //->add(new AuthMiddleware($container));

$app->get('/auth', 'AuthController:signin')->setName('signin');
$app->post('/auth', 'AuthController:postSignin');