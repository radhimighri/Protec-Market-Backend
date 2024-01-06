<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\ReviewCollection;

class ProductReviewsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Product $product)
    {
       //$this->authorize('view', $product);

        $search = $request->get('search', '');

        $reviews = $product
            ->reviews()
            ->search($search)
            ->latest()
            ->get();

        return new ReviewCollection($reviews);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
       // $this->authorize('create', Review::class);

        $validated = $request->validate([
            'review' => ['required', 'max:255', 'string'],
        ]);

        $review = $product->reviews()->create($validated);

        return new ReviewResource($review);
    }
}
