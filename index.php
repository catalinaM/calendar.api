<?php
require 'vendor/autoload.php';
defined('APP_ENV')
|| define('APP_ENV', (getenv('APP_ENV') ? getenv('APP_ENV') : 'local'));

defined('APP_PATH')
|| define('APP_PATH', realpath(dirname(__FILE__)));

define('SECRET_KEY', "sdh723ehawd");
$config = parse_ini_file(APP_PATH . "/config/application.ini", true);
//TODO: create an registry class for keeping $config data
$config = (isset($config[APP_ENV])) ? $config[APP_ENV] : array();

$loader = new \Aura\Autoload\Loader;
$loader->register();

$loader->setPrefixes(array(
    'Controllers' => array(
        __DIR__ .'/controllers',
    ),
    'App' => array(
        __DIR__ .'/library/app',
    ),
    'Models' => array(
        __DIR__ .'/models',
    ),
    'Mappers' => array(
        __DIR__ .'/models/mappers',
    ),
));
$loader->setClassFile('DB\MysqliDb', __DIR__.'/vendor/joshcam/mysqli-database-class/MysqliDb.php');

$db = new MysqliDb ($config['db_conf']);
$frontController = new App\FrontController();
$frontController->run();
