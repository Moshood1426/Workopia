<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class ListingController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath("config/db.php");
        $this->db = new Database($config);
    }

    public function index()
    {
        $stmt = "SELECT * FROM listings";
        $listings = $this->db->query($stmt)->fetchAll();

        loadView("listings/index", ["listings" => $listings]);
    }

    public function create()
    {
        loadView("listings/create");
    }

    public function store()
    {
        $allowedFields = [
            'title', 'description', 'salary', 'tags', 'company', 'address',
            'city', 'state', 'phone', 'email', 'requirements', 'benefits'
        ];

        //keep what is in the allowedFields from the request body
        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));
        $newListingData["user_id"] = 1;

        //sanitize the inputs
        $newListingData = array_map('sanitize', $newListingData);

        $importantData = ['title', 'description', 'city', 'state', 'email'];
        $errors = [];

        foreach ($importantData as $data) {
            if (
                empty($newListingData[$data]) ||
                !Validation::string($newListingData[$data])
            ) {
                $errors[$data] = ucfirst($data) . ' cannot be empty';
            }
        }

        if (!empty($errors)) {
            // Reload view with errors
            loadView('listings/create', [
                'errors' => $errors,
                'listing' => $newListingData
            ]);
        } else {
            //create the field for database query
            $fields = [];
            foreach ($newListingData as $field => $value) {
                $fields[] = $field;
            }
            $fields = implode(", ", $fields);

            //create values for database query
            $values = [];
            foreach ($newListingData as $field => $value) {
                if ($value === "") {
                    $newListingData[$field] = null;
                }

                $values[] = ":" . $field;
            }

            $values = implode(", ", $values);

            //run the query
            $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";

            inspectAndDie($query);
            $this->db->query($query, $newListingData);

            redirect("/listings");
        }
    }

    public function show($params)
    {
        $id = $params['id'] ?? "";

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

        if (!$listing) {
            ErrorController::notFound("Listing not found");
            return;
        }
        loadView("listings/show", ["listing" => $listing]);
    }

    public function destroy($params)
    {
        $id = $params['id'] ?? "";

        $param = [
            'id' => $id
        ];

        $listing = $this->db->query(
            'SELECT * FROM listings WHERE id = :id',
            $params
        )->fetch();

        if (!$listing) {
            ErrorController::notFound("Listing not found");
            return;
        }

        $this->db->query(
            'DELETE FROM listings WHERE id = :id',
            $params
        );

        $_SESSION['success_message'] = "Listing deleted successfully";

        redirect("/listings");
    }

    public function edit($params)
    {
        $id = $params['id'] ?? "";

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

        if (!$listing) {
            ErrorController::notFound("Listing not found");
            return;
        }

        loadView("listings/edit", ["listing" => $listing]);
    }

    public function update($params)
    {
        //set up params for DB query
        $id = $params['id'] ?? "";
        $params = [
            'id' => $id
        ];

        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

        if (!$listing) {
            ErrorController::notFound("Listing not found");
            return;
        }

        $allowedFields = [
            'title', 'description', 'salary', 'tags', 'company', 'address',
            'city', 'state', 'phone', 'email', 'requirements', 'benefits'
        ];

        //keep what is in the allowedFields from the request body
        $updatedValues = array_intersect_key($_POST, array_flip($allowedFields));

        $updatedValues = array_map('sanitize', $updatedValues);

        $importantData = ['title', 'description', 'city', 'state', 'email'];
        $errors = [];

        foreach ($importantData as $data) {
            if (
                empty($updatedValues[$data]) ||
                !Validation::string($updatedValues[$data])
            ) {
                $errors[$data] = ucfirst($data) . ' cannot be empty';
            }
        }

        if (!empty($errors)) {
            // Reload view with errors
            loadView("listings/edit", [
                'errors' => $errors,
                'listing' => $listing
            ]);
        } else {
            $updateFields = [];

            foreach (array_keys($updatedValues) as $field) {
                $updateFields[] = "{$field} = :{$field}";
            }

            $updateFields = implode(', ', $updateFields);

            $updateQuery = "UPDATE listings SET $updateFields WHERE id = :id";

            $updatedValues['id'] = $id;
            $this->db->query($updateQuery, $updatedValues);

            // Set flash message
            $_SESSION['success_message'] = "Listing updated";

            redirect('/listings/' . $id);
        }
    }
}
