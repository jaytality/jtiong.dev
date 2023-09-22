<?php

// Controller: Home

namespace spark\Controllers;

use \spark\Core\Controller as Controller;
use \spark\Models\HomeModel;
use \spark\Helpers\Time;
use Illuminate\Database\Capsule\Manager as Capsule;

class HomeController extends Controller
{
    /**
     * generates a graph of commits and log of commits that is visible as the main index page of jtiong.dev
     *
     * @param integer $page
     * @return void
     */
    function index($page = 0)
    {
        $homeModel = new HomeModel;

        $limit = 25;    // this is the number of commit messages to show per page
        $from  = 0;     // starting visible pagination number (not including 1 and ...)
        $to    = 0;     // last visible pagination number (not including end and ...)

        // total number of pages in pagination
        $commitCount = Capsule::table('jtdev_commits')->count();
        $totalPages = $commitCount / $limit;
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
        $commits = $homeModel->getCommits($limit, $offset);

        //
        // BUILDING COMMIT STATS GRAPH
        //
        // $fullCommits = R::find('commits', ' ORDER BY time ASC');
        $fullCommits = $homeModel->getCommitsTimeline();
        $oldest = $homeModel->getOldestCommit();
        $newest = $homeModel->getNewestCommit();

        $commitStart = $month = strtotime($oldest->date);
        $commitEnd   = strtotime($newest->date);

        // build the statistics array
        $statistics = [];
        while($month < $commitEnd) {
            $statistics[date('F Y', $month)] = 0;
            $month = strtotime("+1 month", $month);
        }

        // always add current month too
        $statistics[date('F Y')] = 0;

        foreach ($fullCommits as $commit) {
            $statistics[date('F Y', strtotime($commit->date))] += 1;
        }

        // get the highestcommits for display calculations
        $highestCommits = 0;
        foreach ($statistics as $stat => $count) {
            if ($count > $highestCommits) {
                $highestCommits = $count;
            }
        }

        // navigation variables
        $this->viewData['from']       = $from;
        $this->viewData['to']         = $to;
        $this->viewData['end']        = $totalPages - 1;
        $this->viewData['commits']    = $commits;
        $this->viewData['page']       = $page;
        $this->viewData['highest']    = $highestCommits;
        $this->viewData['statistics'] = $statistics;

		$this->viewOpts['page']['layout']  = 'default';
        $this->viewOpts['page']['content'] = 'home/index';
        $this->viewOpts['page']['section'] = 'home';
        $this->viewOpts['page']['title']   = 'Home';

        $this->view->load($this->viewOpts, $this->viewData);
    }

    /**
     * initiate the login with discord process - redirects user to discord verification
     *
     * @return void
     */
    function login()
    {
		$this->viewOpts['page']['layout']  = 'default';
        $this->viewOpts['page']['content'] = 'home/login';
        $this->viewOpts['page']['section'] = 'home';
        $this->viewOpts['page']['title']   = 'Home';

        $this->view->load($this->viewOpts, $this->viewData);
    }

    /**
     * log a user out - destroy all session stuff and redirect to index /
     *
     * @return void
     */
    function logout()
    {
        session_destroy();
        header("Location: " . getConfig('host'));
        exit();
    }
}

