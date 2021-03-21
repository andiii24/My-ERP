<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GdnPrepared extends Notification
{
    use Queueable;

    public function __construct($gdn)
    {
        $this->gdn = $gdn;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Approval request for DO/GDN prepared by ' . ucfirst($this->gdn->createdBy->name),
            'endpoint' => '/gdn/' . $this->gdn->id,
        ];
    }
}
