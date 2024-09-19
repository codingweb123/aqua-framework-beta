<?php
/*
 * AQUA ROUTES
 */

use MainController\MainController;
use Router\Router;
use Templater\Templater;

/*
 * It is recommended to compile views if you do not wish to compile it on every visit to your website.
 * Remove line above
 */

Templater::compile();

Router::get("/", [MainController::class, "index"])->name("home");

Router::run();