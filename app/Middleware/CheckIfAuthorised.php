<?php

namespace App\Middleware;

use App\Middleware\MiddlewareInterface;
use App\Auth;
use App\Redirect;

class CheckIfAuthorised implements MiddlewareInterface
{
    public function handle(?array $data = null):void
    {
        if(Auth::user($_SESSION['id']) === null) {
            Redirect::url('/users/index');
        }
    }
}