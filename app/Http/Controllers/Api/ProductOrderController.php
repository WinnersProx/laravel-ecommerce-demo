<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Http\Requests\Order\InitOrderRequest;
use App\Http\Resources\ProductOrder as ResourcesProductOrder;
use App\Mail\ProductOrderCompleted;
use App\Models\ProductOrder;
use App\Services\Order\ComputeOrderPrice;
use App\Services\Order\ComputeStaffOrderPrice;
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
        $product = Product::whereId($request->product_id)->firstOrFail();

        $totalPrice = $product->price * $request->quantity;

        throwIf(
            ($request->quantity > $product->quantity),
            "The quantity should be less than or equal to {$product->quantity}"
        );

        $order = $request->user()->orders()->create([
            'product_id' => $request->product_id,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'payment_reference' => Str::uuid()
        ]);

        $product->update(['quantity' => ($product->quantity - $request->quantity)]);

        return response()->json([
            'success' => true,
            'message' => 'Order successfuly initiated',
            'order' => $order
        ]);
    }

    /**
     * Complete a product order
     * @param ProductOrder $productOrder
     */
    public function completeProductOrder(ProductOrder $order, Request $request)
    {
        throwIf(
            ($order->user->id !== $request->user()->id),
            'You are not authorized to complete this order',
            403
        );

        $order->update(['status' => 'completed']);

        return response()->json([
            'success' => true,
            'message' => 'Order successfully completed'
        ]);
    }
}
