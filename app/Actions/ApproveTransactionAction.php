<?php

namespace App\Actions;

use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ApproveTransactionAction
{
    private function sendNotification($model, $notification, $permission)
    {
        if (! is_null($notification) && ! is_null($permission)) {
            Notification::send(
                Notifiables::byNextActionPermission($permission, $model->createdBy),
                new $notification($model)
            );
        }
    }

    public function execute($model, $notification = null, $permission = null)
    {
        if ($model->isApproved()) {
            return [false, 'This transaction is already approved.'];
        }

        DB::transaction(function () use ($model, $notification, $permission) {
            $model->approve();

            $this->sendNotification($model, $notification, $permission);
        });

        return [true, 'You have approved this transaction successfully.'];
    }
}
