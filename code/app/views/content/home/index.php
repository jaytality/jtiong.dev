<?php
?>

<br />

<div class="container">
    <!-- commit graph -->
    <div class="row">
        <div class="col-md-12">
            commit graph here
        </div>
    </div>

    <!-- commit messages -->
    <div class="row">
        <div class="col-md-9">
            <!-- page button navigation -->
            <a href="/" class="btn btn-danger<?=$page == 0 ? ' btn-light' : ''?>">&nbsp;&nbsp;1&nbsp;&nbsp;</a>
            <?php
                for ($i = 2; $i <= 5; $i++) {
                    ?>
                        <a href="<?=($i == 1) ? '/' : '/' . $i?>" class="btn btn-danger<?=$page == $i ? ' btn-light' : ''?>"><?='&nbsp;&nbsp;' . $i . '&nbsp;&nbsp;' . $here?></a>
                    <?php
                }

                if ($end > 5) {
                    echo '... ...';
                    ?>
                        <a href="/<?=$end?>" class="btn btn-danger"><?='&nbsp;&nbsp;' . $end . '&nbsp;&nbsp;'?></a>
                    <?php
                }
            ?>
            <br />
            <br />
        </div>
        <div class="col-md-3">
            <!-- prev/next navigation -->
        </div>
    </div>
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
    <div class="row">
        <div class="col-md-12">
            <?php
                for ($i = 1; $i <= $end; $i++) {
                    ?>
                        <a href="<?=($i == 1) ? '/' : '/' . $i?>" class="btn btn-danger<?=$page == $i ? ' btn-light' : ''?>"><?=$i?></a>
                    <?php
                }
            ?>
        </div>
    </div>
</div>
