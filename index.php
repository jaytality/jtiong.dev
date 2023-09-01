<?php

/**
 * @author Johnathan Tiong <johnathan.tiong@gmail.com>
 * @copyright 2016-09-01 eLeague Pty Ltd
 */

session_start();

// root constant
define('ROOT', dirname(__FILE__));

// file location constants
define('ctrlr', ROOT . '/app/controllers');
define('model', ROOT . '/app/models');
define('views', ROOT . '/app/views');

// autoload composer
require ROOT . '/vendor/autoload.php';
require ROOT . '/bootstrap.php';

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * DB connection using Capsule
 */
$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => getConfig('database.host'),
    'database' => getConfig('database.name'),
    'username' => getConfig('database.user'),
    'password' => getConfig('database.pass'),
    'options' => [
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true,
        PDO::MYSQL_ATTR_SSL_KEY => '/etc/ssl/certs/ca-certificates.crt'
    ]
]);

$capsule->setAsGlobal();

$capsule->bootEloquent();

// database migrations
foreach (glob(ROOT . "/database/*.php") as $filename) {
    include $filename;
}

$base = new \spark\Core\Base();

// SITE TIMEZONE
date_default_timezone_set('Australia/Sydney');

// SETUP error logging
// error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('log_errors', 1);
ini_set('display_errors', 1);

include ROOT . '/routing.php';

// end of file
