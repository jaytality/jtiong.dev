<?php

/**
 * getCommits
 *
 * fetch all the repositories & relevant commits that i've done
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

function getCommitsByUser($accessToken, $githubUsername, $repository)
{
    $url = "https://api.github.com/repos/{$githubUsername}/{$repository}/commits";

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
            $addRepo = Capsule::table('jtdev_repos')->insert([
                'name'        => $repo['name'],
                'githubid'    => $repo['id'],
                'created'     => strtotime($repo['created_at']),
                'visible'     => true,
                'description' => $repo['description'],
                'url'         => $repo['html_url'],
                'fork'        => $repo['fork'],
            ]);
        }
    }
} else {
    echo "Error fetching repositories.";
}

// Now, for each repository listed, fetch the commits for each repo
// to save on calls - we only work with visible repos
$repos = Capsule::table('jtdev_repos')
            ->where('visible', '=', true)
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
