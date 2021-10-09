<?php

namespace App\Traits;

use App\Models\Warehouse;
use App\Scopes\BranchScope;

trait Branchable
{
    protected static function bootBranchable()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->warehouse_id = auth()->user()->warehouse_id;
            }
        });

        static::addGlobalScope(new BranchScope);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
