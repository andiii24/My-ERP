<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Services\ProductMovementHistoryInWarehouseService;

class ProductHistoryByWarehouseController extends Controller
{
    public function __invoke(Warehouse $warehouse, Product $product)
    {
        $this->authorize('view', $warehouse);

        $this->authorize('view', $product);

        $history = (new ProductMovementHistoryInWarehouseService($warehouse, $product))->history();

        return $history;
    }
}
