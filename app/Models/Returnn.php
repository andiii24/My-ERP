<?php

namespace App\Models;

use App\Traits\Addable;
use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Returnn extends Model
{
    use MultiTenancy, HasFactory, SoftDeletes, Approvable, PricingTicket, HasUserstamps, Branchable, Addable;

    protected $table = "returns";

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class, 'return_id');
    }

    public function details()
    {
        return $this->returnDetails;
    }

    public function getAll()
    {
        if (auth()->user()->hasRole('System Manager') || auth()->user()->hasRole('Analyst')) {
            return $this->latest()->get();
        }

        return $this->byBranch()->latest()->get();
    }
}
