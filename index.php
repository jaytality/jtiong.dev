<?php

/**
 * @author Johnathan Tiong <johnathan.tiong@gmail.com>
 * @copyright 2016-09-01 Johnathan Tiong
 */

session_start();

// root constant
define('ROOT', dirname(__FILE__));

// file location constants
define('ctrlr', ROOT . '/app/controllers');
define('model', ROOT . '/app/models');
define('views', ROOT . '/app/views');

// autoload composer
require_once ROOT . '/vendor/autoload.php';
require_once ROOT . '/bootstrap.php';
require_once ROOT . '/database.php';

$base = new \spark\Core\Base();

// SITE TIMEZONE
date_default_timezone_set('Australia/Sydney');

// SETUP error logging
// error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('log_errors', 1);
ini_set('display_errors', 1);

include ROOT . '/routing.php';

// end of file
