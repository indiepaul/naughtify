<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class SetWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Webhook URL: https://".env('WEBHOOK_URL')."/telegram-webhook");
        // TODO: check if webhooks is registered
        // TelegramService::getWebhookInfo();
        // TODO: if registered delete 
        TelegramService::deleteWebhook();
        $response = TelegramService::createWebhook();

        $this->info($response);

    }
}
