<?php

namespace spark\Models;

use \spark\Core\Model as Model;
use Illuminate\Database\Capsule\Manager as Capsule;

class HomeModel extends Model
{
    public function getOldestCommit()
    {
        $oldestCommit = Capsule::table('jtdev_commits')
            ->orderBy('date', 'asc')
            ->first();

        return $oldestCommit;
    }

    public function getNewestCommit()
    {
        $newestCommit = Capsule::table('jtdev_commits')
            ->orderBy('date', 'desc')
            ->first();

        return $newestCommit;
    }

    public function getCommits($limit = 0, $offset = 0)
    {
        $commits = Capsule::table('jtdev_commits')
            ->orderBy('date', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $commits;
    }

    public function getCommitsTimeline()
    {
        return Capsule::table('jtdev_commits')->orderBy('date', 'asc')->get();
    }
}

// end of file
