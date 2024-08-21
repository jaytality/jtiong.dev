<?php

/**
 * getCommits
 *
 * fetch all the repositories & relevant commits that i've done
 *
 * @author Johnathan Tiong <johnathan.tiong@gmail.com>
 * @copyright 2022 Johnathan Tiong
 *
 * @version 1.0 (2022)          - curl request to gitlab and dump into local mysql
 * @version 1.1 (2023-08-31)    - updating to use github, planetscale DB
 */

require_once '../../bootstrap.php';
require_once '../../database.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$accessToken = getConfig('github.token');
$githubUsername = getConfig('github.username');

function getCommitsByUser($accessToken, $githubUsername, $repository)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.github.com/repos/{$githubUsername}/{$repository}/commits",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $accessToken,
            'X-GitHub-Api-Version: 2022-11-28',
            'User-Agent: jtiong.dev',
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return json_decode($response, true);
}

// Now, for each repository listed, fetch the commits for each repo
// to save on calls - we only work with NON FORKS
$repos = Capsule::table('jtdev_repos')
            ->where('fork', '=', false)
            ->get();

foreach ($repos as $repo) {
    echo "Fetching Commits for... {$repo->name}\n";
    $repository = $repo->name;

    // Get the commits
    $commits = getCommitsByUser($accessToken, $githubUsername, $repository);

    // Output the commits
    if (is_array($commits)) {
        foreach ($commits as $commit) {
            if (!is_array($commit)) continue;

            echo "\tChecking commit [{$commit['sha']}]...\n";

            $checkCommit = Capsule::table('jtdev_commits')
                ->where('sha', '=', $commit['sha'])
                ->get();

            if ($checkCommit->isEmpty()) {
                echo "\tCommit not found, recording new commit!\n\n";
                $addCommit = Capsule::table('jtdev_commits')->insert([
                    'sha'       => $commit['sha'],
                    'date'      => date("Y-m-d H:i:s", strtotime($commit['commit']['author']['date'])),
                    'repo_id'   => $repo->githubid,
                    'repo_name' => $repo->name,
                    'message'   => $commit['commit']['message'],
                    'author'    => $commit['commit']['author']['name'],
                    'email'     => $commit['commit']['author']['email'],
                    'url'       => $commit['html_url'],
                    'visible'   => true,
                ]);
            } else {
                echo "\tCommit exists... skipping!\n\n";
            }
        }
    } else {
        echo "Error fetching commits.";
    }
}

// end of file
