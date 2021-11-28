<?php

namespace App\View\Components\Common;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class ProductList extends Component
{
    public $products, $name, $selectedProductId, $tags, $excludedProducts;

    public function __construct($name, $selectedProductId, $tags, $excludedProducts = null)
    {
        $this->name = $name;

        $this->selectedProductId = $selectedProductId;

        $this->tags = $tags;

        $this->excludedProducts = $excludedProducts;

        $this->products = Cache::store('array')->rememberForever(auth()->id() . '_' . 'productLists', function () {
            return Product::select(['id', 'product_category_id', 'name', 'code'])
                ->when($this->excludedProducts, fn($query) => $query->whereNotIn('id', $this->excludedProducts->toArray()))
                ->with('productCategory:id,name')
                ->orderBy('name')
                ->get();
        });
    }

    public function render()
    {
        return view('components.common.product-list');
    }
}
