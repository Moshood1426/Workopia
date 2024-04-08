<?php

require "../helpers.php";
require basePath("Framework/Router.php");
require basePath("Framework/Database.php");

$config = require basePath("config/db.php");

$db = new Database($config);

$router = new Router();

$routes = require basePath("routes.php");

// inspect($routes);

$inituri = substr($_SERVER["REQUEST_URI"], 13);
$uri = parse_url($inituri, PHP_URL_PATH);
$method = $_SERVER["REQUEST_METHOD"];

$router->route($uri, $method);
