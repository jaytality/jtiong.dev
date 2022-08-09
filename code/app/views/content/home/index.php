<?php
?>

<br />

<div class="container">
    <!-- commit graph -->
    <div class="row">
        <div class="col-md-12">
            commit graph
            <br />
            Page: <?=$page?><br />
            From: <?=$from?><br />
            To: <?=$to?><br />
            End: <?=$end?><br />
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
                            $time = new \spark\Helpers\Time;
                            ?>
                                <tr>
                                    <td>
                                        <strong class="text-warning">Johnathan</strong>
                                        <br />
                                        <small class="text-muted"><?=$time->niceOutput($commit['time'])?></small>
                                    </td>
                                    <td>
                                        <span style="font-size: 1.1rem; ">
                                            <strong class="text-success"><?=$commit['project']?></strong> / <strong class="text-info"><?=$commit['branch']?></strong>
                                            <strong class="text-muted">#<?=$commit['hash']?></strong>
                                        </span>
                                        <p style="color: #ccc; "><?=$commit['title']?></p>
                                    </td>
                                </tr>
                            <?php
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
