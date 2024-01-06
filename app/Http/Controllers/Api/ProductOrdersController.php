<?php
namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCollection;

class ProductOrdersController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Product $product)
    {
       // $this->authorize('view', $product);

        $search = $request->get('search', '');

        $orders = $product
            ->order()
            ->search($search)
            ->latest()
            ->get();

        return new OrderCollection($orders);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, Order $order)
    {
        $this->authorize('update', $product);

        $product->order()->syncWithoutDetaching([$order->id]);

        return response()->noContent();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Product $product, Order $order)
    {
        $this->authorize('update', $product);

        $product->order()->detach($order);

        return response()->noContent();
    }
}
