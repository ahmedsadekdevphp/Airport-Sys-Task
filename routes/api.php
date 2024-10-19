<?php
require_once '../core/Router.php';
require_once '../app/controllers/RegisterController.php';
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/UsersController.php';
$router = new Router();

$router->add('POST', 'register', 'RegisterController@register');
$router->add('POST', 'login', 'AuthController@login');
$router->add('GET', 'users', 'UsersController@index');
$router->add('POST', 'users/activate', 'UsersController@activateUser');
$router->add('POST', 'users/disable', 'UsersController@disableUser');
$router->add('POST', 'users/reset', 'UsersController@resetPassword');

