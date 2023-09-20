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

// Function to get the list of repositories for a username
function getRepositoriesForUser($accessToken)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.github.com/user/repos?visibility=all&affiliation=owner&per_page=100&page=1',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $accessToken,
            'X-GitHub-Api-Version: 2022-11-28'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return json_decode($response, true);
}

// Get the repositories
$repositories = getRepositoriesForUser(getConfig('github.token'));

// truncate the table - Github should always be the source of truth
// by doing this - any deleted repos will also no longer be relevant to the frontend
$truncateRepos = Capsule::table('jtdev_repos')->truncate();
$count = 1;

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
            echo "COUNT: $count\n\n";
            $count++;
        } else {
            echo "REPOSITORY EXISTS ({$repo['name']}) \n\n";
        }
    }
} else {
    echo "Error fetching repositories.\n\n";
}

 // end of file