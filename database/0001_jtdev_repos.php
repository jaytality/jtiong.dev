<?php

use ILluminate\Database\Capsule\Manager as Capsule;

if (!Capsule::schema()->hasTable('jtdev_repos')) {
    Capsule::schema()->create('jtdev_repos', function($table) {
        $table->increments('id');
        $table->string('github');                       // github (node_id)
        $table->string('name');                         // (name)
        $table->timestamp('created');                   // created timestamp (created_at)
        $table->boolean('visible')->default(true);      // visible on jtiong.dev (if no, all commits are shielded)
        $table->text('description')->nullable();        // (description)
        $table->text('url')->nullable();                // html_url of the repo
    });
}

/**
 * further migrations can be added below using things like
 * Capsule::schema()->hasColumn
 */

// end of file
