<?php

$router->get("/", "HomeController@index");
$router->get("/listing/{id}", "ListingController@show");
$router->get("/listings", "ListingController@index");
$router->get("/listings/create", "ListingController@create");
