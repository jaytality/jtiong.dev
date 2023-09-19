<?php

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

if (!function_exists('getConfig')) {
    /**
     * getConfig
     * returns a config setting from .env.yaml
     *
     * @param string $name
     * @param string $default
     * @return $value
     */
    function getConfig($name = '', $default = '') {
        global $sparkConfig;

        // if no config is loaded, parse the .env.yaml
        if (empty($sparkConfig)) {
            $sparkConfig = yaml_parse_file(__DIR__ . '/.env.yaml');
        }

        // if there's no specific variable defined, return whole config array
        if (empty($name)) {
            return $sparkConfig;
        }

        $keys = explode('.', $name);

        if (empty($keys)) {
            return false;
        }

        $value = $sparkConfig;
        foreach ($keys as $key) {
            if (!isset($value[$key])) {
                $value = !empty($default) ? $default : null;
                break;
            }

            $value = $value[$key];
        }

        return $value;
    }
}

if (!function_exists('dd')) {
    /**
     * dd
     * laravel style dump and die
     *
     * @param $data
     * @return void
     */
    function dd($data)
    {
        echo '<pre>';
        die(var_dump($data));
        echo '</pre>';
    }
}

// end of file
