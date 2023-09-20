<div class="container">
    <!-- commit graph -->
    <div class="row">
        <div class="col-md-12">
            <div class="barchart">
                <?php
                foreach ($statistics as $stat => $count) {
                    echo '<a href="#" title="' . $stat . ' - ' . $count . ' commits">';
                    if ($count > 0) {
                        // calculate a percentage - doesn't need to be super accurate
                        $height = ($count / $highest) * 100;
                        $height = ceil($height);

                        echo '<div style="height: ' . $height . '%"></div>';
                    }
                    echo '</a>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- commit messages -->
    <?php
        include(views . '/_components/pagination.php');
    ?>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <tbody>
                    <?php
                        foreach ($commits as $commit) {
                            if ($commit->visible) {
                                $time = new \spark\Helpers\Time;
                                ?>
                                    <tr>
                                        <td>
                                            <strong class="text-author">Johnathan</strong>
                                            <br />
                                            <small class="text-muted"><?=$time->niceOutput(strtotime($commit->date))?></small>
                                        </td>
                                        <td>
                                            <span style="font-size: 1.1rem; ">
                                                <strong class="text-project"><?=$commit->repo_name?></strong>
                                                <span class="text-muted">#<?=substr($commit->sha, 0, 6)?></span>
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
        include(views . '/_components/pagination.php');
    ?>
</div>
