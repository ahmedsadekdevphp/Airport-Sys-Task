<?php
require_once('../core/Localization.php');

function trans($key) {
    return Localization::translate($key);
}
