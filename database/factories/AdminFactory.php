<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Enums\Role;

class AdminFactory extends UserFactory
{
    protected $model = Admin::class;

    public function definition(): array
    {
        return array_merge(parent::definition(), [
            'role' => Role::Admin->value,
        ]);
    }
}
