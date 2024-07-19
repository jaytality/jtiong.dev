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
     * fetch all the commits in my account based off a page offset & limit
     *
     * @param integer $limit
     * @param integer $offset
     * @return void
     */
    public function getCommits($limit = 0, $offset = 0)
    {
        $commits = Capsule::table('jtdev_commits')
            ->orderBy('date', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $commits;
    }

    public function getCommitsTimeline($all = false)
    {
        $commits = Capsule::table('jtdev_commits')->orderBy('date', 'asc')->get();

        $managedCommits = [];

        foreach ($commits as $commit) {
            // we only want commits within the last 12 months of the current day to be retrieved
            if (strtotime($commit->date) >= strtotime("-12 months", date('Y-m-d'))) {
                $managedCommits[] = $commit;
            }
        }

        if ($all) {
            return $commits;
        }

        return $managedCommits;
    }
}

// end of file
