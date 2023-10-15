<?php

namespace App\Services\Telegram;

use App\Services\Telegram\Exceptions\TelegramApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;


class TelegramBotApi
{
    public const HOST = 'https://api.telegram.org/bot';
    public static function sendMessage(string $token, int $chatId, string $text)
    {
        $client = new Client(['base_uri' => self::HOST]);

        try {
            $response = $client->get($token . '/sendMessage', [
                'json' => [
                    'chat_id' => $chatId,
                    'text' => $text
                ]
            ]);
        }
        catch (TelegramApiException $e) {
            logger()
                ->channel('stack')
                ->error($e->getMessage());
        }
        finally {
            $response = json_decode($response->getBody());
        }
        return $response->ok;
    }
}
