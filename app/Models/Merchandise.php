<?php

namespace App\Models;

use App\Models\MerchandiseBatch;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Merchandise extends Model
{
    use MultiTenancy, SoftDeletes;

    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getOnHandAttribute()
    {
        return number_format(
            $this->available + $this->reserved,
            2, '.', ''
        );
    }

    public function merchandiseBatches()
    {
        return $this->hasMany(MerchandiseBatch::class);
    }

    public function inventoryHistories()
    {
        return $this->hasMany(InventoryHistory::class);
    }
}