<?php

/**
 * getRepositories
 *
 * fetch all repositories
 *
 * @author Johnathan Tiong <johnathan.tiong@gmail.com>
 * @copyright 2023 Johnathan Tiong
 *
 */

require_once '../../bootstrap.php';
require_once '../../database.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$accessToken    = getConfig('github.token');
$githubUsername = getConfig('github.username');

// Function to get the list of repositories for a username
function getRepositoriesForUser($accessToken, $githubUsername)
{
    $params = [
        'type'     => 'all',
        'per_page' => 100,
        'page'     => 1,
    ];

    $url = "https://api.github.com/users/{$githubUsername}/repos?" . http_build_query($params);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Bearer {$accessToken}",
        "User-Agent: jtiong.dev" // Replace 'Awesome-App' with your app name or identifier
    ));

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Get the repositories
$repositories = getRepositoriesForUser($accessToken, $githubUsername);

// truncate the table - Github should always be the source of truth
// by doing this - any deleted repos will also no longer be relevant to the frontend
$truncateRepos = Capsule::table('jtdev_repos')->truncate();

// Output the repositories
if (is_array($repositories)) {
    foreach ($repositories as $repo) {
        echo "CHECKING REPO: {$repo['name']}\n";

        $checkRepo = Capsule::table('jtdev_repos')
            ->where('githubid', '=', $repo['id'])
            ->where('name', '=', $repo['name'])
            ->get();

        if ($checkRepo->isEmpty()) {
            echo "ADDING NEW REPOSITORY: {$repo['name']}\n";
            $addRepo = Capsule::table('jtdev_repos')->insert([
                'name'        => $repo['name'],
                'githubid'    => $repo['id'],
                'created'     => date("Y-m-d H:i:s", strtotime($repo['created_at'])),
                'visible'     => true,
                'description' => $repo['description'],
                'url'         => $repo['html_url'],
                'fork'        => $repo['fork'],
            ]);
        } else {
            echo "REPOSITORY EXISTS ({$repo['name']}) \n\n";
        }
    }
} else {
    echo "Error fetching repositories.";
}

 // end of file