<?php
require_once '../core/Router.php';
require_once '../app/controllers/RegisterController.php';
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/UsersController.php';

$router = new Router();

$router->add('POST', 'register', 'RegisterController@register');
$router->add('POST', 'login', 'AuthController@login');
$router->add('GET', 'users', 'UsersController@index');
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Parse to get the path only
$router->dispatch($url); // Dispatch the parsed URL
