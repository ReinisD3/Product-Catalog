<?php

namespace App\Services\Users;

class LoginService extends UsersBaseService
{
    public function execute(LoginServiceRequest $loginServiceRequest):void
    {
        $loggedUser = $this->usersRepository->validateLogin(
            $loginServiceRequest->getEmail(),
            $loginServiceRequest->getPassword());
        $_SESSION['id'] = $loggedUser->id();
    }
}