<?php

namespace App\Logging;

use Illuminate\Log\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FilterHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

class FilterInfoOnly
{
    public function __invoke(Logger $logger)
    {
        $maxLevel = Level::Info;

        $handler = new StreamHandler(storage_path('logs/info.log'), Level::Debug);

        // Создаем форматтер без [] [] в конце
        $output = "[%datetime%] %level_name%: %message%\n";
        $formatter = new LineFormatter($output, null, true, true);

        $handler->setFormatter($formatter);

        $filterHandler = new FilterHandler($handler, Level::Debug, $maxLevel);

        $logger->setHandlers([$filterHandler]);
    }
}
