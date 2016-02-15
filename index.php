<?php
error_reporting(E_ALL);

require_once(__DIR__.'/vendor/autoload.php');
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

try {
    $db = new MysqliDb (Array (
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'db'=> 'calendars',
        'port' => 3306,
        'prefix' => '',
        'charset' => 'utf8'));

} catch (Exception $ex){
    print_r($ex);
}

define('SECRET_KEY', "sdh723ehawd");


$frontController = new App\FrontController();
$frontController->run();
