<?php

namespace App\Http\Controllers\Api;

use App\Models\Customer;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\ReviewCollection;
use App\Http\Requests\ReviewUpdateRequest;
class CustomerReviewsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
/*    public function index(Request $request, Customer $customer)
     {
      //  $this->authorize('view', $customer);

        $search = $request->get('search', '');

        $reviews = $customer
            ->reviews()
            ->search($search)
            ->latest()
            ->paginate();

        return new ReviewCollection($reviews);
    }
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
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
/*     public function store(Request $request, Customer $customer)
    {
       // $this->authorize('create', Review::class);

        $validated = $request->validate([
            'review' => ['required',  'string'],
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $review = $customer->reviews()->create($validated);

        return new ReviewResource($review);
    } */
    public function store(Request $request)
    {
        $this->validate($request, [
            'review' => ['required',  'string'],
            'product_id' => ['required', 'exists:products,id'],
        ]);
 
        $review = new Review;
        $review->review = $request->review;
        $review->product_id = $request->product_id;
 
        if (auth()->user()->reviews()->save($review))
            return response()->json([
                'success' => true,
                'data' => [$review->toArray()]
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Review not added'
            ], 500);
    }

    public function show($id)
    {
        $review = auth()->user()->reviews()->find($id);
 
        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found '
            ], 400);
        }
        return response()->json([
            'success' => true,
            'data' => $review->toArray()
        ], 200);
    }
    /* public function update(ReviewUpdateRequest $request, Customer $customer)
    {
       // $this->authorize('create', Review::class);

        $validated = $request->validate([
            'review' => ['required', 'max:255', 'string'],
            'product_id' => ['required', 'exists:products,id'],
        ]);

        $review = $customer->reviews()->update($validated);

        return new ReviewResource($review);
    } */
    public function update(Request $request, $id)
    {
        $review = auth()->user()->reviews()->find($id);
 
        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 400);
        }
 
        $updated = $review->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Review can not be updated'
            ], 500);
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Review $review
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = auth()->user()->reviews()->find($id);
 
        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 400);
        }
 
        if ($review->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Review can not be deleted'
            ], 500);
        }
    }
    
}
