<?php
require_once '../core/Router.php';
require_once '../app/controllers/RegisterController.php';
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/UsersController.php';
$router = new Router();

$router->add('POST', 'register', 'RegisterController@register');
$router->add('POST', 'login', 'AuthController@login');
$router->add('GET', 'users', 'UsersController@index');
$router->add('POST', 'users/activate/{user_id}', 'UsersController@activateUser');
$router->add('POST', 'users/disable/{user_id}', 'UsersController@disableUser');
$router->add('POST', 'users/reset/{user_id}', 'UsersController@resetPassword');

$router->add('GET', 'countries', 'CountryController@index');

$router->add('GET', 'airports', 'AirportsController@index');
$router->add('POST', 'airport/store', 'AirportsController@store');
$router->add('PUT', 'airport/update/{id}', 'AirportsController@update');
$router->add('DELETE', 'airport/delete/{id}', 'AirportsController@destory');
