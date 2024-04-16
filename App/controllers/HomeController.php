<?php

namespace App\Controllers;

use Framework\Database;

class HomeController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath("config/db.php");
        $this->db = new Database($config);
    }

    public function index()
    {
        $stmt = "SELECT * FROM listings LIMIT 6";
        $listings = $this->db->query($stmt)->fetchAll();

        loadView("home", ["listings" => $listings]);
    }
}
