<?php
namespace App\Repositories;

use App\Models\Collections\UsersCollection;
use App\Models\User;

interface UsersRepositoryInterface
{

    public function validateLogin(string $email, string $password): ?User;

    public function add(User $user): void;
}