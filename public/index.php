<?php

require "../helpers.php";

$routes = [
    "/" => "controllers/home.php",
    "/listings" => "controllers/listings/index.php",
    "/listings/create" => "controllers/listings/create.php",
    "404" => "controllers/error/404.php"
];

$inituri = $_SERVER["REQUEST_URI"];
$uri = substr($inituri, 13);
$method = $_SERVER["REQUEST_METHOD"];


if (array_key_exists($uri, $routes)) {
    require(basePath($routes[$uri]));
} else {
    require(basePath($routes["404"]));
};
