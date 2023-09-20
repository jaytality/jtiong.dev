<?php

/**
 * runs through the commits stored - and if the repo they belong to has been deleted, set them to "invisible"
 *
 * @author Johnathan Tiong <johnathan.tiong@gmail.com>
 * @copyright 2023 Johnathan Tiong
 *
 */

require_once '../../bootstrap.php';
require_once '../../database.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$commits = Capsule::table('jtdev_commits')->get();

foreach ($commits as $commit) {
    // check for the repo it belongs to
    $repoCheck = Capsule::table('jtdev_repos')
        ->where('name', '=', $commit->repo_name)
        ->get();

    // if the repo does NOT exist
    if ($repoCheck->isEmpty()) {
        // update to invisible
        $updateCommit = Capsule::table('jtdev_commits')
            ->where('id', $commit->id)
            ->update([
                'visible' => false,
            ]);
    }
}

// end of file
