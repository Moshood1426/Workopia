<?php

use Framework\Database;

$config = require basePath("config/db.php");
$db = new Database($config);

$stmt = "SELECT * FROM listings LIMIT 6";
$listings = $db->query($stmt)->fetchAll();

loadView("home", ["listings" => $listings]);
