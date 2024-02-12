<?php

namespace App\Enums;

enum InlineItemTypeEnum: int
{
    case InlineQueryResultArticle = 0;
    case InlineQueryResultAudio = 1;
    case InlineQueryResultContact = 2;
    case InlineQueryResultGame = 3;
    case InlineQueryResultDocument = 4;
    case InlineQueryResultGif = 5;
    case InlineQueryResultLocation = 6;
    case InlineQueryResultMpeg4Gif = 7;
    case InlineQueryResultPhoto = 8;
    case InlineQueryResultVenue = 9;
    case InlineQueryResultVideo = 10;
    case InlineQueryResultVoice = 11;
    case InlineQueryResultCachedAudio = 12;
    case InlineQueryResultCachedDocument = 13;
    case InlineQueryResultCachedGif = 14;
    case InlineQueryResultCachedMpeg4Gif = 15;
    case InlineQueryResultCachedPhoto = 16;
    case InlineQueryResultCachedSticker = 17;
    case InlineQueryResultCachedVideo = 18;
    case InlineQueryResultCachedVoice = 19;

}
