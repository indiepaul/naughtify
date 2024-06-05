<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Notification;
use App\Models\Registration;
use App\Models\User;
use App\Notifications\Message;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Telegram\TelegramUpdates;

class NaughtifyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Notification::send($request->user(), $request->message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        Log::info(json_encode($request->all()));
        $message = json_decode(json_encode($request->message));
        if (!Channel::where('identifier', $message->chat->id)->exists()) {
            if ($request->message['text'] == '/start') {
                $response = TelegramService::initiateRegistration($message);
                TelegramService::sendMessage($response, $message->chat->id);
                return;
            }
            $response = TelegramService::registerEmail($message);
            TelegramService::sendMessage($response, $message->chat->id);
            return;
        }
        return;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
