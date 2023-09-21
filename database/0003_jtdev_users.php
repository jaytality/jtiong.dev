<?php

use ILluminate\Database\Capsule\Manager as Capsule;

if (!Capsule::schema()->hasTable('jtdev_users')) {
    Capsule::schema()->create('jtdev_users', function($table) {
        $table->increments('id');
        $table->string('email');                        // email
        $table->string('name');                         // name
        $table->string('password');                     // password
        $table->integer('created');                     //
        $table->integer('updated');                     //
        $table->integer('lastonline');                  //
        $table->boolean('disabled')->default(false);    // account can be disabled
    });
}

/**
 * further migrations can be added below using things like
 * Capsule::schema()->hasColumn
 */

// end of file
