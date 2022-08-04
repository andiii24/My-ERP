<?php

namespace App\Models;

use App\Traits\TouchParentUserstamp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompensationAdjustmentDetail extends Model
{
    use SoftDeletes, TouchParentUserstamp;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function compensationAdjustment()
    {
        return $this->belongsTo(CompensationAdjustment::class, 'adjustment_id');
    }

    public function parentModel()
    {
        return $this->compensationAdjustment();
    }
}
