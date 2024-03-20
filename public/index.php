<?php

require "../helpers.php";
require basePath("Router.php");
require basePath("Database.php");

$config = require basePath("config/db.php");

$db = new Database($config);

$router = new Router();

$routes = require basePath("routes.php");

// inspect($routes);

$inituri = $_SERVER["REQUEST_URI"];
$uri = substr($inituri, 13);
$method = $_SERVER["REQUEST_METHOD"];

$router->route($uri, $method);
