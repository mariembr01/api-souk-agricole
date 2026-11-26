<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnonceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'quantity_kg' => $this->quantity_kg,
            'harvested_at' => $this->harvested_at?->toIso8601String(),
            'is_available' => $this->is_available,
            'created_at' => $this->created_at?->toIso8601String(),
            'author' => $this->whenLoaded('user', fn () => $this->user->name),
            'produit' => new ProduitResource($this->whenLoaded('produit')),
            'regions' => RegionResource::collection($this->whenLoaded('regions')),
        ];
    }
}