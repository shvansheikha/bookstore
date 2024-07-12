<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'name' => $this->faker->name
        ];
    }
}
