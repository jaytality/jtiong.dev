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
    $url = "https://api.github.com/repos/{$githubUsername}/{$repository}/commits";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: token {$accessToken}",
        "User-Agent: jtiong.dev" // Replace 'Awesome-App' with your app name or identifier
    ));

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Now, for each repository listed, fetch the commits for each repo
// to save on calls - we only work with visible repos, and NON FORKS
$repos = Capsule::table('jtdev_repos')
            ->where('visible', '=', true)
            ->where('fork', '=', false)
            ->get();

foreach ($repos as $repo) {
    $repository = $repo->name;

    // Get the commits
    $commits = getCommitsByUser($accessToken, $githubUsername, $repository);

    // Output the commits
    if (is_array($commits)) {
        foreach ($commits as $commit) {
            $checkCommit = Capsule::table('jtdev_commits')
                ->where('sha', '=', $commit['sha'])
                ->get();

            if ($checkCommit->isEmpty()) {
                $addCommit = Capsule::table('jtdev_commits')->insert([
                    'sha'       => $commit['sha'],
                    'date'      => $commit['commit']['author']['date'],
                    'repo_id'   => $repo->githubid,
                    'repo_name' => $repo->name,
                    'message'   => $commit['commit']['message'],
                    'author'    => $commit['commit']['author']['name'],
                    'email'     => $commit['commit']['author']['email'],
                    'url'       => $commit['html_url'],
                ]);
            }
        }
    } else {
        echo "Error fetching commits.";
    }
}

// end of file
