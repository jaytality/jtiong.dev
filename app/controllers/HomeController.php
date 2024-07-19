<?php

// Controller: Home

namespace spark\Controllers;

use \spark\Core\Controller as Controller;
use \spark\Models\HomeModel;
use \spark\Models\UserModel;
use \spark\Helpers\Time;
use Illuminate\Database\Capsule\Manager as Capsule;

class HomeController extends Controller
{
    /**
     * generates a graph of commits and log of commits that is visible as the main index page of jtiong.dev
     * this will ONLY show commits from the current calendar year
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

        // fetch all commits
        // $commits = $homeModel->getCommits($limit, $offset);

        //
        // BUILDING COMMIT STATS GRAPH
        //
        $commits = $homeModel->getCommitsTimeline();
        $oldest = $homeModel->getOldestCommit();
        $newest = $homeModel->getNewestCommit();

        $commitStart = $month = strtotime($oldest->date);
        $commitEnd   = strtotime($newest->date);

        // build the statistics array
        $statistics = [];

        // build stats of each Month Year (e.g. August 2024) in $statistics
        for ($i = 1; $i <= 12; $i++) {
            $statistics[date("F Y", mktime(0, 0, 0, $i, 0, date('Y', strtotime(time()))))] = 0;
        }

        echo '<pre>';
        print_r($statistics, true);
        echo '<br>';

        foreach ($commits as $commit) {
            echo $commit->date . '<br>';
            // $statistics[date('F Y', strtotime($commit->date))] += 1;
        }

        die();

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
        $user = new UserModel;

        if (!empty($_POST['email'])) {
            $authenticated = $user->authenticate($_POST['email'], $_POST['password']);
            if ($authenticated == false) {
                $this->viewData['error'] = "Invalid Authentication";
            } else {
                header("Location: https://" . getConfig('host'));
                exit();
            }
        }

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
        header("Location: https://" . getConfig('host'));
        exit();
    }
}

