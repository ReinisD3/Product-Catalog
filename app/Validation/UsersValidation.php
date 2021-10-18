<?php

namespace app\Validation;

use App\Errors;
use App\Exceptions\FormValidationException;
use App\Exceptions\RepositoryValidationException;
use Respect\Validation\Validator as v;
use App\Models\User;

class UsersValidation
{
    private Errors $errors;

    public function __construct()
    {
        $this->errors = new Errors();
    }

    public function getErrors(): Errors
    {
        return $this->errors;
    }

    public function validateLogin(?User $user): void
    {
        if (empty($user)) {
            $this->errors->add(
                'login',
                "<script type='text/javascript'>alert('User not found! Try Again or make new User account.');</script>"
            );
        }
        if (count($this->errors->all()) > 0) {
            throw new RepositoryValidationException();
        }
    }
    /**
     * @throws FormValidationException
     */
    public function validateRegister(array $data): void
    {

        if ($data['name'] == '') $this->errors->add('registerName', 'Add name here');

        if (!v::stringType()->length(6, null)->validate($data['password']))
            $this->errors->add('registerPassword', 'Need at least 6 characters ');
        if (substr_count($data['password'], ' ') > 0)
            $this->errors->add('registerPassword', 'No spaces allowed ');
        if (!v::email()->validate($data['email']))
            $this->errors->add('registerEmail', 'Wrong email input');

        if (count($this->errors->all()) > 0) {
            throw new FormValidationException();

        }
    }
}

