<?php
require dirname(__DIR__).'/vendor/autoload.php'; 
require_once  dirname(__DIR__).'/app/services/Helpers.php';

use Database\Filldata;

$fill = new Filldata();
$fill->seedCountry();