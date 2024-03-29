<?php

namespace App\Models;

use App\Scopes\ActiveWarehouseScope;
use App\Scopes\BranchScope;
use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrnDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function grn()
    {
        return $this->belongsTo(Grn::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class)->withoutGlobalScopes([ActiveWarehouseScope::class]);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function parentModel()
    {
        return $this->grn;
    }

    public function getByWarehouseAndProduct($warehouse, $product)
    {
        return $this->where([
            ['warehouse_id', $warehouse->id],
            ['product_id', $product->id],
        ])
            ->whereIn('grn_id', function ($query) {
                $query->select('id')
                    ->from('grns')
                    ->where('company_id', userCompany()->id)
                    ->whereNotNull('added_by');
            })
            ->get()
            ->load([
                'grn' => function ($query) {
                    return $query->withoutGlobalScopes([BranchScope::class])->with(['supplier']);
                }]
            );
    }
}