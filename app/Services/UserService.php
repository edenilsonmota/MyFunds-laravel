<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }
}
