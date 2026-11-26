<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProduitResource;
use App\Models\Produit;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProduitController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ProduitResource::collection(
            Produit::withCount('annonces')->orderBy('name')->get()
        );
    }

    public function show(Produit $produit): ProduitResource
    {
        return new ProduitResource($produit->loadCount('annonces'));
    }
}