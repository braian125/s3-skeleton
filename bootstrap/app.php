<?php
/**
 * Bootstrap file
 *
 * @author Brian Vanegas Parra @braian125
 * @link https://github.com/braian125/s3-skeleton
 */
ini_set('session.cookie_httponly', 1);
session_name('app_session');
session_start();
header_remove("X-Powered-By");
require_once __DIR__ . '/../vendor/autoload.php';

if(file_exists( __DIR__ . '/../.env' )){	
	$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
	$dotenv->load();	
}else{
	$dotenvSettings = "";
	$_ENV['APP_TWIGDEBUG'] = "true";
	$_ENV['APP_DISPLAYERRORDETAILS'] = "true";
	$_ENV['DB_CONNECTION'] = "mysql";
	$_ENV['DB_HOST'] = "localhost";
	$_ENV['DB_DATABASE'] = "database";
	$_ENV['DB_USERNAME'] = "root";
	$_ENV['DB_PASSWORD'] = "";
	$_ENV['DB_PREFIX'] = "";
}

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

(isset($dotenvSettings)) ? $container['dotenv'] = $dotenvSettings : '';

$container['auth'] = function($container){
	return new \App\Auth\Auth;
};

$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

$container['csrf'] = function ($c) {
    return new \Slim\Csrf\Guard;
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
	$view->addExtension( new App\Views\CsrfExtension($container['csrf']));
	$view->addExtension(new \Twig_Extension_Debug());
	$view['baseUrl'] = $basePath;
	if(isset($_SESSION['user'])){
		$view->getEnvironment()->addGlobal('auth', [
			'check'	=> $container->auth->check(),
			'user'	=> $container->auth->user(),
		]);	
	}

	$view->getEnvironment()->addGlobal('flash', $container->flash);		
	(isset($container['dotenv'])) ? $view->getEnvironment()->addGlobal('dotenv','true') : '';
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