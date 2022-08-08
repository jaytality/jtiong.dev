<?php
    $totalPages = R::count('commits') / 50;
    $totalPages = (int)$totalPages;
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
        <div class="col-md-12">
            <?php
                for ($i = 0; $i < $totalPages; $i++) {
                    ?>
                        <a href="<?=$i == 0 ? '/' : '/' . $i + 1?>" class="btn btn-primary"><?=$i + 1?></a>
                    <?php
                }
            ?>
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
                for ($i = 1; $i <= $totalPages; $i++) {
                    ?>
                        <a href="<?=$page == 0 ? '/' : '/' . $page?>" class="btn btn-primary"><?=$page + 1?></a>
                    <?php
                }
            ?>
        </div>
    </div>
</div>
