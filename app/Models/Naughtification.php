<?php

namespace App\Models;

use App\Jobs\SendMail;
use App\Jobs\SendSMS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Naughtification extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notify()
    {
        if ($this->email != null || $this->email != '') {
            $dispatchData = [
                'from_email' => $this->user->email,
                'from_name' => $this->user->name,
                'mail_to' => $this->email,
                'subject' => $this->subject,
                'message' => $this->message,
            ];
            SendMail::dispatch($dispatchData);
        }
        if ($this->phone != null || $this->phone != '') {
            SendSMS::dispatch([
                'phone' => $this->phone,
                'message' => $this->message,
            ]);
        }
    }
}
