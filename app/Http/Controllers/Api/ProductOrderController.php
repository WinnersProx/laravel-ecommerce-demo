<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Http\Requests\Order\InitOrderRequest;
use App\Http\Resources\ProductOrder as ResourcesProductOrder;
use App\Mail\ProductOrderCompleted;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
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

        // TODO: Validate quantity

        $productOrder = $product->orders()->create([
            'total_price' => $totalPrice,
            'user_id' => $request->user()->id,
            'payment_reference' => Str::uuid()
        ]);

        // Decrease the number of items
        $product->update(['quantity' => $product->quantity - $request->quantity]);

        return response()->json([
            'success' => true,
            'order' => new ResourcesProductOrder($productOrder),
            'message' => "Your order has been successfuly placed ({$totalPrice} RWF)."
        ]);
    }

    /**
     * Complete a product order
     * @param ProductOrder $productOrder
     */
    public function completeProductOrder(Request $request)
    {
        $request->validate([
            'payment_reference' => 'required|string|exists:product_orders'
        ]);

        $productOrder = ProductOrder::wherePaymentReference($request->payment_reference)
            ->first();

        if ($request->user()->id !== $productOrder->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to update this order'
            ], 403);
        }

        $productOrder->update(['status' => 'completed']);

        return response()->json([
            'success' => true,
            'message' => 'Order successfully completed'
        ]);
    }
}
