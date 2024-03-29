<?php

namespace App\Traits;

trait PricingProduct
{
    public function getOriginalUnitPriceAttribute()
    {
        $inputtedUnitPrice = $this->unit_price;

        if (!userCompany()->isPriceBeforeVAT()) {
            $inputtedUnitPrice = $this->unit_price * ($this->product->tax->amount + 1);
        }

        return number_format($inputtedUnitPrice, 2, thousands_separator:'');
    }

    public function getUnitPriceAttribute($value)
    {
        if (!userCompany()->isPriceBeforeVAT()) {
            $value = $value / ($this->product->tax->amount + 1);
        }

        return number_format($value, 2, thousands_separator:'');
    }

    public function getTotalPriceAttribute()
    {
        $totalPrice = number_format($this->unit_price * $this->quantity, 2, thousands_separator:'');
        $discount = ($this->discount ?? 0.00) / 100;
        $discountAmount = 0.00;

        if (userCompany()->isDiscountBeforeVAT()) {
            $discountAmount = number_format($totalPrice * $discount, 2, thousands_separator:'');
        }

        return number_format($totalPrice - $discountAmount, 2, thousands_separator:'');
    }

    public function getTotalVatAttribute()
    {
        $totalVat = $this->Product->tax->amount * $this->totalPrice;

        return number_format($totalVat, 2, thousands_separator:'');
    }
}
