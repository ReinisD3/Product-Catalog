<?php

namespace App\Validation\Users;

use App\Exceptions\FormValidationException;
use App\Middleware\MiddlewareInterface;
use App\Redirect;
use App\Validation\BaseValidation;
use Respect\Validation\Validator as v;


class RegisterFormValidation extends BaseValidation implements MiddlewareInterface
{

    public function handle(?array $data = null): void
    {

        try {
            if ($_POST['name'] == '') $this->errors->add('name', 'Add name');

            if (!v::stringType()->length(6, null)->validate($_POST['password']))
                $this->errors->add('password', 'Need at least 6 characters ');
            if (substr_count($_POST['password'], ' ') > 0)
                $this->errors->add('password', 'No spaces allowed ');
            if ($this->usersRepository->getByEmail($_POST['email']) !== null)
                $this->errors->add('email', 'Email already used');
            if (!v::email()->validate($_POST['email']))
                $this->errors->add('email', 'Wrong email input');

            $this->checkErrors();

        } catch (FormValidationException $e) {
            $_SESSION['errors'] = $this->getErrors();
            Redirect::url("/users/register");
        }

    }
}