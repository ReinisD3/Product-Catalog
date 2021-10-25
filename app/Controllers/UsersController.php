<?php

namespace App\Controllers;


use App\Models\User;
use App\Redirect;
use App\Repositories\MysqlUsersRepository;
use App\Repositories\UsersRepositoryInterface;
use Twig\Environment;

class UsersController
{
    private UsersRepositoryInterface $repository;
    private Environment $twig;

    public function __construct(MysqlUsersRepository $repository, Environment $twig)
    {
        $this->repository = $repository;
        $this->twig = $twig;
    }

    public function index(): void
    {
        echo $this->twig->render('Users/login.twig');

    }

    public function logout(): void
    {
        unset($_SESSION['id']);
        echo "Es te";
        Redirect::url('/');

    }

    public function login(): void
    {

        $loggedUser = $this->repository->validateLogin($_GET['email'], $_GET['password']);

        $_SESSION['id'] = $loggedUser->id();
        Redirect::url('/products/show');

    }


    public function register(): void
    {
        echo $this->twig->render('Users\register.twig');
    }

    public function registerSave(): void
    {
            $this->repository->add(new User(
                $_POST['name'],
                $_POST['email'],
                $_POST['password']
            ));

            Redirect::url('/users/index');

    }

}