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
        $limit = 25;    // this is the number of commit messages to show per page
        $from  = 0;     // starting visible pagination number (not including 1 and ...)
        $to    = 0;     // last visible pagination number (not including end and ...)

        // total number of pages in pagination
        $totalPages = R::count('commits') / $limit;
        $totalPages = ceil($totalPages);

        if (
            $page >= 0 ||
            $page <= 4
        ) {
            $from = 2;
            $to = 5;
        } else if (
            $page >= $totalPages
        )

        // if we're not on the first page
        /*
        if ($page == 1) {
            $from = $page - 2;
            $to = $page + 2;
            $page = $page - 1;
        }

        if ($page == 2) {
            $from = $page - 2;
            $to = $page + 2;
            $page = $page - 1;
        }
        */

        $offset = $page * $limit;

        if ($offset == 0) {
            $commits = R::find('commits', ' ORDER BY time DESC LIMIT '. $limit);
        } else {
            $commits = R::find('commits', ' ORDER BY time DESC LIMIT ' . $limit . ' OFFSET ' . $offset );
        }
        // calculate the display - we always show the FIRST and LAST pages
        // followed by a "..." "page-1" "page" "page+1" "..."
        /**
         * pagination
         * logically it can look like
         *
         * 1 2 3 4 5 ... 9
         * 1 [2] 3 4 5
         * 1 2 [3] 4 5
         *
         * after page 3:
         * 1 ... 3 [4] 5 ... 7
         *
         * so if there's more than 6 pages
         */

        // navigation variables
        $this->viewData['from']    = $from;
        $this->viewData['to']      = $to;
        $this->viewData['end']     = $totalPages;
        $this->viewData['commits'] = $commits;
        $this->viewData['page']    = $page;

		$this->viewOpts['page']['layout']  = 'default';
        $this->viewOpts['page']['content'] = 'home/index';
        $this->viewOpts['page']['section'] = 'home';
        $this->viewOpts['page']['title']   = 'Home';

        $this->view->load($this->viewOpts, $this->viewData);
    }
}

