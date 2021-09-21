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

    public function scopeSaleableProducts($query)
    {
        return $query->where('type', 'Merchandise Inventory');
    }

    public function getAll()
    {
        return $this->with(['productCategory', 'createdBy', 'updatedBy', 'supplier'])->orderBy('name')->get();
    }

    public function getProductNames()
    {
        return $this->orderBy('name')->get(['id', 'name']);
    }

    public function getSaleableProducts()
    {
        return $this->saleableProducts()->orderBy('name')->get();
    }

    public function getProductUOM()
    {
        return $this->unit_of_measurement;
    }

    public function getOutOfStockMerchandiseProducts($onHandMerchandiseProducts)
    {
        return $this
            ->where('type', 'Merchandise Inventory')
            ->whereNotIn('id', $onHandMerchandiseProducts->pluck('id'))
            ->get();
    }

    public function isProductSaleable($productId = null)
    {
        if (is_null($productId)) {
            $productId = $this->id;
        }

        return $this->where('id', $productId)->saleableProducts()->exists();
    }

    public function isProductMerchandise($productId = null)
    {
        if (is_null($productId)) {
            $productId = $this->id;
        }

        return $this->where('id', $productId)->where('type', 'Merchandise Inventory')->exists();
    }

    public function isProductLimited($onHandQuantity)
    {
        return $this->min_on_hand >= $onHandQuantity;
    }

    public function countProductsOfCompany()
    {
        return $this->count();
    }
}
