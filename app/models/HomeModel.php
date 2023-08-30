<?php

namespace spark\Models;

use \spark\Core\Model as Model;

use \R as R;

class HomeModel extends Model
{
    public function getOldestCommit()
    {
        return R::findOne('commits', ' ORDER BY time ASC LIMIT 1');
    }

    public function getNewestCommit()
    {
        return R::findOne('commits', ' ORDER BY time DESC LIMIT 1');
    }
}

// end of file
