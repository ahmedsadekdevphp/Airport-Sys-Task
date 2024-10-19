<?php
require_once('../core/Localization.php');
require_once('../config/Config.php');

function trans($key) {
    return Localization::translate($key);
}

function config($key) {
    return Config::get($key); 
}
