<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

class UsersService
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $title): ?User
    {
        return User::where('email', $title)->first();
    }
}
