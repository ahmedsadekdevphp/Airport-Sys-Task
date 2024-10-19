<?php
require_once '../config/config.php';
require_once '../routes/api.php';
require_once  '../app/services/Helpers.php';
include_once(__DIR__ . '/../app/services/Response.php');

spl_autoload_register(function ($className) {
    require_once '../core/' . $className . '.php';
});

$router->dispatch(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
