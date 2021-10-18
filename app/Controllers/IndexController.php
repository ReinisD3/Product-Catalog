<?php

namespace app\Controllers;

use App\View;

class IndexController
{
    public function index(): View
    {
        return new View('index.twig');
    }
}