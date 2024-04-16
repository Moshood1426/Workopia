<?php

namespace App\Controllers;

class ErrorController
{
    public static function notFound($msg = 'Resource not found')
    {
        http_response_code(404);
        loadView("error", ['status' => '404', 'message' => $msg]);
    }

    public static function unauthorized($msg = 'You are not authorized to view this message')
    {
        http_response_code(403);
        loadView("error", ['status' => '403', 'message' => $msg]);
    }
}
