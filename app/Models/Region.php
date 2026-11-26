<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Region extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function annonces(): BelongsToMany
    {
        return $this->belongsToMany(Annonce::class)->withTimestamps();
    }
}