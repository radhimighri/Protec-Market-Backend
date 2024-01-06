<?php
namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategorieCollection;

class ProductCategoriesController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Product $product)
    {
      //  $this->authorize('view', $product);

        $search = $request->get('search', '');

        $categories = $product
            ->categories()
            ->search($search)
            ->latest()
            ->get();

        return new CategorieCollection($categories);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @param \App\Models\Categorie $categorie
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request,
        Product $product,
        Categorie $categorie
    ) {
        $this->authorize('update', $product);

        $product->categories()->syncWithoutDetaching([$categorie->id]);

        return response()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @param \App\Models\Categorie $categorie
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        Request $request,
        Product $product,
        Categorie $categorie
    ) {
        $this->authorize('update', $product);

        $product->categories()->detach($categorie);

        return response()->noContent();
    }
}
