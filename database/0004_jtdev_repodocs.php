<?php

/**
 * jtdev_repodocs are links to gitbook document spaces for various projects as needed
 */

use ILluminate\Database\Capsule\Manager as Capsule;

if (!Capsule::schema()->hasTable('jtdev_repodocs')) {
    Capsule::schema()->create('jtdev_repodocs', function($table) {
        $table->increments('id');
        $table->string('repo');                         // repository name
        $table->string('gitbook');                      // gitbook url
    });
}

/**
 * further migrations can be added below using things like
 * Capsule::schema()->hasColumn
 */

// end of file
