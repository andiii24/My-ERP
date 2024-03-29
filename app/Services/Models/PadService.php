<?php

namespace App\Services\Models;

use App\Models\Pad;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PadService
{
    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $pad = Pad::create(Arr::except($data, ['field']));

            if (isset($data['status'])) {
                $pad->padStatuses()->createMany($data['status']);
            }

            collect($data['field'])
                ->each(function ($field) use ($pad) {
                    $padField = $pad->padFields()->create($field);

                    if ($field['is_relational_field']) {
                        $padField->padRelation()->create(Arr::only($field, ['relationship_type', 'model_name', 'representative_column', 'component_name']));
                    }
                });

            $pad->padPermissions()->createMany($this->generatePermissions($pad));

            if ($data['has_prices']) {
                $pad->padFields()->createMany($this->generatePriceFields());
            }

            if ($data['has_payment_term']) {
                $pad->padFields()->createMany($this->generatePaymentTermFields());
            }

            return $pad;
        });
    }

    public function update($pad, $data)
    {
        return DB::transaction(function () use ($pad, $data) {
            $pad->update(Arr::except($data, ['field']));

            $padFields = $pad->padFields()
                ->whereNotIn('label', $this->generatePriceFields()->pluck('label')->merge($this->generatePaymentTermFields()->pluck('label')))
                ->get();

            if (isset($data['status'])) {
                $pad->padStatuses()->forceDelete();
                $pad->padStatuses()->createMany($data['status']);
            }

            collect($data['field'])
                ->each(function ($field, $i) use ($padFields) {
                    $padFields[$i]->update($field);

                    if ($field['is_relational_field']) {
                        $padFields[$i]->padRelation->update(
                            Arr::only($field, ['relationship_type', 'model_name', 'representative_column', 'component_name'])
                        );
                    }
                });

            $pad->padPermissions()->whereNotIn('name', $this->generatePermissions($pad)->pluck('name'))->forceDelete();

            $this->generatePermissions($pad)->each(function ($permission) use ($pad) {
                $pad->padPermissions()->firstOrCreate($permission);
            });

            $pad->padFields()->detailFields()->when(
                $data['has_prices'],
                fn($q) => $q->whereIn('label', $this->generatePriceFields()->pluck('label'))->exists()
                ?: $pad->padFields()->createMany($this->generatePriceFields()),
                fn($q) => $q->whereIn('label', $this->generatePriceFields()->pluck('label'))->forceDelete()
            );

            $pad->padFields()->masterFields()->when(
                $data['has_payment_term'],
                fn($q) => $q->whereIn('label', $this->generatePaymentTermFields()->pluck('label'))->exists()
                ?: $pad->padFields()->createMany($this->generatePaymentTermFields()),
                fn($q) => $q->whereIn('label', $this->generatePaymentTermFields()->pluck('label'))->forceDelete()
            );

            return $pad;
        });
    }

    public function generatePermissions($pad)
    {
        $permissions = [
            ['name' => 'Create'],
            ['name' => 'Read'],
            ['name' => 'Update'],
            ['name' => 'Delete'],
        ];

        if ($pad->isApprovable()) {
            $permissions[] = ['name' => 'Approve'];
        }

        if ($pad->isConvertable()) {
            $permissions[] = ['name' => 'Convert'];
        }

        if (!$pad->isInventoryOperationNone()) {
            $permissions[] = ['name' => ucfirst($pad->getInventoryOperationType())];
        }

        return collect($permissions);
    }

    public function generatePriceFields()
    {
        return collect([
            [
                'label' => 'Quantity',
                'icon' => 'fas fa-balance-scale',
                'is_master_field' => 0,
                'is_required' => 1,
                'is_visible' => 1,
                'is_printable' => 1,
                'tag' => 'input',
                'tag_type' => 'number',
            ],
            [
                'label' => 'Unit Price',
                'icon' => 'fas fa-dollar-sign',
                'is_master_field' => 0,
                'is_required' => 1,
                'is_visible' => 1,
                'is_printable' => 1,
                'tag' => 'input',
                'tag_type' => 'number',
            ],
            [
                'label' => 'Discount',
                'icon' => 'fas fa-percent',
                'is_master_field' => 0,
                'is_required' => 0,
                'is_visible' => 1,
                'is_printable' => 1,
                'tag' => 'input',
                'tag_type' => 'number',
            ],
        ]);
    }

    public function generatePaymentTermFields()
    {
        return collect([
            [
                'label' => 'Discount',
                'icon' => 'fas fa-percent',
                'is_master_field' => 1,
                'is_required' => 0,
                'is_visible' => 0,
                'is_printable' => 1,
                'tag' => 'input',
                'tag_type' => 'number',
            ],
            [
                'label' => 'Payment Method',
                'icon' => 'fas fa-credit-card',
                'is_master_field' => 1,
                'is_required' => 1,
                'is_visible' => 1,
                'is_printable' => 1,
                'tag' => 'select',
                'tag_type' => '',
            ],
            [
                'label' => 'Cash Received',
                'icon' => 'fas fa-money-bill',
                'is_master_field' => 1,
                'is_required' => 1,
                'is_visible' => 0,
                'is_printable' => 1,
                'tag' => 'input',
                'tag_type' => 'number',
            ],
            [
                'label' => 'Credit Due Date',
                'icon' => 'fas fa-calendar',
                'is_master_field' => 1,
                'is_required' => 0,
                'is_visible' => 0,
                'is_printable' => 1,
                'tag' => 'input',
                'tag_type' => 'date',
            ],
        ]);
    }
}
