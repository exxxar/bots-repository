<?php

namespace App\Classes;

use App\Enums\InlineItemTypeEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use function PHPUnit\Framework\objectEquals;

trait InlineQueryMethodsTrait
{

    public function getInlineQueryItem(object $item): mixed
    {
        Log::info("type=" . $item->type . "-" . print_r(InlineItemTypeEnum::InlineQueryResultArticle, true));

        return match (InlineItemTypeEnum::from($item->type)) {
            InlineItemTypeEnum::InlineQueryResultArticle => $this->InlineQueryResultArticle($item),
            InlineItemTypeEnum::InlineQueryResultCachedPhoto => $this->InlineQueryResultCachedPhoto($item),
            default => null,
        };
    }

    private function InlineQueryResultArticle(object $item): object
    {
        Log::info("we are here");
        $config = $item->custom_settings ?? [];


        $tmp = [
            "type" => "article",
            "id" => Str::uuid(),
            "title" => $item->title ?? null,
            "input_message_content" => [
                "message_text" => $item->input_message_content
            ],
            "parse_mode" => "HTML",
        /*    "url" => $config["url"] ?? null,*/
            "hide_url" => $config["hide_url"] ?? true,
            "description" => $item->description ?? null,
          /*  "thumbnail_url" => $config["thumbnail_url"] ?? null,
            "thumbnail_width" => $config["thumbnail_width"] ?? null,
            "thumbnail_height" => $config["thumbnail_height"] ?? null,*/
        ];

        if (!is_null($item->inline_keyboard))
            $tmp["reply_markup"] = [
                'inline_keyboard' => $item->inline_keyboard ?? []
            ];

        return (object)$tmp;
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

        if (!is_null($item->inline_keyboard))
            $tmp["reply_markup"] = [
                'inline_keyboard' => $item->inline_keyboard ?? []
            ];

        return (object)$tmp;
    }
}
