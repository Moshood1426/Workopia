<?php

namespace Framework;

class Session
{
    //Starter Session
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    //Set a session
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    //Get session by key
    public static function get($key, $default = null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    //Check if session exists
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    //Clear session by key
    public static function clear($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    //clear all session data
    public static function clearAll()
    {
        session_unset();
        session_destroy();
    }
}
