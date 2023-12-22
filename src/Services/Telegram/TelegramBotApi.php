<?php

namespace Services\Telegram;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Services\Telegram\Exceptions\TelegramApiException;
use Throwable;


class TelegramBotApi implements TelegramBotApiContract
{
    public const HOST = 'https://api.telegram.org/bot';

    public static function fake(): TelegramBotApiFake
    {
        return app()->instance(
            TelegramBotApiContract::class,
            new TelegramBotApiFake()
        );
    }

    public static function sendMessage(string $token, int $chatId, string $text): bool
    {
        try {
//            $client = new Client(['base_uri' => self::HOST . $token . '/sendMessage']);
            $response = Http::get(self::HOST . $token . '/sendMessage', [
                'chat_id' => $chatId,
                'text' => $text
            ])->throw()->json();

            return $response['ok'] ?? false;

        } catch (Throwable $e) {
            report( new TelegramApiException($e->getMessage()));
//            logger()->error('Ошибка отправки сообщения в Telegram: ' . $e->getMessage());
            return false;
        }
    }
}
