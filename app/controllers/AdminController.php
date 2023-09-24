<?php

// Controller: Admin

namespace spark\Controllers;

use \spark\Core\Controller as Controller;
use \spark\Models\HomeModel;
use \spark\Models\UserModel;
use \spark\Helpers\Time;
use Illuminate\Database\Capsule\Manager as Capsule;

class AdminController extends Controller
{
    function index()
    {
		$this->viewOpts['page']['layout']  = 'default';
        $this->viewOpts['page']['content'] = 'admin/index';
        $this->viewOpts['page']['section'] = 'home';
        $this->viewOpts['page']['title']   = 'Home';

        if (
            empty($_SESSION['authenticated']) ||
            $_SESSION['authenticated'] != true
        ) {
            $this->viewData['error'] = "Invalid Authentication";

            $this->viewOpts['page']['content'] = 'home/index';
        }

        $this->view->load($this->viewOpts, $this->viewData);
    }
}

