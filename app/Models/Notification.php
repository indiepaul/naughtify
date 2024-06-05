<?php

namespace App\Models;

use App\Notifications\Message;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table = 'user_notifications';

    protected $guarded = [];

    public static function send($user, $message)
    {
        $user->messages()->create([
            'message' => $message
        ]);
        $user->notify(new Message($message));
    }
}
