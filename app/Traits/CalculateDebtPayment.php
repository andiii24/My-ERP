<?php

namespace App\Traits;

trait CalculateDebtPayment
{
    public function isPaymentInCash()
    {
        return $this->payment_type == 'Cash Payment';
    }

    public function getDebtPayableInPercentageAttribute()
    {
        return 100.00 - $this->cash_paid_in_percentage;
    }

    public function getPaymentInCashAttribute()
    {
        $price = $this->grandTotalPrice;

        if ($this->cash_paid_type == 'percent') {
            $paymentInCash = $price * ($this->cash_paid_in_percentage / 100);
        }

        if ($this->cash_paid_type == 'amount') {
            $paymentInCash = $this->cash_paid;
        }

        return $paymentInCash;
    }

    public function getCashPaidInPercentageAttribute()
    {
        $price = $this->grandTotalPrice;

        if ($price <= 0) {
            return 0.00;
        }

        if ($this->cash_paid_type == 'percent') {
            $cashPaidInPercentage = $this->cash_paid;
        }

        if ($this->cash_paid_type == 'amount') {
            $cashPaidInPercentage = ($this->paymentInCash / $price) * 100;
        }

        return $cashPaidInPercentage;
    }

    public function getPaymentInDebtAttribute()
    {
        $price = $this->grandTotalPrice;

        return $price - $this->paymentInCash;
    }
}
