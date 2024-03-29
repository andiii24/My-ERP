<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenderChecklist extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $attributes = [
        'status' => 'Not Started',
    ];

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function generalTenderChecklist()
    {
        return $this->belongsTo(GeneralTenderChecklist::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to')->withDefault(['name' => 'N/A']);
    }
}
