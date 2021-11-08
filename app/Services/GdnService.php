<?php

namespace App\Services;

use App\Models\Credit;
use App\Services\InventoryOperationService;
use App\Services\NextReferenceNumService;
use Illuminate\Support\Facades\DB;

class GdnService
{
    public function subtract($gdn)
    {
        if (!auth()->user()->hasWarehousePermission('sales',
            $gdn->gdnDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to sell from one or more of the warehouses.'];
        }

        if (!$gdn->isApproved()) {
            return [false, 'This Delivery Order is not approved yet.'];
        }

        if ($gdn->isSubtracted()) {
            return [false, 'This Delivery Order is already subtracted from inventory'];
        }

        $from = $gdn->reservation()->exists() ? 'reserved' : 'available';

        $unavailableProducts = InventoryOperationService::unavailableProducts($gdn->gdnDetails, $from);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($gdn, $from) {
            InventoryOperationService::subtract($gdn->gdnDetails, $from);

            $gdn->subtract();
        });

        return [true, ''];
    }

    public function convertToCredit($gdn)
    {
        if (!$gdn->isApproved()) {
            return [false, 'This delivery order is not approved yet.'];
        }

        if ($gdn->credit()->exists()) {
            return [false, 'This delivery order already has a credit record.'];
        }

        Credit::create([
            'code' => NextReferenceNumService::table('credits'),
            'cash_amount' => $gdn->payment_in_cash,
            'credit_amount' => $gdn->payment_in_credit,
            'credit_amount_settled' => 0.00,
            'due_date' => $gdn->due_date,
        ]);

        return [true, ''];
    }
}
