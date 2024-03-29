<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UniqueReferenceNum implements Rule
{
    private $tableName;

    private $excludedId;

    private $value;

    public function __construct($tableName, $excludedId = null)
    {
        $this->tableName = $tableName;

        $this->excludedId = $excludedId;
    }

    public function passes($attribute, $value)
    {
        $this->value = round($value);

        return DB::table($this->tableName)
            ->when(Schema::hasColumn($this->tableName, 'warehouse_id'), fn($q) => $q->where('warehouse_id', authUser()->warehouse_id))
            ->where('company_id', userCompany()->id)
            ->where('code', $value)
            ->when(is_numeric($this->excludedId), fn($q) => $q->where('id', '<>', $this->excludedId))
            ->when(is_countable($this->excludedId), fn($q) => $q->whereNotIn('id', $this->excludedId))
            ->doesntExist();
    }

    public function message()
    {
        return "Reference #{$this->value} has already been taken.";
    }
}
