<?php
require_once '../routes/api.php';
require_once  '../app/services/Helpers.php';
include_once(__DIR__ . '/../app/services/Response.php');



$router->dispatch(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
