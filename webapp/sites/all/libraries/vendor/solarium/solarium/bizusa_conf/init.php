<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

require 'sites/all/libraries/vendor/autoload.php';
if (file_exists(__DIR__ . '/config.php')) {
    require(__DIR__ . '/config.php');
} else {
    require(__DIR__ . '/config.dist.php');
}
