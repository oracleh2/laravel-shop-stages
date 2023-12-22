<?php

namespace Services\Telegram\Exceptions;

use Exception;
use GuzzleHttp\Exception\GuzzleException;

class TelegramApiException extends Exception implements GuzzleException
{

}
