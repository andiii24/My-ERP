<?php

namespace App\Services\Models;

use App\Services\Inventory\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class ReturnService
{
    public function add($return, $user)
    {
        if (!$user->hasWarehousePermission('add',
            $return->returnDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to add to one or more of the warehouses.'];
        }

        if (!$return->isApproved()) {
            return [false, 'This transaction is not approved yet.'];
        }

        if ($return->isAdded()) {
            return [false, 'This transaction is already added to inventory.'];
        }

        DB::transaction(function () use ($return) {
            InventoryOperationService::add($return->returnDetails, $return);

            $return->add();
        });

        return [true, ''];
    }
}