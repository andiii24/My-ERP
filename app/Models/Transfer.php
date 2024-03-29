<?php

namespace App\Models;

use App\Scopes\TransferScope;
use App\Traits\Addable;
use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\Closable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\Subtractable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transfer extends Model
{
    use MultiTenancy, Branchable, SoftDeletes, Approvable, HasUserstamps, Addable, Subtractable, Closable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new TransferScope);
    }

    public function transferDetails()
    {
        return $this->hasMany(TransferDetail::class);
    }

    public function transferredFrom()
    {
        return $this->belongsTo(Warehouse::class, 'transferred_from');
    }

    public function transferredTo()
    {
        return $this->belongsTo(Warehouse::class, 'transferred_to');
    }

    public static function withBranchScope()
    {
        return false;
    }
}
