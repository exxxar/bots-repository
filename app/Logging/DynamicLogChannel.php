<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\LogRecord;

class DynamicLogChannel
{

    protected static $botDomain;

    public static function setGlobalBotDomain($botDomain)
    {
        self::$botDomain = $botDomain;
    }

    public function __invoke($config)
    {

        $botName = self::$botDomain ?? 'bot';


        $logPath = storage_path("logs/{$botName}.log");

        // Создаем обработчик
        $handler = new StreamHandler($logPath, Logger::DEBUG);

        $handler->setFormatter(new CustomLogFormatter());

        // Создаем логгер с этим обработчиком
        $logger = new Logger('dynamic');
        $logger->pushHandler($handler);


        return $logger;
    }


}
