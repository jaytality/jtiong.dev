<?php

// GET /
$base->get("/", function () {
    $controller = new spark\Controllers\HomeController;
    return $controller->{'index'}();
});
