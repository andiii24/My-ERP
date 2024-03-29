<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\PurchaseDatatable;
use App\DataTables\PurchaseDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Notifications\PurchasePrepared;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Purchase Management');

        $this->authorizeResource(Purchase::class, 'purchase');
    }

    public function index(PurchaseDatatable $datatable)
    {
        $datatable->builder()->setTableId('purchases-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalPurchases = Purchase::count();

        $totalPurchased = Purchase::purchased()->count();

        $totalApproved = Purchase::approved()->notPurchased()->count();

        $totalNotApproved = Purchase::notApproved()->count();

        return $datatable->render('purchases.index', compact('totalPurchases', 'totalPurchased', 'totalApproved', 'totalNotApproved'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        $currentPurchaseNo = nextReferenceNumber('purchases');

        return view('purchases.create', compact('suppliers', 'currentPurchaseNo'));
    }

    public function store(StorePurchaseRequest $request)
    {
        $purchase = DB::transaction(function () use ($request) {
            $purchase = Purchase::create($request->safe()->except('purchase'));

            $purchase->purchaseDetails()->createMany($request->validated('purchase'));

            Notification::send(Notifiables::byNextActionPermission('Approve Purchase'), new PurchasePrepared($purchase));

            return $purchase;
        });

        return redirect()->route('purchases.show', $purchase->id);
    }

    public function show(Purchase $purchase, PurchaseDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('purchase-details-datatable');

        $purchase->load(['purchaseDetails.purchase', 'supplier', 'contact', 'grns']);

        return $datatable->render('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        if ($purchase->isApproved()) {
            return back()->with('failedMessage', 'You can not edit an approved purchase.');
        }

        $purchase->load('purchaseDetails.product');

        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        return view('purchases.edit', compact('purchase', 'suppliers'));
    }

    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        if ($purchase->isApproved()) {
            return redirect()->route('purchases.show', $purchase->id)->with('failedMessage', 'You can not edit an approved purchase.');
        }

        DB::transaction(function () use ($request, $purchase) {
            $purchase->update($request->safe()->except('purchase'));

            $purchase->purchaseDetails()->forceDelete();

            $purchase->purchaseDetails()->createMany($request->validated('purchase'));
        });

        return redirect()->route('purchases.show', $purchase->id);
    }

    public function destroy(Purchase $purchase)
    {
        if ($purchase->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an approved purchase.');
        }

        $purchase->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
