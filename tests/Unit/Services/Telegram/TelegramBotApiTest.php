<?php

namespace Tests\Unit\Services\Telegram;

use Illuminate\Support\Facades\Http;
use Services\Telegram\TelegramBotApi;
use Services\Telegram\TelegramBotApiContract;
use Tests\TestCase;

class TelegramBotApiTest extends TestCase
{
    /** @test **/
    public function it_send_message_success_by_http_fake(): void
    {
        Http::fake([
            TelegramBotApi::HOST . '*' => Http::response(['ok' => true])
        ]);

        $result = TelegramBotApi::sendMessage('', 1, 'test');

        $this->assertTrue($result);
    }
    /** @test **/
    public function it_send_message_fail_by_http_fake(): void
    {
        Http::fake([
            TelegramBotApi::HOST . '*' => Http::response(['ok' => false])
        ]);

        $result = TelegramBotApi::sendMessage('', 1, 'test');

        $this->assertFalse($result);
    }
    /** @test **/
    public function it_send_message_success_by_fake_instance(): void
    {
        TelegramBotApi::fake()
            ->returnTrue();

        $result = app(TelegramBotApiContract::class)::sendMessage('', 1, 'test');

        $this->assertTrue($result);
    }
    /** @test **/
    public function it_send_message_fail_by_fake_instance(): void
    {
        TelegramBotApi::fake()
            ->returnFalse();

        $result = app(TelegramBotApiContract::class)::sendMessage('', 1, 'test');

        $this->assertFalse($result);
    }
}
