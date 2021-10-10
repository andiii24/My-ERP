<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'properties' => 'array',
    ];

    public function merchandises()
    {
        return $this->hasMany(Merchandise::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function gdnDetails()
    {
        return $this->hasMany(GdnDetail::class);
    }

    public function transferDetails()
    {
        return $this->hasMany(TransferDetail::class);
    }

    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }

    public function grnDetails()
    {
        return $this->hasMany(GrnDetail::class);
    }

    public function tenderDetails()
    {
        return $this->hasMany(TenderDetail::class);
    }

    public function sivDetails()
    {
        return $this->hasMany(SivDetail::class);
    }

    public function damageDetails()
    {
        return $this->hasMany(DamageDetail::class);
    }

    public function proformaInvoiceDetails()
    {
        return $this->hasMany(ProformaInvoiceDetail::class);
    }

    public function adjustmentDetails()
    {
        return $this->hasMany(AdjustmentDetail::class);
    }

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class);
    }

    public function reservationDetails()
    {
        return $this->hasMany(ReservationDetail::class);
    }

    public function setPropertiesAttribute($array)
    {
        $properties = [];

        foreach ($array as $item) {
            if (is_null($item['key']) || is_null($item['value'])) {
                continue;
            }

            $properties[] = $item;
        }

        $this->attributes['properties'] = json_encode($properties);
    }

    public function isProductLimited($onHandQuantity)
    {
        return $this->min_on_hand >= $onHandQuantity;
    }

    public function getOnHandMerchandiseProductsQuery()
    {
        if (auth()->user()->getAllowedWarehouses('read')->isEmpty()) {
            return collect();
        }

        return $this->whereHas('merchandises', function ($query) {
            $query->whereIn('warehouse_id', auth()->user()->getAllowedWarehouses('read')->pluck('id'))
                ->where('available', '>', 0)
                ->orWhere('reserved', '>', 0);
        });
    }

    public function getOutOfStockMerchandiseProductsQuery($warehouseId = null)
    {
        if (auth()->user()->getAllowedWarehouses('read')->isEmpty()) {
            return collect();
        }

        return $this->whereNotIn('id', function ($query) use ($warehouseId) {
            $query->select('product_id')
                ->from('merchandises')
                ->where('company_id', userCompany()->id)
                ->whereIn('warehouse_id', auth()->user()->getAllowedWarehouses('read')->pluck('id'))
                ->when($warehouseId, fn($query) => $query->where('warehouse_id', $warehouseId))
                ->where('available', '>', 0)
                ->orWhere('reserved', '>', 0);
        });
    }

    public function getLimitedMerchandiseProductsQuery($warehouseId = null)
    {
        if (auth()->user()->getAllowedWarehouses('read')->isEmpty()) {
            return collect();
        }

        return $this->whereHas('merchandises', function ($query) use ($warehouseId) {
            $query->whereIn('warehouse_id', auth()->user()->getAllowedWarehouses('read')->pluck('id'))
                ->when($warehouseId, fn($query) => $query->where('warehouse_id', $warehouseId))
                ->whereRaw('products.min_on_hand != 0')
                ->whereRaw('merchandises.available <= products.min_on_hand');
        });
    }
}
