<?php

namespace App\Models;

use App\Enums\Role;

class Admin extends User
{
    protected $attributes = [
        'role' => Role::Admin,
    ];

    public static function defaultRole(): Role
    {
        return Role::Admin;
    }
}
