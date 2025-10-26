<?php

namespace App\Models;

use App\Enums\Role;

class Client extends User
{
    protected $attributes = [
        'role' => Role::Client,
    ];

    public static function defaultRole(): Role
    {
        return Role::Client;
    }
}
