<?php

namespace app\Controllers;

use App\Exceptions\FormValidationException;
use App\Exceptions\RepositoryValidationException;
use App\Models\User;
use App\Redirect;
use App\Repositories\MysqlUsersRepository;
use App\Repositories\UsersRepositoryInterface;
use App\Validation\UsersValidation;
use App\View;

class UsersController
{
    private UsersRepositoryInterface $repository;
    private UsersValidation $validator;

    public function __construct()
    {
        $this->repository = new MysqlUsersRepository();
        $this->validator = new UsersValidation();
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

        $loggedUser = $this->repository->validateLogin(
            trim($_GET['email']),
            $_GET['password']
        );
        try {
            $this->validator->validateLogin($loggedUser);
            $_SESSION['id'] = $loggedUser->id();

        } catch (RepositoryValidationException $e) {

            echo "<script type='text/javascript'>alert('User not found! Try Again or make new User account.');</script>";
        }
        return new Redirect('/');
    }


    public function register(): View
    {
        return new View('Users\register.twig');
    }

    public function registerSave(): object
    {
        try {
            $this->validator->validateRegister($_POST);
            $this->repository->add(new User(
                $_POST['name'],
                $_POST['email'],
                $_POST['password']
            ));
            echo "<script type='text/javascript'>alert('User registered');</script>";
            return new Redirect('/users/index');
        } catch (FormValidationException $e) {

            $_SESSION['errors'] = $this->validator->getErrors();
            return new View('Users\register.twig');
        }

    }

}