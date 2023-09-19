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

require_once '../bootstrap.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$accessToken    = getConfig('github.token');
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
        "User-Agent: jtiong.dev" // Replace 'Awesome-App' with your app name or identifier
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

 // end of file