<?php

namespace app\Middleware;

use App\Middleware\MiddlewareInterface;
use App\Auth;
use app\Redirect;

class CheckIfAuthorised implements MiddlewareInterface
{
    public function handle(?array $data = null):void
    {
        if(Auth::user($_SESSION['id']) === null) {
            header('Location:/users/index');
            exit;
        }
    }
}