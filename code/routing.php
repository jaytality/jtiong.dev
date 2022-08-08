<?php
foreach (glob(ROOT."/routes/*.php") as $filename) {
    include $filename;
}

// ERROR: 404
$base->notFound(function () {
    $view = new \spark\Core\Error;
    $view->display('404');
});
