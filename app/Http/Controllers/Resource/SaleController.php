<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\SaleDatatable;
use App\DataTables\SaleDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Sale Management');

        $this->authorizeResource(Sale::class, 'sale');
    }

    public function index(SaleDatatable $datatable)
    {
        $datatable->builder()->setTableId('sales-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalSales = Sale::count();

        $totalNotApproved = Sale::notApproved()->notCancelled()->count();

        $totalApproved = Sale::approved()->notCancelled()->count();

        $totalCancelled = Sale::cancelled()->count();

        return $datatable->render('sales.index', compact('totalSales', 'totalNotApproved', 'totalApproved', 'totalCancelled'));
    }

    public function create()
    {
        $currentInvoiceNo = nextReferenceNumber('sales');

        return view('sales.create', compact('currentInvoiceNo'));
    }

    public function store(StoreSaleRequest $request)
    {
        $sale = DB::transaction(function () use ($request) {
            $sale = Sale::create($request->safe()->except('sale'));

            $sale->saleDetails()->createMany($request->validated('sale'));

            return $sale;
        });

        return redirect()->route('sales.show', $sale->id);
    }

    public function show(Sale $sale, SaleDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('sale-details-datatable');

        $sale->load(['saleDetails.product', 'gdns', 'customer', 'contact']);

        return $datatable->render('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        if ($sale->isApproved() || $sale->isCancelled()) {
            return back()->with('failedMessage', 'Invoices that are approved/cancelled can not be edited.');
        }

        $sale->load('saleDetails.product');

        return view('sales.edit', compact('sale'));
    }

    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        if ($sale->isApproved() || $sale->isCancelled()) {
            return back()->with('failedMessage', 'Invoices that are approved/cancelled can not be edited.');
        }

        DB::transaction(function () use ($request, $sale) {
            $sale->update($request->safe()->except('sale'));

            $sale->saleDetails()->forceDelete();

            $sale->saleDetails()->createMany($request->validated('sale'));
        });

        return redirect()->route('sales.show', $sale->id);
    }

    public function destroy(Sale $sale)
    {
        if ($sale->isApproved() || $sale->isCancelled()) {
            return back()->with('failedMessage', 'Invoices that are approved/cancelled can not be deleted.');
        }

        $sale->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
