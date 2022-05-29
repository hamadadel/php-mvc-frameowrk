<?php
require_once '../vendor/autoload.php';
use Framework\Routing\Router;

$router = new Router();

$routes = require_once __DIR__ . '/../app/routes.php';
$routes($router);
echo $router->dispatch();