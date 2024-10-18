<?php
require_once '../config/config.php';
require_once '../routes/api.php';

spl_autoload_register(function ($className) {
    require_once '../core/' . $className . '.php';
});

$router = new Router();
$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
