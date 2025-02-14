<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = Http::asForm()->post(env('TELCO_API'), [
            'api_key' => env('TELCOM_API_KEY'),
            'password' => env('TELCOM_PASSWORD'),
            'from' => env('TELCOM_FROM'),
            'text' => $this->data['message'],
            'numbers' => '0' . urlencode($this->data['phone']),
        ]);
        Log::info($response->body());
    }
}
