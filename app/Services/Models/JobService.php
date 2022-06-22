<?php

namespace App\Services\Models;

use App\Models\Job;
use App\Services\Inventory\InventoryOperationService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class JobService
{
    public function addToWorkInProcess($request, $job)
    {
        if (!$job->isApproved()) {
            return [false, 'This job is not approved yet.', ''];
        }

        DB::transaction(function () use ($request, $job) {
            for ($i = 0; $i < count($job->jobDetails); $i++) {
                if (!isset($request->job[$i])) {
                    continue;
                }

                if ($request->job[$i]['product_id'] != $job->jobDetails[$i]->product_id) {
                    continue;
                }

                if ($job->jobDetails[$i]->isWipCompleted() || $job->jobDetails[$i]->isAvailableCompleted() || $job->jobDetails[$i]->isJobDetailCompleted()) {
                    continue;
                }

                if (!$this->isQuantityValid($job->jobDetails[$i]->quantity, $job->jobDetails[$i]->available, $job->jobDetails[$i]->wip + $request->job[$i]['wip'])) {
                    return false;
                }

                $job->jobDetails[$i]->update([
                    'product_id' => $request->job[$i]['product_id'],
                    'wip' => $request->job[$i]['wip'] + $job->jobDetails[$i]->wip,
                ]);

                $billOfMaterialdetails = $job->jobDetails[$i]->billOfMaterial->billOfMaterialDetails()->get(['product_id', 'quantity'])->toArray();
                $billOfMaterialdetails = data_set($billOfMaterialdetails, '*.warehouse_id', $job->factory_id);
                $quantity = $request->job[$i]['wip'];

                $details[] = collect($billOfMaterialdetails)->transform(function ($detail) use ($quantity) {
                    $detail['quantity'] = $detail['quantity'] * $quantity;
                    return $detail;
                });

                $addDetails[] = [
                    'product_id' => $request->job[$i]['product_id'],
                    'quantity' => $request->job[$i]['wip'],
                    'warehouse_id' => $job->factory_id,
                ];
            }

            if (isset($details) && count($details)) {
                $billOfMaterialdetails = Arr::flatten($details, 1);

                if (!InventoryOperationService::areAvailable($billOfMaterialdetails)) {
                    return false;
                }
            }

            if (isset($billOfMaterialdetails) && count($billOfMaterialdetails)) {
                InventoryOperationService::subtract($billOfMaterialdetails);
            }

            if (isset($addDetails) && count($addDetails)) {
                InventoryOperationService::add($addDetails, 'wip');
            }
        });

        return [true, ''];
    }

    public function addToAvailable($request, $job)
    {
        if (!$job->isApproved()) {
            return [false, 'This job is not approved yet.', ''];
        }

        DB::transaction(function () use ($request, $job) {
            for ($i = 0; $i < count($job->jobDetails); $i++) {
                if (!isset($request->job[$i])) {
                    continue;
                }

                if ($request->job[$i]['product_id'] != $job->jobDetails[$i]->product_id) {
                    continue;
                }

                if ($job->jobDetails[$i]->isAvailableCompleted()) {
                    continue;
                }

                if (!$this->isQuantityValid($job->jobDetails[$i]->quantity, $request->job[$i]['available'], 0)) {
                    return false;
                }

                if ($request->job[$i]['available'] > $job->jobDetails[$i]->wip) {
                    $quantity = $request->job[$i]['available'] - $job->jobDetails[$i]->wip;

                    $availableDetails[$i] = [
                        'product_id' => $request->job[$i]['product_id'],
                        'wip' => 0,
                        'available' => $request->job[$i]['available'] + $job->jobDetails[$i]->available,
                        'quantity' => $quantity,
                        'warehouse_id' => $job->factory_id,
                    ];

                    $wipDetails[$i] = [
                        'product_id' => $request->job[$i]['product_id'],
                        'quantity' => $job->jobDetails[$i]->wip,
                        'warehouse_id' => $job->factory_id,
                    ];

                    $job->jobDetails[$i]->update(Arr::only($availableDetails[$i], ['product_id', 'wip', 'available']));

                    $billOfMaterialdetails = $job->jobDetails[$i]->billOfMaterial->billOfMaterialDetails()->get(['product_id', 'quantity'])->toArray();
                    $billOfMaterialdetails = data_set($billOfMaterialdetails, '*.warehouse_id', $job->factory_id);

                    $details[$i] = collect($billOfMaterialdetails)->transform(function ($detail) use ($quantity) {
                        $detail['quantity'] = $detail['quantity'] * $quantity;
                        return $detail;
                    });
                }

                if ($request->job[$i]['available'] <= $job->jobDetails[$i]->wip) {
                    $quantity = $request->job[$i]['available'];

                    $wipDetails[$i] = [
                        'product_id' => $request->job[$i]['product_id'],
                        'wip' => $job->jobDetails[$i]->wip - $request->job[$i]['available'],
                        'available' => $request->job[$i]['available'] + $job->jobDetails[$i]->available,
                        'quantity' => $quantity,
                        'warehouse_id' => $job->factory_id,
                    ];

                    $job->jobDetails[$i]->update(Arr::only($wipDetails[$i], ['product_id', 'wip', 'available']));
                }
            }

            if (isset($details) && count($details)) {
                $billOfMaterialdetails = Arr::flatten($details, 1);

                if (!InventoryOperationService::areAvailable($billOfMaterialdetails)) {
                    return false;
                }

                InventoryOperationService::subtract($billOfMaterialdetails);
            }

            if (isset($wipDetails) && count($wipDetails)) {
                InventoryOperationService::subtract($wipDetails, 'wip');
                InventoryOperationService::add($wipDetails);
            }

            if (isset($availableDetails) && count($availableDetails)) {
                InventoryOperationService::add($availableDetails);
            }
        });

        return [true, ''];
    }

    public function addExtra($jobExtra, $user)
    {
        if (!$user->hasWarehousePermission('add', $jobExtra->job->factory_id)) {
            return [false, 'You do not have permission to add to one or more of the warehouses.'];
        }

        if ($jobExtra->isAdded()) {
            return [false, 'This Product is already added to inventory.'];
        }

        $detail = $jobExtra->only(['product_id', 'quantity']);

        $detail['warehouse_id'] = $jobExtra->job->factory_id;

        DB::transaction(function () use ($jobExtra, $detail) {
            InventoryOperationService::add($detail);

            $jobExtra->add();
        });

        return [true, ''];
    }

    public function subtractExtra($jobExtra, $user)
    {
        if (!$user->hasWarehousePermission('subtract', $jobExtra->job->factory_id)) {
            return [false, 'You do not have permission to subtract from one or more of the warehouses.'];
        }

        if ($jobExtra->isSubtracted()) {
            return [false, 'This Product is already subtracted from inventory.'];
        }

        $detail = $jobExtra->only(['product_id', 'quantity']);

        $detail['warehouse_id'] = $jobExtra->job->factory_id;

        $unavailableProducts = InventoryOperationService::unavailableProducts($detail);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($jobExtra, $detail) {
            InventoryOperationService::subtract($detail);

            $jobExtra->subtract();
        });

        return [true, ''];
    }

    private function isQuantityValid($quantity, $available, $wip)
    {
        return $quantity >= ($available + $wip);
    }
}
