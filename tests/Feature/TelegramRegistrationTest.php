<?php

use App\Models\Registration;
use App\Services\TelegramService;

test('canInitiateRegistration', function () {
    $spy = $this->spy(TelegramService::class);

    $response = $this->post(
        '/api/webhook/telegram',
        [
            'message' => [
                "chat" => [
                    "id" => 5446295168,
                    "first_name" => "Paul",
                    "last_name" => "Mtali",
                ],
                "text" => "/start"
            ]
        ]
    );

    $registration = Registration::firstWhere('identifier', 5446295168);
    $this->assertNotNull($registration);
    $response->assertStatus(200);
});
