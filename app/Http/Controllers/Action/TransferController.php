<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Actions\ConvertToSivAction;
use App\Http\Controllers\Controller;
use App\Models\Siv;
use App\Models\Transfer;
use App\Notifications\TransferApproved;
use App\Services\TransferService;

class TransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Transfer Management');
    }

    public function approve(Transfer $transfer, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $transfer);

        [$isExecuted, $message] = $action->execute($transfer, TransferApproved::class, 'Make Transfer');

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function subtract(Transfer $transfer, TransferService $transferService)
    {
        $this->authorize('transfer', $transfer);

        [$isExecuted, $message] = $transferService->subtract($transfer);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back();
    }

    public function add(Transfer $transfer, TransferService $transferService)
    {
        $this->authorize('transfer', $transfer);

        [$isExecuted, $message] = $transferService->add($transfer);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back();
    }

    public function convertToSiv(Transfer $transfer, ConvertToSivAction $action)
    {
        $this->authorize('view', $transfer);

        $this->authorize('create', Siv::class);

        $transferDetails = $transfer->transferDetails()->get(['product_id', 'quantity'])->toArray();

        data_fill($transferDetails, '*.warehouse_id', $transfer->transferred_from);

        $siv = $action->execute(
            'Transfer',
            $transfer->code,
            null,
            $transfer->approved_by,
            $transferDetails,
        );

        return redirect()->route('sivs.show', $siv->id);
    }
}
