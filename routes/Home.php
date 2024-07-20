<?php

/**
 * /login
 *
 * initiates the login with discord process
 *
 * @category GET
 */
$base->get("/login", function () {
    $controller = new spark\Controllers\HomeController;
    return $controller->{'login'}();
});

$base->post("/login", function () {
    $controller = new spark\Controllers\HomeController;
    return $controller->{'login'}();
});

/**
 * /logout
 *
 * logs a user out - destroys sessions, etc.
 *
 * @category GET
 */
$base->get("/logout", function () {
    $controller = new spark\Controllers\HomeController;
    return $controller->{'logout'}();
});

// GET /changelog
$base->get("/changelog", function () {
    $controller = new spark\Controllers\HomeController;
    return $controller->{'changelog'}();
});

// GET /page
$base->get("/:page", function ($page) {
    $controller = new spark\Controllers\HomeController;
    return $controller->{'index'}($page);
});

// GET /
$base->get("/", function () {
    $controller = new spark\Controllers\HomeController;
    return $controller->{'index'}();
});