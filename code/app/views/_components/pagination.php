
<div class="row">
    <div class="col-md-9">
        <!-- page button navigation -->
        <a href="/" class="btn btn-sm btn-danger<?=$page == 0 ? ' btn-light' : ''?>">&nbsp;&nbsp;1&nbsp;&nbsp;</a>
        <?php
            if ($page > 4) {
                ?>
                    <button class="btn btn-sm btn-danger" disabled>&nbsp;&nbsp;...&nbsp;&nbsp;</button>
                <?php
            }
            // if there's more than 5 pages
            if ($end > 5) {
                for ($i = $from; $i <= $to; $i++) {
                    if ($from <= 1) {
                        continue;
                    } else {
                        ?>
                            <a href="<?=($i == 1) ? '/' : '/' . $i?>" class="btn btn-sm btn-danger<?=$page == $i ? ' btn-light' : ''?>"><?='&nbsp;&nbsp;' . $i . '&nbsp;&nbsp;'?></a>
                        <?php
                    }
                }

                if ($to != $end) {
                    ?>
                        <button class="btn btn-sm btn-danger" disabled>&nbsp;&nbsp;...&nbsp;&nbsp;</button>
                    <?php
                }
            }


            if ($to != $end) {
                ?>
                    <a href="/<?=$end?>" class="btn btn-sm btn-danger<?=$page == $end ? ' btn-light' : ''?>"><?='&nbsp;&nbsp;' . $end . '&nbsp;&nbsp;'?></a>
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
