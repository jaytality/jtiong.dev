<?php

// GET /
$base->get("/", function () {
    $controller = new spark\Controllers\HomeController;
    return $controller->{'index'}();
});

$base->get("/:page", function ($page) {
    $controller = new spark\Controllers\HomeController;
    return $controller->{'index'}($page);
});
