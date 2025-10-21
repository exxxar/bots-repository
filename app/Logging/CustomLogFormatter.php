<?php

namespace App\Logging;

use Monolog\Formatter\FormatterInterface;
use Monolog\LogRecord;

class CustomLogFormatter implements FormatterInterface
{
    public function format(LogRecord $record): string
    {
        // Форматируем лог как строку
        return sprintf(
            "[%s] %s.%s: %s\n",
            $record->datetime->format('Y-m-d H:i:s'),
            $record->channel,
            $record->level->name,
            $record->message
        );
    }

    public function formatBatch(array $records): string
    {
        $output = '';
        foreach ($records as $record) {
            $output .= $this->format($record);
        }

        return $output;
    }
}
