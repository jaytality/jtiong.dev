<?php

/**
 * database.php
 *
 * connecting to the database - primarily used to cron tasks
 *
 */
define('ROOT', dirname(__FILE__));

require 'vendor/autoload.php';
require_once ROOT . '/config.php';

use Illuminate\Database\Capsule\Manager as Capsule;
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

// include all functions
foreach (glob(ROOT . "/functions/*.php") as $filename) {
    include $filename;
}