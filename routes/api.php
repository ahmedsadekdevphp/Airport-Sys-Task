<?php
use Core\Router;
use App\Middlewares\RoleMiddleware;
use App\Middlewares\AuthMiddleware;
$router = new Router();

$router->add('POST', 'register', 'RegisterController@register');
$router->add('POST', 'login', 'AuthController@login');

$router->add('GET', 'users', 'UsersController@index', [AuthMiddleware::class, RoleMiddleware::class], ['admin']);
$router->add('PUT', 'users/role/{user_id}', 'UsersController@changeRole', [AuthMiddleware::class, RoleMiddleware::class], ['admin']);
$router->add('POST', 'users/activate/{user_id}', 'UsersController@activateUser', [AuthMiddleware::class, RoleMiddleware::class], ['admin']);
$router->add('POST', 'users/disable/{user_id}', 'UsersController@disableUser', [AuthMiddleware::class, RoleMiddleware::class], ['admin']);
$router->add('POST', 'users/reset/{user_id}', 'UsersController@resetPassword', [AuthMiddleware::class, RoleMiddleware::class], ['admin']);

$router->add('GET', 'countries', 'CountryController@index', [AuthMiddleware::class, RoleMiddleware::class], ['operator']);

$router->add('GET', 'airports', 'AirportsController@index');
$router->add('POST', 'airport/store', 'AirportsController@store', [AuthMiddleware::class, RoleMiddleware::class], ['operator']);
$router->add('PUT', 'airport/update/{id}', 'AirportsController@update', [AuthMiddleware::class, RoleMiddleware::class], ['operator']);
$router->add('DELETE', 'airport/delete/{id}', 'AirportsController@destory', [AuthMiddleware::class, RoleMiddleware::class], ['operator']);



$router->add('PUT', 'profile/update', 'ProfileController@update', [AuthMiddleware::class]);
$router->add('PUT', 'profile/password', 'ProfileController@changePassword', [AuthMiddleware::class]);


$router->add('GET', 'airport/name', 'AirportsController@getAirportByName');
$router->add('GET', 'airport/code', 'AirportsController@getAirportByCode');

