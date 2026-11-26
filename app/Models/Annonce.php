<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Annonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id', 'title', 'description', 'price',
        'quantity_kg', 'harvested_at', 'is_available',
    ];

    protected function casts(): array
    {
        return [
            'harvested_at' => 'date',
            'is_available' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class)->withTimestamps();
    }
}