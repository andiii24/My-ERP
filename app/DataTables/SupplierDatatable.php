<?php

namespace App\DataTables;

use App\Models\Supplier;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SupplierDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('debt limit', function ($supplier) {
                return isFeatureEnabled('Debt Management')
                ? view('components.datatables.link', [
                    'url' => route('suppliers.debts.index', $supplier->id),
                    'label' => userCompany()->currency . '. ' . number_format($supplier->debt_amount_limit, 2),
                ])
                : userCompany()->currency . '. ' . number_format($supplier->debt_amount_limit, 2);
            })
            ->editColumn('registered on', fn($supplier) => $supplier->created_at->toFormattedDateString())
            ->editColumn('added by', fn($supplier) => $supplier->createdBy->name)
            ->editColumn('edited by', fn($supplier) => $supplier->updatedBy->name)
            ->editColumn('actions', function ($supplier) {
                return view('components.datatables.supplier-action', compact('supplier'));
            })
            ->addIndexColumn();
    }

    public function query(Supplier $supplier)
    {
        return $supplier->newQuery()
            ->select('suppliers.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('company_name')->title('Name'),
            Column::make('tin')->content('N/A')->title('TIN No'),
            Column::make('debt limit', 'debt_amount_limit'),
            Column::make('address')->visible(false)->content('N/A'),
            Column::make('contact_name')->content('N/A'),
            Column::make('email')->visible(false)->content('N/A'),
            Column::make('country')->visible(false)->content('N/A')->title('Country/City'),
            Column::make('registered on', 'created_at')->visible(false),
            Column::make('added by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename()
    {
        return 'Supplier_' . date('YmdHis');
    }
}
