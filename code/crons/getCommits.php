<?php

/**
 * getCommits
 *
 * fetch all the relevant commits that i've done
 *
 * @author Johnathan Tiong <johnathan.tiong@gmail.com>
 * @copyright 2022 Johnathan Tiong
 */
include "../config.php";

function get($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}

// @todo I need to implement fetching the GitHub commits as well

die("token: " . getConfig('gitlab.token'));

$projects = get('https://gitlab.jtiong.dev/api/v4/projects?private_token=' . getConfig('gitlab.token'));
$projects = json_decode($projects, true);

// for each project...
foreach ($projects as $project) {
    $branches = get('https://gitlab.jtiong.dev/api/v4/projects/' . $project['id'] . '/repository/branches?private_token=' . getConfig('gitlab.token'));
    $branches = json_decode($branches, true);

    foreach ($branches as $branch) {
        $commits = get('https://gitlab.jtiong.dev/api/v4/projects/' . $project['id'] . '/repository/commits?private_token=' . getConfig('gitlab.token'));
        $commits = json_decode($commits, true);

        foreach ($commits as $commit) {
            $entryCheck = R::findOne('commits', ' fullhash = ?', [ $commit['id'] ]);
            if ($entryCheck == null) {
                $entry = R::xdispense('commits');
                $entry['time']     = strtotime($commit['created_at']);
                $entry['project']  = $project['path'];
                $entry['branch']   = $branch['name'];
                $entry['hash']     = $commit['short_id'];
                $entry['fullhash'] = $commit['id'];
                $entry['title']    = $commit['title'];
                R::store($entry);
            }
        }
    }
}
