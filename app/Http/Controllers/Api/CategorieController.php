<?php

namespace App\Http\Controllers\Api;

use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategorieResource;
use App\Http\Resources\CategorieCollection;
use App\Http\Requests\CategorieStoreRequest;
use App\Http\Requests\CategorieUpdateRequest;

class CategorieController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       // $this->authorize('view-any', Categorie::class);

        $search = $request->get('search', '');

        $categories = Categorie::search($search)
            ->latest()
            ->get();

        return new CategorieCollection($categories);
    }

    /**
     * @param \App\Http\Requests\CategorieStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategorieStoreRequest $request)
    {
        $this->authorize('create', Categorie::class);

        $validated = $request->validated();
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('public');
        }

        $categorie = Categorie::create($validated);

        return new CategorieResource($categorie);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Categorie $categorie
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Categorie $categorie)
    {
     //   $this->authorize('view', $categorie);

        return new CategorieResource($categorie);
    }

    /**
     * @param \App\Http\Requests\CategorieUpdateRequest $request
     * @param \App\Models\Categorie $categorie
     * @return \Illuminate\Http\Response
     */
    public function update(
        CategorieUpdateRequest $request,
        Categorie $categorie
    ) {
        $this->authorize('update', $categorie);

        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            if ($categorie->logo) {
                Storage::delete($categorie->logo);
            }

            $validated['logo'] = $request->file('logo')->store('public');
        }

        $categorie->update($validated);

        return new CategorieResource($categorie);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Categorie $categorie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Categorie $categorie)
    {
        $this->authorize('delete', $categorie);

        if ($categorie->logo) {
            Storage::delete($categorie->logo);
        }

        $categorie->delete();

        return response()->noContent();
    }
}
