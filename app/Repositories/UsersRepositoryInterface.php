<?php
namespace App\Repositories;


use App\Models\User;

interface UsersRepositoryInterface
{

    public function validateLogin(string $email, string $password): ?User;
    public function add(User $user): void;
    public function getById(int $id): ?User;
    public function getByEmail(string $email):?User;

}