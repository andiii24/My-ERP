<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TransactionSubtracted extends Notification
{
    use Queueable;

    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => $this->transaction->pad->icon,
            'message' => 'Products in ' . str()->singular($this->transaction->pad->name) . ' #' . $this->transaction->code . ' are subtracted from inventory by ' . $this->transaction->subtractedBy->name,
            'endpoint' => '/transactions/' . $this->transaction->id,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Transaction Subtracted')
            ->icon(asset('pwa/pwa-512x512.png'))
            ->body('Products in ' . str()->singular($this->transaction->pad->name) . ' #' . $this->transaction->code . ' are subtracted from inventory by ' . $this->transaction->subtractedBy->name)
            ->badge(asset('pwa/pwa-512x512.png'))
            ->action('View', '/notifications/' . $notification->id)
            ->vibrate([500, 250, 500, 250]);
    }
}
