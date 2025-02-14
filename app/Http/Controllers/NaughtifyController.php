<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Naughtification;
use App\Models\Notification;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function send(Request $request)
    {
        $request->validate([
            'phone' => 'string',
            'email' => 'string',
            'subject' => 'string|required',
            'message' => 'string|required'
        ]);

        // $exists = Naughtification::where('phone', $request->phone)
        //     ->where('email', $request->email)
        //     ->where('message', $request->message)
        //     ->exists();
        // if ($exists) {
        //     //resend notification
        // }

        $naughtification = $request->user()
            ->naughtifications()
            ->create($request->all());
        $naughtification->notify();
        return response()->json(['message' => 'ok']);
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
