<?php

namespace app\Controllers;

use App\Exceptions\FormValidationException;
use App\Exceptions\RepositoryValidationException;
use App\Models\User;
use App\Redirect;
use App\Repositories\MysqlUsersRepository;
use App\Repositories\UsersRepositoryInterface;
use App\View;

class UsersController
{
    private UsersRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = new MysqlUsersRepository();
    }

    public function index(): View
    {
        return new View('Users/login.twig');

    }

    public function logout(): Redirect
    {
        unset($_SESSION['id']);
        return new Redirect('/');

    }

    public function login(): Redirect
    {

        $loggedUser = $this->repository->validateLogin($_GET['email'], $_GET['password']);

        $_SESSION['id'] = $loggedUser->id();
        return new Redirect('/products/show');

    }


    public function register(): View
    {
        return new View('Users\register.twig');
    }

    public function registerSave(): Redirect
    {
            $this->repository->add(new User(
                $_POST['name'],
                $_POST['email'],
                $_POST['password']
            ));

            return new Redirect('/users/index');

    }

}