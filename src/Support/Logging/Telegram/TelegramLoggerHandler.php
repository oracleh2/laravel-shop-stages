<?php

namespace Support\Logging\Telegram;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use Services\Telegram\TelegramBotApi;

class TelegramLoggerHandler extends AbstractProcessingHandler
{
    protected int $chatId;
    protected string $token;
    public function __construct(
         array $config,
    )
    {
        $this->chatId = $config['chat_id'];
        $this->token = $config['token'];
        $level = Logger::toMonologLevel($config['level']);
        parent::__construct($level, true);
    }

    protected function write(LogRecord $record): void
    {
        TelegramBotApi::sendMessage(
            $this->token,
            $this->chatId,
            $record['formatted'],
        );
    }
}
