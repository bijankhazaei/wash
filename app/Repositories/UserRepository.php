<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends Repository
{
    /**
     * @return string
     */
    public function model() : string
    {
        return User::class;
    }
}
