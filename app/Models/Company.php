<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name', 'sector', 'membership_plan', 'currency', 'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
