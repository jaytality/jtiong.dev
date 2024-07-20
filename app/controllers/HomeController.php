<?php

// Controller: Home

namespace spark\Controllers;

use \spark\Core\Controller as Controller;
use \spark\Models\HomeModel;
use \spark\Models\UserModel;
use \spark\Helpers\Time;
use Illuminate\Database\Capsule\Manager as Capsule;
use \DateTime as DateTime;

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

        // total number of pages in pagination
        $commitCount = Capsule::table('jtdev_commits')->count();

        //
        // BUILDING COMMIT STATS GRAPH
        //
        $commits = $homeModel->getCommitsTimeline(true);
        $oldest = $homeModel->getOldestCommit();
        $newest = $homeModel->getNewestCommit();

        // build the statistics array
        $commitStart = $month = strtotime($oldest->date);
        $commitEnd   = strtotime($newest->date);

        // build the statistics array
        $statistics = [];
        while($month < $commitEnd) {
            $statistics[date('F Y', $month)] = 0;
            $month = strtotime("+1 month", $month);
        }

        foreach ($commits as $commit) {
            $key = date('F Y', strtotime($commit->date));

            // $monthYear = date('F Y', strtotime($))
            if (!array_key_exists($key, $statistics)) {
                $statistics[$key] = 0;
            }

            $statistics[$key] += 1;
        }

        // get the highestcommits for display calculations
        $highestCommits = 0;
        $highestMonth = "";
        foreach ($statistics as $stat => $count) {
            if ($count > $highestCommits) {
                $highestCommits = $count;
                $highestMonth = $stat;
            }
        }

        $this->viewData['commits']       = $commits;
        $this->viewData['highestCommit'] = $highestCommits;
        $this->viewData['highestMonth']  = $highestMonth;
        $this->viewData['commitCount']   = $commitCount;
        $this->viewData['oldestCommit']  = date('F Y', strtotime($oldest->date));
        $this->viewData['lifespanDays']  = $this->getLifespanDays($oldest->date, $newest->date);
        $this->viewData['statistics']    = $statistics;

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

    /**
     * shows a changelog of development of jtiong.dev
     *
     * @return void
     */
    function changelog()
    {
		$this->viewOpts['page']['layout']  = 'default';
        $this->viewOpts['page']['content'] = 'home/changelog';
        $this->viewOpts['page']['section'] = 'home';
        $this->viewOpts['page']['title']   = 'Home';

        $this->view->load($this->viewOpts, $this->viewData);
    }

    function getLifespanDays($startDate, $endDate)
    {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);

        $interval = $start->diff($end);

        return $interval->days;
    }
}

