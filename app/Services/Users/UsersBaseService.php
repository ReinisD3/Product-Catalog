<?php

namespace App\Services\Users;

use App\Repositories\MysqlUsersRepository;

abstract class UsersBaseService
{
    protected MysqlUsersRepository $usersRepository;

    public function __construct(MysqlUsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }
}