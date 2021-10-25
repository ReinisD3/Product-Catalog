<?php

namespace App\Controllers;


use App\Auth;
use Twig\Environment;

class IndexController
{

    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function index(): void
    {
        echo $this->twig->render('index.twig', ['userName' => Auth::user($_SESSION['id'])]);
    }
}