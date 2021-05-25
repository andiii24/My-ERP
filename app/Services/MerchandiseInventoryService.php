<?php

namespace App\Services;

use App\Models\Merchandise;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Services\SetDataOwnerService;

class MerchandiseInventoryService
{
    private $merchandise;

    public function __construct(Merchandise $merchandise)
    {
        $this->merchandise = $merchandise;
    }

    public function add($detail)
    {
        DB::transaction(function () use ($detail) {
            $merchandise = $this->merchandise->firstOrCreate(
                [
                    'product_id' => $detail->product_id,
                    'warehouse_id' => $detail->warehouse_id,
                ],
                Arr::add(SetDataOwnerService::forNonTransaction(),
                    'on_hand', 0.00)
            );

            $merchandise->on_hand = $merchandise->on_hand + $detail->quantity;

            $merchandise->updated_by = SetDataOwnerService::forUpdate()['updated_by'];

            $merchandise->save();
        });
    }

    public function isAvailable($detail)
    {
        return $this->merchandise->where([
            ['product_id', $detail->product_id],
            ['warehouse_id', $detail->warehouse_id],
            ['on_hand', '>=', $detail->quantity],
        ])->exists();
    }

    public function subtract($detail)
    {
        $merchandise = $this->merchandise->where([
            ['product_id', $detail->product_id],
            ['warehouse_id', $detail->warehouse_id],
            ['on_hand', '>=', $detail->quantity],
        ])->first();

        $merchandise->on_hand = $merchandise->on_hand - $detail->quantity;

        $merchandise->updated_by = SetDataOwnerService::forUpdate()['updated_by'];

        $merchandise->save();
    }
}
