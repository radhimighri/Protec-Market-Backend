<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $this->authorize('view-any', Categorie::class);

        $search = $request->get('search', '');

        $categories = Categorie::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.categories.index', compact('categories', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Categorie::class);

        return view('app.categories.create');
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

        return redirect()
            ->route('categories.edit', $categorie)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Categorie $categorie
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Categorie $categorie)
    {
        $this->authorize('view', $categorie);

        return view('app.categories.show', compact('categorie'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Categorie $categorie
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Categorie $categorie)
    {
        $this->authorize('update', $categorie);

        return view('app.categories.edit', compact('categorie'));
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

        return redirect()
            ->route('categories.edit', $categorie)
            ->withSuccess(__('crud.common.saved'));
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

        return redirect()
            ->route('categories.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
