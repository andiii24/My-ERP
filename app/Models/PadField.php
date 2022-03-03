<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PadField extends Model
{
    use SoftDeletes;

    protected $casts = [
        'is_master_field' => 'boolean',
        'is_required' => 'boolean',
        'is_visible' => 'boolean',
        'is_printable' => 'boolean',
    ];

    public function pad()
    {
        return $this->belongsTo(Pad::class);
    }

    public function padRelation()
    {
        return $this->belongsTo(PadRelation::class);
    }

    public function isMasterField()
    {
        return $this->is_master_field;
    }

    public function isRequired()
    {
        return $this->is_required;
    }

    public function isVisible()
    {
        return $this->is_visible;
    }

    public function isPrintable()
    {
        return $this->is_printable;
    }
}
