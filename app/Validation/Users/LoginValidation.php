<?php

namespace app\Validation\Users;

use App\Exceptions\FormValidationException;
use App\Middleware\MiddlewareInterface;
use App\Repositories\MysqlUsersRepository;
use App\Validation\BaseValidation;


class LoginValidation extends BaseValidation implements MiddlewareInterface
{

    public function handle(?array $data = null): void
    {
        $userRepository = new MysqlUsersRepository();
        try {
            if (empty($userRepository->validateLogin($_GET['email'], $_GET['password']))) {
                $this->errors->add('login', 'User not found! Try Again or make new User account.');
            }
            $this->checkErrors();

        } catch (FormValidationException $e) {
            $_SESSION['errors'] = $this->getErrors();
            header('Location:/users/index');
            exit;
        }

    }
}