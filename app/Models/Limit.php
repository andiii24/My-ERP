<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Limit extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function companies()
    {
        return $this->morphedByMany(Company::class, 'limitable')->withPivot('amount');
    }

    public function plans()
    {
        return $this->morphedByMany(Plan::class, 'limitable')->withPivot('amount');
    }

    public function isLimitReached($limitName, $currentAmount)
    {
        $limit = $this->where('name', $limitName)->first();

        $limitByCompany = $limit->companies()->wherePivot('limitable_id', userCompany()->id)->first();

        $limitByPlan = $limit->plans()->wherePivot('limitable_id', userCompany()->plan_id)->first();

        if ($limitByPlan) {
            $allowedAmount = $limitByPlan->pivot->amount;
        }

        if ($limitByCompany) {
            $allowedAmount = $limitByCompany->pivot->amount;
        }

        if ($currentAmount >= $allowedAmount) {
            return true;
        }

        return false;
    }
}
