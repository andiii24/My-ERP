<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;

class DeleteWeekOldNotifications extends Command
{
    protected $signature = 'notifications:delete-week';

    protected $description = 'Delete notifications that were created week ago or more';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(DatabaseNotification $databaseNotification)
    {
        $databaseNotification->where('created_at', '<=', now()->subDays(7))->delete();

        return 0;
    }
}
