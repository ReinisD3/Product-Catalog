<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\Users\LoginService;
use App\Services\Users\LoginServiceRequest;
use App\Services\Users\RegisterSaveService;
use App\Services\Users\RegisterSaveServiceRequest;
use Twig\Environment;

class UsersController
{
    private Environment $twig;
    private LoginService $loginService;
    private RegisterSaveService $registerSaveService;

    public function __construct(Environment         $twig,
                                LoginService        $loginService,
                                RegisterSaveService $registerSaveService)
    {
        $this->twig = $twig;
        $this->loginService = $loginService;
        $this->registerSaveService = $registerSaveService;
    }

    public function index(): void
    {
        echo $this->twig->render('Users/login.twig');

    }

    public function logout(): void
    {
        unset($_SESSION['id']);

        Redirect::url('/');

    }

    public function login(): void
    {
        $loginServiceRequest = new LoginServiceRequest($_GET, $_SESSION['id']);
        $this->loginService->execute($loginServiceRequest);

        Redirect::url('/products/show');

    }


    public function register(): void
    {
        echo $this->twig->render('Users\register.twig');
    }

    public function registerSave(): void
    {
        $request = new RegisterSaveServiceRequest($_POST);
        $this->registerSaveService->execute($request);

        Redirect::url('/users/index');

    }

}