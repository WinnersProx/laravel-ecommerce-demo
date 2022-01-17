<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Http\Requests\Order\InitOrderRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductOrderController extends Controller
{

    /**
     * Initialize a product order
     * @param Product $product
     * @return JsonResponse
     */
    public function initProductOrder(InitOrderRequest $request): JsonResponse
    {
        $product = Product::find($request->product_id);
        $totalPrice = ($product->price * $request->quantity);

        $product->orders()->create([
            'total_price' => $totalPrice,
            'user_id' => $request->user()->id,
            'payment_reference' => Str::uuid()
        ]);

        // Decrease the number of items
        $product->update(['quantity' => $product->quantity - $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => "Your order has been successfuly placed ({$totalPrice} RWF)."
        ]);
    }
}
