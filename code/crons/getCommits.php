<?php

/**
 * getCommits
 *
 * fetch all the relevant commits that i've done
 *
 * @author Johnathan Tiong <johnathan.tiong@gmail.com>
 * @copyright 2022 Johnathan Tiong
 */
function get($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}

// debug flag for in-line console stuff
$debug = false;

// @todo I need to implement fetching the GitHub commits as well
echo ($debug) ? "gitlab token: " . getConfig('gitlab.token') . "\n\n" : '';

$projects = get('https://gitlab.jtiong.dev/api/v4/projects?private_token=' . getConfig('gitlab.token'));
$projects = json_decode($projects, true);

// for each project...
foreach ($projects as $project) {
    $branches = get('https://gitlab.jtiong.dev/api/v4/projects/' . $project['id'] . '/repository/branches?private_token=' . getConfig('gitlab.token'));
    $branches = json_decode($branches, true);
    echo ($debug) ? "PROJECT: {$project['name']}\n" : '';

    foreach ($branches as $branch) {
        $commits = get('https://gitlab.jtiong.dev/api/v4/projects/' . $project['id'] . '/repository/commits?private_token=' . getConfig('gitlab.token'));
        $commits = json_decode($commits, true);
        echo ($debug) ? "\t{$branch['name']}\n" : '';

        foreach ($commits as $commit) {
            echo ($debug) ? "\t\t" . strtotime($commit['created_at']) . " - " . $commit['short_id'] . ": " . $commit['title'] . "\n" : '';
            $existCheck = R::findOne('commits', ' branch = ? AND fullhash = ?', [ $branch['name'], $commit['id'] ]);
            if ($existCheck == null) {
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
