<?php

// GET /
$base->get("/admin", function () {
    $controller = new spark\Controllers\AdminController;
    return $controller->{'index'}();
});
