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
        $this->viewData['commits'] = R::find('commits', ' ORDER BY time DESC');

		$this->viewOpts['page']['layout']  = 'default';
        $this->viewOpts['page']['content'] = 'home/index';
        $this->viewOpts['page']['section'] = 'home';
        $this->viewOpts['page']['title']   = 'Home';

        $this->view->load($this->viewOpts, $this->viewData);
    }
}

