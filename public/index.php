<?php

require __DIR__ . "/../vendor/autoload.php";
require "../helpers.php";
// require basePath("Framework/Router.php");
// require basePath("Framework/Database.php");
use Framework\Database;
use Framework\Router;

$config = require basePath("config/db.php");

$db = new Database($config);

$router = new Router();

$routes = require basePath("routes.php");

// inspect($routes);

$inituri = $_SERVER["REQUEST_URI"];
$uri = parse_url($inituri, PHP_URL_PATH);

$router->route($uri);
