<?php

namespace Database\Factories;

use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnonceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'produit_id' => Produit::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 1, 100),
            'quantity_kg' => fake()->numberBetween(1, 500),
            'harvested_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'is_available' => false,
        ];
    }

    public function disponible(): static
    {
        return $this->state(fn () => ['is_available' => true]);
    }
}