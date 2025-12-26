<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class ProcessEmailQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:email-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process email and notification queues if enabled in settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $queueMode = Setting::get('queue_mode');

        if ($queueMode === 'enabled') {
            Log::info('Processing email and notification queues as queue_mode is enabled.');
            Artisan::call('queue:work', ['--queue' => 'emails,notifications', '--once' => true]);
        } else {
            Log::info('Skipping email and notification queue processing as queue_mode is disabled.');
        }
    }
}