<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "email" => Str::random(5) . "@demo.test",
            "password" => password_hash(Str::random(64), PASSWORD_BCRYPT),
            "prenom" => "user",
            "nom" => "demo",
            "role" => "user",
        ];
    }

}
