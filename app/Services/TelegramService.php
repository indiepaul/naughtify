<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\User;
use App\Notifications\Message;
use Illuminate\Support\Facades\Notification;

class TelegramService
{
    public static function createWebhook()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN') . '/setWebhook', [
            'form_params' => [
                'url' => env('WEBHOOK_URL') . '/api/webhook/telegram',
            ]
        ]);

        return $response->getBody()->getContents();
    }

    public static function getWebhookInfo()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN') . '/getWebhookInfo');
        return $response->getBody()->getContents();
    }

    public static function deleteWebhook()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN') . '/deleteWebhook');
        return $response->getBody()->getContents();
    }
    public static function initiateRegistration($message)
    {
        Registration::create([
            'identifier' => $message->chat->id,
            'name' => $message->chat->first_name . ' ' . $message->chat->last_name
        ]);
        return "What is your registered email address?";
    }

    public static function registerEmail($message)
    {
        $registration = Registration::firstWhere('identifier', $message->chat->id);
        if (!filter_var($message->text, FILTER_VALIDATE_EMAIL)) {
            return "Please enter a valid email address";
        }

        if (!User::where('email', $message->text)->exists()) {
            return "No user found with this email address";
        }

        $registration->update(['email' => $message->text]);

        $user = User::where('email', $message->text)->first();
        $user->channels()->create([
            'name' => 'Telegram',
            'identifier' => $message->chat->id
        ]);

        return "Registration Complete. Thank you!";
    }

    public static function sendMessage($message, $chatId)
    {
        if (env('APP_ENV') !== 'testing') {
            Notification::route('telegram', $chatId)
                ->notify(new Message($message));
        }
    }
}
