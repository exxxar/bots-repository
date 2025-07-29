<?php

namespace App\Facades;


use App\Http\BusinessLogic\Methods\CDEKLogicFactory as CDEKLogic;
use App\Http\BusinessLogic\Methods\BotMediaLogicFactory as MediaLogic;
use App\Http\BusinessLogic\Methods\ProductCollectionLogicFactory as CollectionLogic;
use App\Http\BusinessLogic\Methods\IIKOLogicFactory as IIKOLogic;
use App\Http\BusinessLogic\Methods\BotPageLogicFactory as PageLogic;
use App\Http\BusinessLogic\Methods\FrontPadLogicFactory as FrontPadLogic;
use App\Http\BusinessLogic\Methods\InlineQueryLogicFactory as InlineQueryLogic;
use App\Http\BusinessLogic\Methods\PaymentLogicFactory as PaymentLogic;
use App\Http\BusinessLogic\Methods\BotLogicFactory as BotLogic;
use App\Http\BusinessLogic\Methods\BotSlugLogicFactory as SlugLogic;
use App\Http\BusinessLogic\Methods\ManagerLogicFactory as ManagerLogic;
use App\Http\BusinessLogic\Methods\ProductLogicFactory as ProductLogic;
use App\Http\BusinessLogic\Methods\BotDialogsLogicFactory as DialogLogic;
use App\Http\BusinessLogic\Methods\BotAdministrativeLogicFactory as AdminLogic;
use App\Http\BusinessLogic\Methods\CompanyLogicFactory as CompanyLogic;
use App\Http\BusinessLogic\Methods\AmoLogicFactory as AmoLogic;
use App\Http\BusinessLogic\Methods\AppointmentLogicFactory as AppointmentLogic;
use App\Http\BusinessLogic\Methods\QuizLogicFactory as QuizLogic;
use App\Http\BusinessLogic\Methods\ReviewLogicFactory as ReviewLogic;
use App\Http\BusinessLogic\Methods\StatisticLogicFactory as StatisticLogic;
use App\Http\BusinessLogic\Methods\YClientLogicFactory as YClientsLogic;
use App\Http\BusinessLogic\Methods\BotUserLogicFactory as BotUserLogic;
use App\Http\BusinessLogic\Methods\KeyboardLogicFactory as KeyboardLogic;
use App\Http\BusinessLogic\Methods\GeoLogicFactory as GeoLogic;
use App\Http\BusinessLogic\Methods\DeliveryLogicFactory as DeliveryLogic;
use App\Http\BusinessLogic\Methods\PromoCodesLogicFactory as PromoCodesLogic;
use App\Http\BusinessLogic\Methods\MailingLogicFactory as QueueLogic;
use App\Http\BusinessLogic\Methods\BitrixLogicFactory as BitrixLogic;
use App\Http\BusinessLogic\Methods\BasketLogicFactory as BasketLogic;
use App\Http\BusinessLogic\Methods\TableLogicFactory as TableLogic;
use App\Http\BusinessLogic\Methods\StoryLogicFactory as StoryLogic;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Log\Logger
 */
class CoreLogic extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'core.logic';
    }
}
