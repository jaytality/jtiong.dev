<?php

$curl = new \spark\Helpers\Curl();

$projects = $curl->get('https://gitlab.jtiong.dev/api/v4/projects?private_token=' . getConfig('gitlab.token'));
$projects = json_decode($projects, true);

// for each project...
foreach ($projects as $project) {
    $branches = $curl->get('https://gitlab.jtiong.dev/api/v4/projects/' . $project['id'] . '/repository/branches?private_token=' . getConfig('gitlab.token'));
    $branches = json_decode($branches, true);

    foreach ($branches as $branch) {
        $commits = $curl->get('https://gitlab.jtiong.dev/api/v4/projects/' . $project['id'] . '/repository/commits?private_token=' . getConfig('gitlab.token'));
        $commits = json_decode($commits, true);

        foreach ($commits as $commit) {
            $entryCheck = R::findOne('commits', ' hash = ?', [ $commit['id'] ]);
            if ($entryCheck == null) {
                $entry = R::xdispense('commits');
                $entry['project'] = $project['path'];
                $entry['branch']  = $branch['name'];
                $entry['hash']    = $commit['id'];
                $entry['title']   = $commit['title'];
                $entry['created'] = strtotime($commit['created_at']);
                R::store($entry);
            }
        }
    }
}
