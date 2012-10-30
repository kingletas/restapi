<?php

if (!isset($_SERVER['DEVELOPER_MODE_ON']) or true) {
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', 1);
}

define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('BP', dirname(__FILE__));
/**
 * Set include path
 */
$paths = array();
$paths[] = BP . DS . 'override';
$paths[] = BP . DS . 'views';
$paths[] = BP . DS . 'handlers';
$paths[] = BP . DS . 'lib';
$originalPath = get_include_path();
set_include_path(implode(PS, $paths) . PS . $originalPath);

//autoload
include_once 'Autoload.php';
Autoload::register();

Config::init();
