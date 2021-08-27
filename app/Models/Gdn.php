<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Discountable;
use App\Traits\PricingTicket;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Gdn extends Model
{
    use SoftDeletes, Approvable, PricingTicket, Discountable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function gdnDetails()
    {
        return $this->hasMany(GdnDetail::class);
    }

    public function reservation()
    {
        return $this->morphOne(Reservation::class, 'reservable');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function scopeCompanyGdn($query)
    {
        return $query->where('company_id', userCompany()->id);
    }

    public function getCodeAttribute($value)
    {
        return Str::after($value, userCompany()->id . '_');
    }

    public function getCreditPayableInPercentageAttribute()
    {
        return 100.00 - $this->cash_received_in_percentage;
    }

    public function details()
    {
        return $this->gdnDetails;
    }

    public function getAll()
    {
        if (auth()->user()->hasRole('System Manager') || auth()->user()->hasRole('Analyst')) {
            return $this->companyGdn()->latest()->get();
        }

        return $this->companyGdn()
            ->where('warehouse_id', auth()->user()->warehouse_id)
            ->latest()
            ->get();
    }

    public function countGdnsOfCompany()
    {
        return $this->companyGdn()->count();
    }

    public function subtract()
    {
        $this->status = 'Subtracted From Inventory';
        $this->save();
    }

    public function isSubtracted()
    {
        return $this->status == 'Subtracted From Inventory';
    }

    public function getPaymentInCash()
    {
        if (userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPrice;
        }

        if (!userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPriceAfterDiscount;
        }

        if ($this->cash_received_in_percentage < 0) {
            return $price;
        }

        return $price * ($this->cash_received_in_percentage / 100);
    }

    public function getPaymentInCredit()
    {
        if (userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPrice;
        }

        if (!userCompany()->isDiscountBeforeVAT()) {
            $price = $this->grandTotalPriceAfterDiscount;
        }

        if ($this->credit_payable_in_percentage < 0) {
            return $price;
        }

        return $price * ($this->credit_payable_in_percentage / 100);
    }
}
