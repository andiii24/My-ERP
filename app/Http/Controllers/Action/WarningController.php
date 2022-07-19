<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Warning;
use App\Notifications\WarningApproved;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class WarningController extends Controller
{
    public function approve(Warning $warning, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $warning);

        [$isExecuted, $message] = $action->execute($warning, WarningApproved::class);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Warning', $warning->createdBy)->push($warning->employee->user),
            new WarningApproved($warning)
        );

        return back()->with('successMessage', $message);
    }
}
