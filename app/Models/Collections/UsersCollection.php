<?php


namespace app\Models\Collections;

use App\Models\User;

class UsersCollection
{
    private array $users = [];

    public function __construct(array $users = [])
    {

        foreach ($users as $user) {
            if ($user instanceof User) $this->add($user);
        }

    }

    public function add(User $user): void
    {
        /** @var User $user */
        $this->users[$user->id()] = $user;
    }

    public function users(): array
    {
        return $this->users;
    }
}