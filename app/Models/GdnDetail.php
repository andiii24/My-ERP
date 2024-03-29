<?php

namespace App\Models;

use App\Scopes\ActiveWarehouseScope;
use App\Scopes\BranchScope;
use App\Traits\PricingProduct;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GdnDetail extends Model
{
    use SoftDeletes, PricingProduct, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function gdn()
    {
        return $this->belongsTo(Gdn::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class)->withoutGlobalScopes([ActiveWarehouseScope::class]);
    }

    public function parentModel()
    {
        return $this->gdn;
    }

    public function getByWarehouseAndProduct($warehouse, $product)
    {
        return $this->where([
            ['warehouse_id', $warehouse->id],
            ['product_id', $product->id],
        ])
            ->whereIn('gdn_id', function ($query) {
                $query->select('id')
                    ->from('gdns')
                    ->where('company_id', userCompany()->id)
                    ->whereNotNull('subtracted_by');
            })
            ->get()
            ->load([
                'gdn' => function ($query) {
                    return $query->withoutGlobalScopes([BranchScope::class])->with(['customer']);
                }]
            );
    }
}
