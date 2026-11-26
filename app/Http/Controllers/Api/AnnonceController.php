<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IndexAnnonceRequest;
use App\Http\Requests\StoreAnnonceRequest;
use App\Http\Requests\UpdateAnnonceRequest;
use App\Http\Resources\AnnonceResource;
use App\Models\Annonce;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class AnnonceController extends Controller
{
    public function index(IndexAnnonceRequest $request): AnonymousResourceCollection
    {
        $perPage = (int) ($request->validated('per_page') ?? 5);

        $annonces = Annonce::with('user', 'produit', 'regions')
            ->when($request->validated('produit'), fn (Builder $query, string $slug) => $query->whereRelation('produit', 'slug', $slug))
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        return AnnonceResource::collection($annonces);
    }

    public function store(StoreAnnonceRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['is_available'] = $request->boolean('is_available', true);

        $annonce = $request->user()->annonces()->create(Arr::except($validated, ['regions']));
        $annonce->regions()->sync($validated['regions'] ?? []);

        return (new AnnonceResource($annonce->load('user', 'produit', 'regions')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Annonce $annonce): AnnonceResource
    {
        return new AnnonceResource($annonce->load('user', 'produit', 'regions'));
    }

    public function update(UpdateAnnonceRequest $request, Annonce $annonce): AnnonceResource
    {
        $validated = $request->validated();
        $validated['is_available'] = $request->boolean('is_available', true);

        $annonce->update(Arr::except($validated, ['regions']));
        $annonce->regions()->sync($validated['regions'] ?? []);

        return new AnnonceResource($annonce->load('user', 'produit', 'regions'));
    }

    public function destroy(Annonce $annonce): Response
    {
        Gate::authorize('delete', $annonce);

        $annonce->delete();

        return response()->noContent();
    }
}