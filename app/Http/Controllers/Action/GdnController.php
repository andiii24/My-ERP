<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Actions\ConvertToSivAction;
use App\Http\Controllers\Controller;
use App\Models\Credit;
use App\Models\Gdn;
use App\Models\Siv;
use App\Notifications\GdnApproved;
use App\Notifications\GdnSubtracted;
use App\Services\GdnService;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Notification;

class GdnController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Gdn Management');

        $this->middleware('isFeatureAccessible:Credit Management')->only('convertToCredit');
    }

    public function approve(Gdn $gdn, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $gdn);

        if (!auth()->user()->hasWarehousePermission('sales',
            $gdn->gdnDetails->pluck('warehouse_id')->toArray())) {
            return back()->with('failedMessage', 'You do not have permission to approve in one or more of the warehouses.');
        }

        [$isExecuted, $message] = $action->execute($gdn, GdnApproved::class, 'Subtract GDN');

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function printed(Gdn $gdn)
    {
        $this->authorize('view', $gdn);

        if (!$gdn->isApproved()) {
            return back()->with('failedMessage', 'This Delivery Order is not approved yet.');
        }

        $gdn->load(['gdnDetails.product', 'customer', 'company', 'createdBy', 'approvedBy']);

        return \PDF::loadView('gdns.print', compact('gdn'))
            ->setPaper('a4', 'portrait')
            ->stream();
    }

    public function convertToSiv(Gdn $gdn, ConvertToSivAction $action)
    {
        $this->authorize('view', $gdn);

        $this->authorize('create', Siv::class);

        if (!auth()->user()->hasWarehousePermission('siv',
            $gdn->gdnDetails->pluck('warehouse_id')->toArray())) {
            return back()->with('failedMessage', 'You do not have permission to convert to one or more of the warehouses.');
        }

        if (!$gdn->isSubtracted()) {
            return back()->with('failedMessage', 'This Delivery Order is not subtracted yet.');
        }

        if ($gdn->isClosed()) {
            return back()->with('failedMessage', 'This Delivery Order is closed.');
        }

        $siv = $action->execute(
            'DO',
            $gdn->code,
            $gdn->customer->company_name ?? '',
            $gdn->approved_by,
            $gdn->gdnDetails()->get(['product_id', 'warehouse_id', 'quantity'])->toArray(),
        );

        return redirect()->route('sivs.show', $siv->id);
    }

    public function subtract(Gdn $gdn, GdnService $gdnService)
    {
        $this->authorize('subtract', $gdn);

        [$isExecuted, $message] = $gdnService->subtract($gdn);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(notifiables('Approve GDN', $gdn->createdBy), new GdnSubtracted($gdn));

        return back();
    }

    public function close(Gdn $gdn)
    {
        $this->authorize('approve', $gdn);

        if (!auth()->user()->hasWarehousePermission('sales',
            $gdn->gdnDetails->pluck('warehouse_id')->toArray())) {
            return back()->with('failedMessage', 'You do not have permission to close in one or more of the warehouses.');
        }

        if (!$gdn->isSubtracted()) {
            return back()->with('failedMessage', 'This Delivery Order is not subtracted yet.');
        }

        if ($gdn->isClosed()) {
            return back()->with('failedMessage', 'This Delivery Order is already closed.');
        }

        $gdn->close();

        return back()->with('successMessage', 'Delivery Order closed and archived successfully.');
    }

    public function convertToCredit(Gdn $gdn, GdnService $service)
    {
        $this->authorize('create', Credit::class);

        [$isExecuted, $message] = $service->convertToCredit($gdn);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('credits.show', $gdn->credit->id);
    }
}
