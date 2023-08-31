<?php

/**
 * getCommits
 *
 * fetch all the relevant commits that i've done
 *
 * @author Johnathan Tiong <johnathan.tiong@gmail.com>
 * @copyright 2022 Johnathan Tiong
 *
 * GITHUB FIELDS:
 *  - node_id
 *  - name
 *  - description
 *  - html_url
 *
 * @version 1.0 (2022)          - curl request to gitlab and dump into local mysql
 * @version 1.1 (2023-08-31)    - updating to use github, planetscale DB
 */

require_once '../bootstrap.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$accessToken = getConfig('github.token');
$githubUsername = getConfig('github.username');

// Function to get the list of repositories for a username
function getRepositoriesForUser($accessToken, $githubUsername)
{
    $url = "https://api.github.com/users/{$githubUsername}/repos";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: token {$accessToken}",
        "User-Agent: Awesome-App" // Replace 'Awesome-App' with your app name or identifier
    ));

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Get the repositories
$repositories = getRepositoriesForUser($accessToken, $githubUsername);

// Output the repositories
if (is_array($repositories)) {
    foreach ($repositories as $repo) {
        $checkRepo = Capsule::table('jtdev_repos')
                        ->where('github', '=', $repo['node_id'])
                        ->where('name', '=', $repo['name'])
                        ->get();

        if ($checkRepo->isEmpty()) {
            $addRepot = Capsule::table('jtdev_repos')->insert([
                'name'        => $repo['name'],
                'github'      => $repo['node_id'],
                'created'     => strtotime($repo['created_at']),
                'visible'     => true,
                'description' => $repo['description'],
                'url'         => $repo['html_url']
            ]);
        }
    }
} else {
    echo "Error fetching repositories.";
}



/*

// debug flag for in-line console stuff
$debug = false;

// @todo I need to implement fetching the GitHub commits as well
echo ($debug) ? "gitlab token: " . getConfig('gitlab.token') . "\n\n" : '';

$projects = get('https://gitlab.jtiong.dev/api/v4/projects?private_token=' . getConfig('gitlab.token'));
$projects = json_decode($projects, true);

$multibranch = [    // certain projects rely on specific branching - for these projects, it is pertinent to count exclusive branches
    'backups',
];

// for each project...
foreach ($projects as $project) {
    $branches = get('https://gitlab.jtiong.dev/api/v4/projects/' . $project['id'] . '/repository/branches?private_token=' . getConfig('gitlab.token'));
    $branches = json_decode($branches, true);

    echo ($debug) ? "PROJECT: {$project['path']}\n" : '';

    foreach ($branches as $branch) {
        $commits = get('https://gitlab.jtiong.dev/api/v4/projects/' . $project['id'] . '/repository/commits?private_token=' . getConfig('gitlab.token'));
        $commits = json_decode($commits, true);

        echo ($debug) ? "\t{$branch['name']}\n" : '';

        foreach ($commits as $commit) {
            echo ($debug) ? "\t\t" . strtotime($commit['created_at']) . " - " . $commit['short_id'] . ": " . $commit['title'] . "\n" : '';

            // check if the commit already exists in the DB
            if (in_array($project['path'], $multibranch)) {
                $existCheck = R::findOne('commits', ' branch = ? AND fullhash = ?', [ $branch['name'], $commit['id'] ]);
            } else {
                $existCheck = R::findOne('commits', ' fullhash = ?', [ $commit['id'] ]);
            }

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
*/

// end of file
