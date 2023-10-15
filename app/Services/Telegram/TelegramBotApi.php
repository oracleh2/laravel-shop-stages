<?php

namespace App\Services\Telegram;

use App\Services\Telegram\Exceptions\TelegramApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class TelegramBotApi
{
    public const HOST = 'https://api.telegram.org/bot';

    public static function sendMessage(string $token, int $chatId, string $text): bool
    {
        try {
            $client = new Client(['base_uri' => self::HOST . $token . '/sendMessage']);
            $response = $client->get('', [
                'json' => [
                    'chat_id' => $chatId,
                    'text' => $text
                ]
            ]);

            $responseBody = json_decode($response->getBody(), true);

            return isset($responseBody['ok']) && $responseBody['ok'] === true;

        } catch (TelegramApiException $e) {
            logger()->error('Ошибка отправки сообщения в Telegram: ' . $e->getMessage());
            return false;
        }
    }
}
