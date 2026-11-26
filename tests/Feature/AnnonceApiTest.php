<?php

use App\Models\Annonce;
use App\Models\Produit;
use App\Models\Region;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

function annonceData(array $overrides = []): array
{
    return array_merge([
        'produit_id' => Produit::factory()->create()->id,
        'title' => 'Olives de test',
        'description' => 'Une annonce créée par un test.',
        'price' => 10,
        'quantity_kg' => 50,
        'harvested_at' => '2026-11-20',
        'is_available' => true,
    ], $overrides);
}

it('lists the annonces with the expected structure', function () {
    Annonce::factory()->disponible()->count(2)->create();

    $this->getJson('/api/annonces')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonStructure([
            'data' => ['*' => ['id', 'title', 'price', 'quantity_kg', 'harvested_at', 'is_available', 'author', 'produit']],
            'links',
            'meta',
        ]);
});

it('filters the annonces by produit slug', function () {
    $produit = Produit::factory()->create();
    Annonce::factory()->create(['produit_id' => $produit->id, 'title' => 'Dans le produit']);
    Annonce::factory()->create(['title' => 'Hors produit']);

    $this->getJson('/api/annonces?produit='.$produit->slug)
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Dans le produit');
});

it('refuses to create an annonce without a token', function () {
    $this->postJson('/api/annonces', annonceData())->assertUnauthorized();
});

it('forbids updating the annonce of another user', function () {
    $annonce = Annonce::factory()->create();
    Sanctum::actingAs(User::factory()->create());

    $this->putJson('/api/annonces/'.$annonce->id, annonceData())->assertForbidden();
});

it('rejects an invalid annonce', function () {
    Sanctum::actingAs(User::factory()->create());

    $this->postJson('/api/annonces', annonceData(['title' => '']))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title']);
});

it('creates an annonce with its regions', function () {
    $user = User::factory()->create();
    $region = Region::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/annonces', annonceData(['regions' => [$region->id]]))
        ->assertCreated();

    $this->assertDatabaseHas('annonces', ['title' => 'Olives de test', 'user_id' => $user->id]);
    $this->assertDatabaseHas('annonce_region', [
        'annonce_id' => $response->json('data.id'),
        'region_id' => $region->id,
    ]);
});