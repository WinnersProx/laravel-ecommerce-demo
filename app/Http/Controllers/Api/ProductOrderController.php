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
        // TODO: Validate expected fields

        // TODO: Validate remaining quantity

        // TODO: Compute pricing

        return response()->json(['success' => true]);
    }

    /**
     * Complete a product order
     * @param ProductOrder $productOrder
     */
    public function completeProductOrder(ProductOrder $order)
    {
        // TODO: Find order
        // TODO: Check order ownership
        // TODO: Set order as completed

        return response()->json([
            'success' => true,
            'message' => 'Order successfully completed'
        ]);
    }
}
