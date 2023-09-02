<?php
/**
 * Cron
 *
 * This is a class that helps run Crons:
 *  - keeping them running in a time detected for execution
 *  - preventing them overlapping on multiple runs if need be
 *
 * @author Johnathan Tiong <johnathan.tiong@gmail.com>
 * @copyright 2023-09-01 Johnathan Tiong
 */

namespace spark\Helpers;

use Illuminate\Database\Capsule\Manager as Capsule;

class Cron
{
    // function to check if crons/jobs/file.php should execute

    // function to check if there's a no overlap rule in place
        // if no overlap rule is in place, just execute
        // if overlap rule exists, insert execution row in crons table
        // it is up to end user to make sure execution row has a completion timestamp and flag written
}

// end of file
