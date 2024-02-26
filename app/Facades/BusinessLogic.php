<?php

namespace App\Facades;


use App\Http\BusinessLogic\Methods\BotMediaLogicFactory as MediaLogic;
use App\Http\BusinessLogic\Methods\BotPageLogicFactory as PageLogic;
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
use App\Http\BusinessLogic\Methods\YClientLogicFactory as YClientsLogic;
use App\Http\BusinessLogic\Methods\BotUserLogicFactory as BotUserLogic;
use App\Http\BusinessLogic\Methods\KeyboardLogicFactory as KeyboardLogic;
use App\Http\BusinessLogic\Methods\GeoLogicFactory as GeoLogic;
use App\Http\BusinessLogic\Methods\DeliveryLogicFactory as DeliveryLogic;
use App\Http\BusinessLogic\Methods\PromoCodesLogicFactory as PromoCodesLogic;
use Illuminate\Support\Facades\Facade;

/**
 * @method static BotLogic bots()
 * @method static PageLogic pages()
 * @method static SlugLogic slugs()
 * @method static ProductLogic products()
 * @method static DialogLogic dialogs()
 * @method static AdminLogic administrative()
 * @method static CompanyLogic companies()
 * @method static BotUserLogic botUsers()
 * @method static AmoLogic amo()
 * @method static AppointmentLogic appointment()
 * @method static KeyboardLogic keyboards()
 * @method static ManagerLogic manager()
 * @method static MediaLogic media()
 * @method static GeoLogic geo()
 * @method static DeliveryLogic delivery()
 * @method static YClientsLogic yClients()
 * @method static QuizLogic quiz()
 * @method static PaymentLogic payment()
 * @method static PromoCodesLogic promoCodes()
 * @see \Illuminate\Log\Logger
 */
class BusinessLogic extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'business.logic';
    }
}
