<?php

use ILluminate\Database\Capsule\Manager as Capsule;

if (!Capsule::schema()->hasTable('jtdev_commits')) {
    Capsule::schema()->create('jtdev_commits', function($table) {
        $table->increments('id');
        $table->text('sha');
        $table->timestamp('date');                      // when the commit happened
        $table->string('repo_id');                      // name of the repository
        $table->string('repo_name');
        $table->text('message')->nullable();            // commit full message
        $table->text('author');
        $table->text('email');
        $table->text('url');
        $table->boolean('visible')->default(true);      // is it visible on jtiong.dev
    });
}

/**
 * further migrations can be added below using things like
 * Capsule::schema()->hasColumn
 */

// end of file
