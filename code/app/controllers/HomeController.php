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
        $homeModel = new HomeModel;

        $limit = 25;    // this is the number of commit messages to show per page
        $from  = 0;     // starting visible pagination number (not including 1 and ...)
        $to    = 0;     // last visible pagination number (not including end and ...)

        // total number of pages in pagination
        $totalPages = R::count('commits') / $limit;
        $totalPages = ceil($totalPages);

        if ( // if we're within the first 5 pages of the list...
            $page >= 0 &&
            $page <= 4
        ) {
            // hard code the pages to be from 2 to 5
            $from = 2;
            $to = 5;
        } else if (
            // current page is within the range of the last page
            $page >= $totalPages - 2
        ) {
            $from = $totalPages - 5;
            $to = $totalPages - 1;
        } else {
            $from = $page - 2;
            $to = $page + 2;
        }

        $offset = $page * $limit;

        if ($offset == 0) {
            $commits = R::find('commits', ' ORDER BY time DESC LIMIT '. $limit);
        } else {
            $commits = R::find('commits', ' ORDER BY time DESC LIMIT ' . $limit . ' OFFSET ' . $offset );
        }

        $oldest = $homeModel->getOldestCommit();
        $newest = $homeModel->getNewestCommit();

        echo $oldest['time'] . '<br />';
        echo $newest['time'] . '<br />';

        // get oldest commit's month and year
        $commitStart = $month = date('Y-m-d', $oldest['time']);
        $commitEnd   = date('Y-m-d', $newest['time']);

        while($month < $commitEnd) {
            echo date('Y-m', $month) . '<br />';
            $month = strtotime("+1 month", $month);
        }

        // navigation variables
        $this->viewData['from']    = $from;
        $this->viewData['to']      = $to;
        $this->viewData['end']     = $totalPages - 1;
        $this->viewData['commits'] = $commits;
        $this->viewData['page']    = $page;
        $this->viewData['oldest']  = $oldest;
        $this->viewData['newest']  = $newest;

		$this->viewOpts['page']['layout']  = 'default';
        $this->viewOpts['page']['content'] = 'home/index';
        $this->viewOpts['page']['section'] = 'home';
        $this->viewOpts['page']['title']   = 'Home';

        $this->view->load($this->viewOpts, $this->viewData);
    }
}

