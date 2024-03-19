<?php

namespace App\Classes;

use App\Enums\InlineItemTypeEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use function PHPUnit\Framework\objectEquals;

trait InlineQueryMethodsTrait
{

    public function getInlineQueryItem(object $item): mixed
    {
        return match (InlineItemTypeEnum::from($item->type)) {
            InlineItemTypeEnum::InlineQueryResultArticle => $this->InlineQueryResultArticle($item),
            InlineItemTypeEnum::InlineQueryResultCachedPhoto => $this->InlineQueryResultCachedPhoto($item),
            default => null,
        };
    }

    private function InlineQueryResultArticle(object $item): object
    {
        $config = $item->custom_settings ?? [];


        $tmp = [
            "type" => "article",
            "id" => Str::uuid(),
            "title" => $item->title ?? null,
            "input_message_content" => [
                "message_text" => $item->input_message_content
            ],
            "parse_mode" => "HTML",
            "hide_url" => $config["hide_url"] ?? true,
            "description" => $item->description ?? null,

        ];

        return $this->extracted($item, $tmp, $config);
    }

    private function InlineQueryResultCachedPhoto(object $item): object
    {
        $config = $item->custom_settings ?? [];

        $tmp = [
            "type" => "photo",
            "id" => Str::uuid(),
            "photo_file_id" => $config["photo_file_id"] ?? null,
            "caption" => $config["caption"] ?? null,
            "title" => $item->title ?? null,
            "parse_mode" => "HTML",
            "input_message_content" => [
                "message_text" => $item->input_message_content
            ],

        ];

        return $this->extracted($item, $tmp, $config);
    }

    /**
     * @param object $item
     * @param array $tmp
     * @param array $config
     * @return object
     */
    private function extracted(object $item, array $tmp, array $config): object
    {
        if (!is_null($item->inline_keyboard ?? null))
            $tmp["reply_markup"] = [
                'inline_keyboard' => !is_null($item->inline_keyboard ?? null) ? $item->inline_keyboard->menu : []
            ];

        foreach ($config as $c) {
            $c = (object)$c;
            $tmp[$c->key] = $c->value;
        }

        return (object)$tmp;
    }
}
