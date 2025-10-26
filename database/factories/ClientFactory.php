<?php

namespace Database\Factories;

use App\Models\Client;
use App\Enums\Role;

class ClientFactory extends UserFactory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return array_merge(parent::definition(), [
            'role' => Role::Client->value,
        ]);
    }
}
