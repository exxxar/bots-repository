<?php

namespace App\Classes;

use App\Enums\InlineItemTypeEnum;
use Illuminate\Support\Str;

trait InlineQueryMethodsTrait
{

    public function getInlineQueryItem(object $item): mixed
    {
        switch ($item->type) {
            case InlineItemTypeEnum::InlineQueryResultArticle:
                return $this->InlineQueryResultArticle($item);
            case InlineItemTypeEnum::InlineQueryResultCachedPhoto:
                return $this->InlineQueryResultCachedPhoto($item);
        }
    }

    private function InlineQueryResultArticle(object $item)
    {
        return (object)[
            "type" => "article",
            "id" => Str::uuid(),
            "title" => $item->title??null,
            "input_message_content" => $item->input_message_content,
            "reply_markup" => null,
            "url" => null,
            "hide_url" => null,
            "description" => null,
            "thumbnail_url" => null,
            "thumbnail_width" => null,
            "thumbnail_height" => null,
        ];
    }

    private function InlineQueryResultCachedPhoto(object $item)
    {

    }
}
