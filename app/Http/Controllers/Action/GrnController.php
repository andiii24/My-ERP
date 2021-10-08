<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Grn;
use App\Notifications\GrnApproved;
use App\Services\GrnService;

class GrnController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Grn Management');
    }

    public function approve(Grn $grn, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $grn);

        [$isExecuted, $message] = $action->execute($grn, GrnApproved::class, 'Add GRN');

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function add(Grn $grn, GrnService $grnService)
    {
        $this->authorize('add', $grn);

        [$isExecuted, $message] = $grnService->add($grn);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back();
    }
}
