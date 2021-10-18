<?php

namespace App\Models;

class User
{


    private string $name;
    private string $password;
    private string $email;
    private ?int $id;

    public function __construct(?string $name, string $email, string $password, ?int $id = null )
    {
        $this->name = $name;
        $this->password = password_hash($password,PASSWORD_BCRYPT);
        $this->email = $email;
        $this->id = $id;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function name(): ?string
    {
        return $this->name ?? null;
    }
    public function id():int
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

}
