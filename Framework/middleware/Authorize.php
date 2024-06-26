<?php

namespace Framework\Middleware;

use Framework\Session;

class Authorize
{
    public function isAuthenticated()
    {
        return Session::has('user');
    }

    public function handle($role)
    {
        if ($role === "auth" && !$this->isAuthenticated()) {
            redirect("/auth/login");
        } elseif ($role === "guest" && $this->isAuthenticated()) {
            redirect("/");
        }
    }
}
