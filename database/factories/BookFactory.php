<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'user_id' => User::factory()->create()->id,
            'author_id' => Author::factory()->create()->id,
        ];
    }
}
