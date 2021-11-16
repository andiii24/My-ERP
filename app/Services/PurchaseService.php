<?php

namespace App\Services;

class PurchaseService
{
    public function convertToGrn($purchase)
    {
        if ($purchase->isClosed()) {
            return [false, 'This purchase is closed.', ''];
        }

        $data = [
            'purchase_id' => $purchase->id,
            'supplier_id' => $purchase->supplier_id,
            'grn' => $purchase->purchaseDetails->toArray(),
        ];

        return [true, '', $data];
    }
}
