<?php

namespace App\Jobs;

use App\Mail\NaughtificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
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
        Mail::to($this->data['mail_to'])
            ->send(new NaughtificationMail([
                'from_email' => $this->data['from_email'],
                'from_name' => $this->data['from_name'],
                'subject' => $this->data['subject'],
                'message' => $this->data['message'],
            ]));
    }
}
