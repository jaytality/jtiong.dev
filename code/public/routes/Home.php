<?php

// GET /
$base->get("/", function () {
    $controller = new spark\Controllers\HomeController;
    return $controller->{'index'}();
});

$base->get("/:offset", function () {
    $controller = new spark\Controllers\HomeController;
    return $controller->{'index'}($offset);
});
