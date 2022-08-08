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
            pagination
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <tbody>
                    <?php
                        foreach ($commits as $commit) {
                            $time = new Time;
                            ?>
                                <tr>
                                    <td><?=$time->niceOutput($commit['time'])?></td>
                                    <td>
                                        <strong><?=$commit['project'] . ' / ' . $commit['branch']?></strong>
                                        <p><?=$commit['title']?></p>
                                        <small class="text-muted"><?=$commit['hash']?></small>
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
            pagination
        </div>
    </div>
</div>
