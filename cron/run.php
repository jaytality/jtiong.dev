<?php
/**
 * crons
 *
 * this file executes scripts from a /crons folder at various intervals which can be programmatically detected
 * this particular file - should be set to execute every minute
 *
 * @author Johnathan Tiong <johnathan.tiong@gmail.com>
 * @copyright 2022 Johnathan Tiong
 *
 * @version 2023-09-01 updating to use Eloquent, and lay the groundwork for a fully functioning cron setup
 */

// root constant
define('ROOT', dirname(__FILE__));

// file location constants
define('ctrlr', ROOT . '/app/controllers');
define('model', ROOT . '/app/models');
define('views', ROOT . '/app/views');

// autoload composer
require ROOT . '/vendor/autoload.php';
require ROOT . '/bootstrap.php';

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

// @todo some sort of multithreaded execution of cron task files

// every minute let's execute these
// these can be a problem because these files all run sequentially
foreach (glob(ROOT . "/crons/*.php") as $filename) {
    include $filename;
}
