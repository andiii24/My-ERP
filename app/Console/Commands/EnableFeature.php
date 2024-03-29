<?php

namespace App\Console\Commands;

use App\Models\Feature;
use Illuminate\Console\Command;

class EnableFeature extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feature:enable {featureName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable feature';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            Feature::enableOrDisable($this->argument('featureName'), 1);

            $this->info('Feature is enabled globally.');
        } catch (\Exception $ex) {
            $this->error('Feature name is wrong.');
        }

        return 0;
    }
}
