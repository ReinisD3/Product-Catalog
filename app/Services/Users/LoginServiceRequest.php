<?php

namespace App\Services\Users;

class LoginServiceRequest
{
    private string $email;
    private string $password;
    private ?string $userId;

    public function __construct(array $get, ?string $userId = null)
    {
        $this->email = $get['email'];
        $this->password = $get['password'];
        $this->userId = $userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }
}