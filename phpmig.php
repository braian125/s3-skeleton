<?php
date_default_timezone_set("America/Bogota");
require_once 'vendor/autoload.php';
$dotenv = new Dotenv\Dotenv(__DIR__ . '/');
$dotenv->load();

use Phpmig\Adapter;
use Pimple\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

$container = new Container();

$container['config'] = [
    'driver' => $_ENV['DB_CONNECTION'],
	'host'	=>	$_ENV['DB_HOST'],
	'database'	=> $_ENV['DB_DATABASE'],
	'username'	=> $_ENV['DB_USERNAME'],
	'password'	=> $_ENV['DB_PASSWORD'],
	'charset'	=> 'utf8',
	'collation' => 'utf8_unicode_ci',
	'prefix'	=> $_ENV['DB_PREFIX']
];

$container['db'] = function ($c) {
    $capsule = new Capsule();
    $capsule->addConnection($c['config']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

   return $capsule;
};

$container['phpmig.adapter'] = function($c) {
    return new Adapter\Illuminate\Database($c['db'], 'migrations');
};
$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

return $container;