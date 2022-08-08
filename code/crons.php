<?php
/**
 * crons
 *
 * this file executes scripts from a /crons folder at various intervals which can be programmatically detected
 * this particular file - should be set to execute every minute
 * crons are stored as scripts within the /crons/ folder
 *      sub folders are created for every interval required of a cron IN SECONDS
 *
 * NOTE: accuracy of this cron execution is honestly really janky it's built to just LOOSELY run stuff, and not accurately run stuff
 * for true accuracy it's highly recommended you run things via the built in OS cron system/schedular systems
 *
 * @author Johnathan Tiong <johnathan.tiong@gmail.com>
 * @copyright 2022 Johnathan Tiong
 */

// root constant
define('ROOT', dirname(__FILE__));

// file location constants
define('ctrlr', ROOT . '/app/controllers');
define('model', ROOT . '/app/models');
define('views', ROOT . '/app/views');

// autoload composer
require 'vendor/autoload.php';

require ROOT . '/config.php';
require ROOT . '/R.php';

R::setup(getConfig('database.type') . ':host=' . getConfig('database.host') . ';dbname=' . getConfig('database.name'), getConfig('database.user'), getConfig('database.pass'));
R::ext('xdispense', function ($type) {
    return R::getRedBean()->dispense($type);
});

// echo R::testConnection() ? 'connected to the DB' : 'not connected to the DB'; die();

// @todo some sort of multithreaded execution of cron task files

// get the current time
// we need to build in a tolerance of +/- 29 seconds around the timers
// $currentTime    = time();
// $fuzzyTimeStart = $currentTime - 29;
// $fuzzyTimeEnd   = $currentTime + 29;

// every minute let's execute these
// these can be a problem because these files all run sequentially
foreach (glob(ROOT . "/crons/*.php") as $filename) {
    include $filename;
}
