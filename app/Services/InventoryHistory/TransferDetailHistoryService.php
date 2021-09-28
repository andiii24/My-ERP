<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\TransferDetail;
use Illuminate\Support\Str;

class TransferDetailHistoryService implements DetailHistoryServiceInterface
{
    private $warehouse, $product, $history;

    private function get()
    {
        $this->history = (new TransferDetail())
            ->getByWarehouseAndProduct(
                $this->warehouse,
                $this->product
            );

        return $this;
    }

    private function format()
    {
        $history = $this->history
            ->filter(function ($transferDetail) {
                if ($transferDetail->transfer->transferred_to == $this->warehouse->id && !$transferDetail->transfer->isAdded()) {
                    return false;
                }

                return true;
            })
            ->map(function ($transferDetail) {
                return [
                    'type' => 'TRANSFER',
                    'code' => $transferDetail->transfer->code,
                    'date' => $transferDetail->transfer->issued_on,
                    'quantity' => $transferDetail->quantity,
                    'balance' => 0.00,
                    'unit_of_measurement' => $this->product->unit_of_measurement,

                    'details' => $transferDetail->transfer->transferred_from == $this->warehouse->id ?
                    Str::of('Transferred')->append(' from ', $this->warehouse->name) :
                    Str::of('Transferred')->append(' to ', $transferDetail->transfer->transferredTo->name),

                    'function' => $transferDetail->transfer->transferred_from == $this->warehouse->id ? 'subtract' : 'add',
                ];
            });

        $this->history = $history;

        return $this;
    }

    public function retrieve($warehouse, $product)
    {
        $this->product = $product;

        $this->warehouse = $warehouse;

        return $this->get()->format()->history;
    }
}
