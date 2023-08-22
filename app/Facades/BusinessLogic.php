<?php

namespace App\Facades;


use App\Http\BusinessLogic\Methods\BotPageLogicFactory as PageLogic;
use App\Http\BusinessLogic\Methods\BotLogicFactory as BotLogic;
use App\Http\BusinessLogic\Methods\BotSlugLogicFactory as SlugLogic;
use App\Http\BusinessLogic\Methods\ProductLogicFactory as ProductLogic;
use App\Http\BusinessLogic\Methods\BotDialogsLogicFactory as DialogLogic;
use App\Http\BusinessLogic\Methods\BotAdministrativeLogicFactory as AdminLogic;
use App\Http\BusinessLogic\Methods\CompanyLogicFactory as CompanyLogic;
use App\Http\BusinessLogic\Methods\AmoLogicFactory as AmoLogic;
use App\Http\BusinessLogic\Methods\BotUserLogicFactory as BotUserLogic;
use App\Http\BusinessLogic\Methods\KeyboardLogicFactory as KeyboardLogic;
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
 * @method static KeyboardLogic keyboards()
 * @see \Illuminate\Log\Logger
 */
class BusinessLogic extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'business.logic';
    }
}
