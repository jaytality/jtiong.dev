<?php

// Controller: Home

namespace spark\Controllers;

use \spark\Core\Controller as Controller;

use \spark\Models\HomeModel;

use \spark\Helpers\Time;

use \R as R;

class HomeController extends Controller
{
    function index($page = 0)
    {
        $this->viewData['page'] = $page;

        if ($page != 0) {
            $page = $page - 1;
        }

        $offset = $page * 50;
        if ($offset == 0) {
            $commits = R::find('commits', ' ORDER BY time DESC LIMIT 50');
        } else {
            $commits = R::find('commits', ' ORDER BY time DESC LIMIT 50 OFFSET ' . $offset );
        }

        $this->viewData['commits'] = $commits;

		$this->viewOpts['page']['layout']  = 'default';
        $this->viewOpts['page']['content'] = 'home/index';
        $this->viewOpts['page']['section'] = 'home';
        $this->viewOpts['page']['title']   = 'Home';

        $this->view->load($this->viewOpts, $this->viewData);
    }
}

