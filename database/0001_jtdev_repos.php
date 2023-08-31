<?php

use ILluminate\Database\Capsule\Manager as Capsule;

if (!Capsule::schema()->hasTable('jtdev_repos')) {
    Capsule::schema()->create('jtdev_repos', function($table) {
        $table->increments('id');
        $table->string('name');
        $table->date('date');
        $table->boolean('visible')->default(true);
        $table->text('description')->nullable();
    });
}

/**
 * further migrations can be added below using things like
 * Capsule::schema()->hasColumn
 */

// end of file
