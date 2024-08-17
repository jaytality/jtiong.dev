<div class="container">
    <!-- commit graph -->
    <div class="row">
        <div class="col-md-12" style="padding: 1.5rem 0 0 1.5rem; ">
            <p>
                <?='<strong>' . $commitCount . '</strong> commits since <strong>' . $oldestCommit . '</strong> - ' . number_format(($commitCount / $lifespanDays), 2) . ' commits per day!'?> <small class="text-muted">out of <?=$lifespanDays?> days</small><br />
                <strong>Most Commits:</strong> <?=$highestCommit . ' in ' . $highestMonth?><br />
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="barchart">
                <?php
                foreach ($statistics as $stat => $count) {
                    echo '<a href="#" title="' . $stat . ' - ' . $count . ' commits">';
                    if ($count > 0) {
                        // calculate a percentage - doesn't need to be super accurate
                        $height = ($count / $highestCommit) * 100;
                        $height = ceil($height);

                        echo '<div style="height: ' . $height . 'px; "></div>';
                    }
                    echo '</a>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- commit messages -->
    <div class="row">
        <div class="col-md-12">
            <hr />
            <h3>Recent Commits <small class="text-muted">last 50</small></h3>
            <table class="table table-hover table-condensed table-borderless">
                <tbody>
                    <?php
                        $limit = 1;
                        foreach ($commits as $commit) {
                            // if ($commit->visible) {
                                if ($limit >= 50) {
                                    break;
                                }

                                $time = new \spark\Helpers\Time;
                                ?>
                                    <tr>
                                        <td class="text-right">
                                            <strong><a href="/project/<?=$commit->repo_name?>" class="text-danger"><?=$commit->repo_name?></a></strong>
                                            <br />
                                            <?php
                                                // not idea doing this in the view, but converting to current timezone
                                                $timestamp = new \DateTime($commit->date);
                                                $timestamp->setTimezone(new DateTimeZone("Australia/Sydney"));
                                                $timestamp->add(new DateInterval('PT10H'));
                                            ?>
                                            <small class="text-muted"><?=$timestamp->format("d M Y (H:i:s)")?></small>
                                        </td>
                                        <td>
                                            <span style="font-size: 1.1rem; ">
                                                <small class="text-warning">#<?=substr($commit->sha, 0, 6)?></small>
                                            </span>
                                            <p style="color: #ccc; "><?=$commit->message?></p>
                                        </td>
                                    </tr>
                                <?php
                                $limit++;
                            // }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
