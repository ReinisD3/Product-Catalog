<?php

namespace App\Services\Users;

use App\Models\User;

class RegisterSaveServiceRequest
{
    private User $user;
    public function __construct(array $post)
    {
        $this->user = new User(
            $post['name'],
            $post['email'],
            $post['password']
        );
    }
    public function getUser(): User
    {
        return $this->user;
    }
}