<div class="container">
    <!-- commit graph -->
    <div class="row">
        <div class="col-md-4">
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
        <div class="col-md-8">
            <h3>Overview...</h3>
            <p>
                <strong>Lifetime Commits:</strong> <?=$commitCount . ' since ' . $oldestCommit?><br />
                <strong>Most Commits:</strong> <?=$highestCommit . ' in ' . $highestMonth?><br />
            </p>
        </div>
    </div>

    <!-- commit messages -->
    <div class="row">
        <div class="col-md-12">
            <hr />
            <table class="table table-hover table-borderless">
                <tbody>
                    <?php
                        foreach ($commits as $commit) {
                            if ($commit->visible) {
                                $time = new \spark\Helpers\Time;
                                ?>
                                    <tr>
                                        <td>
                                            <span style="font-size: 1.1rem; ">
                                                <strong><a href="/project/<?=$commit->repo_name?>" class="text-author"><?=$commit->repo_name?></a></strong><span class="text-project">#<?=substr($commit->sha, 0, 6)?></span> &bull;
                                                <small class="text-muted"><?=date("d M Y (H:i:s)", strtotime($commit->date))?></small>
                                            </span>
                                            <p style="color: #ccc; "><?=$commit->message?></p>
                                        </td>
                                    </tr>
                                <?php
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
        // include(views . '/_components/pagination.php');
    ?>
</div>
