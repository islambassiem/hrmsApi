<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entity>
 */
class EntityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();

        return [
            'name_en' => fake()->company(),
            'name_ar' => fake()->company(),
            'code' => Str::random(10),
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ];
    }
}
