<?php

namespace App\Services\Order;

use App\Models\Product;

class ComputeOrderPrice
{

    public function compute(Product $product, $quantity): float
    {
        return ($product->price * $quantity);
    }
}
