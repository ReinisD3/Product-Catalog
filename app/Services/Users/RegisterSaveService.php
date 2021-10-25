<?php

namespace App\Services\Users;

class RegisterSaveService extends UsersBaseService
{
    public function execute(RegisterSaveServiceRequest $registerServiceRequest):void
    {
        $this->usersRepository->add($registerServiceRequest->getUser());
    }
}