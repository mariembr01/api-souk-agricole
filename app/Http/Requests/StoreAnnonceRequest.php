<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnonceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'produit_id' => ['required', 'integer', 'exists:produits,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity_kg' => ['required', 'integer', 'min:1'],
            'harvested_at' => ['required', 'date'],
            'is_available' => ['boolean'],
            'regions' => ['array'],
            'regions.*' => ['integer', 'exists:regions,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'price.required' => 'Le prix est obligatoire.',
            'price.min' => 'Le prix ne peut pas être négatif.',
            'quantity_kg.required' => 'La quantité est obligatoire.',
            'quantity_kg.min' => 'La quantité doit être au moins de 1 kg.',
            'produit_id.exists' => 'Ce produit n\'existe pas.',
            'harvested_at.required' => 'La date de récolte est obligatoire.',
        ];
    }
}