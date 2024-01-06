<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\ReviewCollection;
use App\Http\Requests\ReviewStoreRequest;
use App\Http\Requests\ReviewUpdateRequest;

class ReviewController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = auth()->user()->reviews;
 
        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    /**
     * @param \App\Http\Requests\ReviewStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReviewStoreRequest $request)
    {
       // $this->authorize('create', Review::class);

        $validated = $request->validated();

        $review = Review::create($validated);

        return new ReviewResource($review);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Review $review
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Review $review)
    {
       // $this->authorize('view', $review);

        return new ReviewResource($review);
    }

    /**
     * @param \App\Http\Requests\ReviewUpdateRequest $request
     * @param \App\Models\Review $review
     * @return \Illuminate\Http\Response
     */
    public function update(ReviewUpdateRequest $request, Review $review)
    {
       // $this->authorize('update', $review);

        $validated = $request->validated();

        $review->update($validated);

        return new ReviewResource($review);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Review $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Review $review)
    {
        //$this->authorize('delete', $review);

        $review->delete();

        return response()->noContent();
    }
}
