<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductOrderController extends Controller
{

    /**
     * Initialize a product order
     * @param Product $product
     * @return JsonResponse
     */
    public function initProductOrder(Product $product): JsonResponse
    {
        $order = $product->orders()->create([
            ''
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your order has been successfuly placed'
        ]);
    }
}
