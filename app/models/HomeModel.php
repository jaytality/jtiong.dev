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

    /**
     * fetch all the commits in my account
     *
     * @param integer $limit
     * @param integer $offset
     * @param boolean $all
     * @return void
     */
    public function getCommits($limit = 0, $offset = 0, $all = false)
    {
        $commits = Capsule::table('jtdev_commits')
            ->orderBy('date', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        $managedCommits = [];

        foreach ($commits as $commit) {
            if (strtotime($commit->date) >= strtotime(date('Y') . '-01-01')) {
                $managedCommits[] = $commit;
            }
        }
        echo '<pre>' . print_r($managedCommits, true) . '</pre>';
        die();

        return $managedCommits;
    }

    public function getCommitsTimeline()
    {
        return Capsule::table('jtdev_commits')->orderBy('date', 'asc')->get();
    }
}

// end of file
