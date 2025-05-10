<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_en' => fake()->name(),
            'name_ar' => fake('ar')->name(),
            'code' => fake()->text(5),
            'created_by' => User::inRandomOrder()->first()->id,
            'updated_by' => User::inRandomOrder()->first()->id,
        ];
    }
}
