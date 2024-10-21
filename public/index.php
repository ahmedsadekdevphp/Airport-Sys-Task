<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1); 
ini_set('error_log', __DIR__ . '/logs/error.log'); 
require '../vendor/autoload.php'; 
require_once '../core/ResponseCode.php'; 
require_once '../routes/api.php';
require_once  '../app/services/Helpers.php';



$router->dispatch(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
