<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();

$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => $_ENV['APP_DISPLAYERRORDETAILS'],
	    'db' => [
	    	'driver' => $_ENV['DB_CONNECTION'],
	    	'host'	=>	$_ENV['DB_HOST'],
	    	'database'	=> $_ENV['DB_DATABASE'],
	    	'username'	=> $_ENV['DB_USERNAME'],
	    	'password'	=> $_ENV['DB_PASSWORD'],
	    	'charset'	=> 'utf8',
	    	'collation' => 'utf8_unicode_ci',
	    	'prefix'	=> $_ENV['DB_PREFIX']
	    ]
    ]
]);

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function($container) use($capsule) {
	return $capsule;
};

$container['auth'] = function($container){
	return new \App\Auth\Auth;
};

// Twig
$container['view'] = function ($container) {
	$view = new Slim\Views\Twig(__DIR__ . '/../resources/views', [
		'debug' => $_ENV['APP_TWIGDEBUG'],
		'cache' => false,
	]);
	$uri = explode("/",$container['request']->getUri());
	$basePath = $uri[0]."//".$uri[2]."/public";
	$view->addExtension( new \Slim\Views\TwigExtension(
		$container->router,
		$basePath,
		$container->request->getUri()
	));
	$view->addExtension(new \Twig_Extension_Debug());
	$view['baseUrl'] = $basePath;
	if(isset($_SESSION['user'])){
		$view->getEnvironment()->addGlobal('auth', [
			'check'	=> $container->auth->check(),
			'user'	=> $container->auth->user(),
		]);	
	}	
	return $view;
};

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['view']->render($response->withStatus(404), 'errors/404.twig', [
            "myMagic" => "Let's roll"
        ]);
    };
};

/**
 * Special Controllers
 */
$container['AuthController'] = function($container){
	return new \App\Controller\Auth\AuthController($container);
};


/**
 * Custom Controllers
 */
require_once __DIR__ . '/CallableControllers.php';


require_once __DIR__ . '/../app/routes.php';
