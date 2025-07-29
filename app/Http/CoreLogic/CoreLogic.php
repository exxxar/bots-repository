<?php

namespace App\Http\CoreLogic;

use App\Http\CoreLogic\Methods\Auth;

class CoreLogic
{

    private $auth;

    public function __construct()
    {
        $this->auth = new Auth();
    }

    public function auth(): Auth
    {
        return $this->auth;
    }
}
