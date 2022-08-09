<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements WithHeadingRow, ToModel, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $products;

    private $productCategories;

    public function __construct()
    {
        $this->products = Product::all();

        $this->productCategories = ProductCategory::all();
    }

    public function model(array $row)
    {
        $productName = $row['product_name'];
        $code = $row['product_code'] ?? null;
        $productCategory = $this->productCategories->firstWhere('name', $row['product_category_name']);

        if ($this->products->where('name', $productName)->where('code', $code)->where('product_category_id', $productCategory->id)->count()) {
            return null;
        }

        $product = new Product([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'product_category_id' => $productCategory->id,
            'name' => $row['product_name'],
            'code' => $row['product_code'] ?? null,
            'type' => str()->title($row['product_type']),
            'unit_of_measurement' => str()->title($row['product_unit_of_measurement']),
            'min_on_hand' => $row['product_min_on_hand'] ?? 0.00,
        ]);

        $this->products->push($product);

        return $product;
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255'],
            'product_type' => ['required', 'string', 'max:255', Rule::when(userCompany()->plan->isPremium(), Rule::in(['Finished Goods', 'Raw Material']), Rule::in(['Finished Goods']))],
            'product_code' => ['nullable', 'string', 'max:255'],
            'product_unit_of_measurement' => ['required', 'string', 'max:255'],
            'product_min_on_hand' => ['nullable', 'numeric'],
            'product_category_name' => ['required', 'string', 'max:255', Rule::in($this->productCategories->pluck('name'))],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['product_category_name'] = str()->squish($data['product_category_name']);
        $data['product_name'] = str()->squish($data['product_name']);
        $data['product_code'] = str()->squish($data['product_code']);

        return $data;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }
}
