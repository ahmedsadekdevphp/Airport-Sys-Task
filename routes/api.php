<?php
require_once '../core/Router.php';
require_once '../app/controllers/RegisterController.php';

$router = new Router();

$router->add('POST', 'register', 'RegisterController@register');

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 
$router->dispatch($_SERVER['REQUEST_URI']);
