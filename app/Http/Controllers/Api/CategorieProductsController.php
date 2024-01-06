<?php
namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;

class CategorieProductsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Categorie $categorie
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Categorie $categorie)
    {
       // $this->authorize('view', $categorie);

        $search = $request->get('search', '');

        $products = $categorie
            ->products()
            ->search($search)
            ->latest()
            ->get();

        return new ProductCollection($products);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Categorie $categorie
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request,
        Categorie $categorie,
        Product $product
    ) {
        $this->authorize('update', $categorie);

        $categorie->products()->syncWithoutDetaching([$product->id]);

        return response()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Categorie $categorie
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        Request $request,
        Categorie $categorie,
        Product $product
    ) {
        $this->authorize('update', $categorie);

        $categorie->products()->detach($product);

        return response()->noContent();
    }
}
