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
        $curl = new Curl;
        $projects = $curl->get('https://gitlab.jtiong.dev/api/v4/projects?private_token=' . getConfig('gitlab.token'));
        $projects = json_decode($projects, true);

        foreach ($projects as $project) {
            echo $project['path'] . '<br />';
        }

        die();

        // $this->viewData['projects'] =

		$this->viewOpts['page']['layout']  = 'default';
        $this->viewOpts['page']['content'] = 'home/index';
        $this->viewOpts['page']['section'] = 'home';
        $this->viewOpts['page']['title']   = 'Home';

        $this->view->load($this->viewOpts, $this->viewData);
    }
}

