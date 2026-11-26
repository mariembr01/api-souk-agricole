<?php

namespace Database\Seeders;

use App\Models\Annonce;
use App\Models\Produit;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = collect([
            User::factory()->create(['name' => 'Agriculteur Un', 'email' => 'agri1@example.com']),
            User::factory()->create(['name' => 'Agriculteur Deux', 'email' => 'agri2@example.com']),
        ]);

        $produits = collect(['Olives', 'Dattes', 'Amandes'])->map(
            fn (string $name) => Produit::create(['name' => $name, 'slug' => \Illuminate\Support\Str::slug($name)])
        );

        $regions = collect(['Sidi Bouzid', 'Sfax', 'Kairouan', 'Gabès', 'Nabeul'])->map(
            fn (string $name) => Region::create(['name' => $name, 'slug' => \Illuminate\Support\Str::slug($name)])
        );

        for ($i = 0; $i < 20; $i++) {
            $annonce = Annonce::factory()->disponible()->create([
                'user_id' => $users->random()->id,
                'produit_id' => $produits->random()->id,
            ]);
            $annonce->regions()->attach($regions->random(rand(1, 2))->pluck('id'));
        }
    }
}