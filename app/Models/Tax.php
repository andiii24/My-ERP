<?php

namespace App\Models;

use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use MultiTenancy;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function isVat()
    {
        return $this->type == 'VAT';
    }
}
