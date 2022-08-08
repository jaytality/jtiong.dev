<?php

// Controller: Home

namespace spark\Controllers;

use \spark\Core\Controller as Controller;

use \spark\Models\HomeModel;

use \spark\Helpers\Curl;

use \R as R;

class HomeController extends Controller
{
    function index()
    {
        $this->viewData['commits'] = [];

        $curl = new Curl;
        $projects = $curl->get('https://gitlab.jtiong.dev/api/v4/projects?private_token=' . getConfig('gitlab.token'));
        $projects = json_decode($projects, true);

        // for each project...
        foreach ($projects as $project) {
            $branches = $curl->get('https://gitlab.jtiong.dev/api/v4/projects/' . $project['id'] . '/repository/branches?private_token=' . getConfig('gitlab.token'));

            foreach ($branches as $branch) {
                $commits = $curl->get('https://gitlab.jtiong.dev/api/v4/projects/' . $project['id'] . '/repository/commits?private_token=' . getConfig('gitlab.token')) . '&ref_name=' . $branch['name'];

                foreach ($commits as $commit) {
                    array_push($this->viewData['commits'], [
                        'project' => $project['path'],
                        'branch' => $branch['name'],
                        'commit_id' => $commit['id'],
                        'commit_title' => $commit['title'],
                        'commit_created' => $commit['created_at']
                    ]);
                }
            }
        }


		$this->viewOpts['page']['layout']  = 'default';
        $this->viewOpts['page']['content'] = 'home/index';
        $this->viewOpts['page']['section'] = 'home';
        $this->viewOpts['page']['title']   = 'Home';

        $this->view->load($this->viewOpts, $this->viewData);
    }
}

