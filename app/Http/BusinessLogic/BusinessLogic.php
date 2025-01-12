<?php

namespace App\Http\BusinessLogic;

use App\Http\BusinessLogic\Methods\AmoLogicFactory;
use App\Http\BusinessLogic\Methods\AppointmentLogicFactory;
use App\Http\BusinessLogic\Methods\BasketLogicFactory;
use App\Http\BusinessLogic\Methods\BitrixLogicFactory;
use App\Http\BusinessLogic\Methods\CDEKLogicFactory;
use App\Http\BusinessLogic\Methods\FrontPadLogicFactory;
use App\Http\BusinessLogic\Methods\IIKOLogicFactory;
use App\Http\BusinessLogic\Methods\InlineQueryLogicFactory;
use App\Http\BusinessLogic\Methods\MailingLogicFactory;
use App\Http\BusinessLogic\Methods\PaymentLogicFactory;
use App\Http\BusinessLogic\Methods\ProductCollectionLogicFactory;
use App\Http\BusinessLogic\Methods\PromoCodesLogicFactory;
use App\Http\BusinessLogic\Methods\QuizLogicFactory;
use App\Http\BusinessLogic\Methods\ReviewLogicFactory;
use App\Http\BusinessLogic\Methods\StatisticLogicFactory;
use App\Http\BusinessLogic\Methods\TableLogicFactory;
use App\Http\BusinessLogic\Methods\YClientLogicFactory;
use App\Http\BusinessLogic\Methods\BotAdministrativeLogicFactory;
use App\Http\BusinessLogic\Methods\BotDialogsLogicFactory;
use App\Http\BusinessLogic\Methods\BotLogicFactory;
use App\Http\BusinessLogic\Methods\BotMediaLogicFactory;
use App\Http\BusinessLogic\Methods\BotPageLogicFactory;
use App\Http\BusinessLogic\Methods\BotSlugLogicFactory;
use App\Http\BusinessLogic\Methods\BotUserLogicFactory;
use App\Http\BusinessLogic\Methods\CompanyLogicFactory;
use App\Http\BusinessLogic\Methods\DeliveryLogicFactory;
use App\Http\BusinessLogic\Methods\GeoLogicFactory;
use App\Http\BusinessLogic\Methods\KeyboardLogicFactory;
use App\Http\BusinessLogic\Methods\ManagerLogicFactory;
use App\Http\BusinessLogic\Methods\ProductLogicFactory;


class BusinessLogic
{
    protected AmoLogicFactory $amo;
    protected ProductCollectionLogicFactory $collection;
    protected TableLogicFactory $table;
    protected BitrixLogicFactory $bitrix;
    protected PaymentLogicFactory $payment;
    protected QuizLogicFactory $quiz;
    protected AppointmentLogicFactory $appointment;
    protected YClientLogicFactory $yClient;
    protected BotLogicFactory $bot;
    protected BotUserLogicFactory $botUser;
    protected BotPageLogicFactory $page;
    protected BotSlugLogicFactory $slug;
    protected ProductLogicFactory $product;
    protected BotDialogsLogicFactory $dialog;
    protected CompanyLogicFactory $company;
    protected BotAdministrativeLogicFactory $administrative;
    protected KeyboardLogicFactory $keyboard;
    protected ManagerLogicFactory $manager;
    protected BotMediaLogicFactory $media;
    protected GeoLogicFactory $geo;
    protected DeliveryLogicFactory $delivery;
    protected PromoCodesLogicFactory $promoCodes;
    protected InlineQueryLogicFactory $inlineQueries;
    protected MailingLogicFactory $mailing;
    protected FrontPadLogicFactory $frontPad;
    protected ReviewLogicFactory $review;
    protected IIKOLogicFactory $iiko;
    protected StatisticLogicFactory $stat;
    protected CDEKLogicFactory $cdek;
    protected BasketLogicFactory $basket;

    public function __construct()
    {
        $this->amo = new AmoLogicFactory();
        $this->bitrix = new BitrixLogicFactory();
        $this->payment = new PaymentLogicFactory();
        $this->quiz = new QuizLogicFactory();
        $this->appointment = new AppointmentLogicFactory();
        $this->yClient = new YClientLogicFactory();
        $this->bot = new BotLogicFactory();
        $this->botUser = new BotUserLogicFactory();
        $this->page = new BotPageLogicFactory();
        $this->slug = new BotSlugLogicFactory();
        $this->product = new ProductLogicFactory();
        $this->dialog = new BotDialogsLogicFactory();
        $this->company = new CompanyLogicFactory();
        $this->administrative = new BotAdministrativeLogicFactory();
        $this->keyboard = new KeyboardLogicFactory();
        $this->manager = new ManagerLogicFactory();
        $this->media = new BotMediaLogicFactory();
        $this->geo = new GeoLogicFactory();
        $this->delivery = new DeliveryLogicFactory();
        $this->promoCodes = new PromoCodesLogicFactory();
        $this->inlineQueries = new InlineQueryLogicFactory();
        $this->mailing = new MailingLogicFactory();
        $this->frontPad = new FrontPadLogicFactory();
        $this->review = new ReviewLogicFactory();
        $this->iiko = new IIKOLogicFactory();
        $this->collection = new ProductCollectionLogicFactory();
        $this->stat = new StatisticLogicFactory();
        $this->cdek = new CDEKLogicFactory();
        $this->basket = new BasketLogicFactory();
        $this->table = new TableLogicFactory();
    }

    public function table(): TableLogicFactory
    {
        return $this->table;
    }

    public function basket(): BasketLogicFactory
    {
        return $this->basket;
    }

    public function cdek(): CDEKLogicFactory
    {
        return $this->cdek;
    }

    public function bots(): BotLogicFactory
    {
        return $this->bot;
    }

    public function stat(): StatisticLogicFactory
    {
        return $this->stat;
    }

    public function collection(): ProductCollectionLogicFactory
    {
        return $this->collection;
    }

    public function review(): ReviewLogicFactory
    {
        return $this->review;
    }

    public function bitrix(): BitrixLogicFactory
    {
        return $this->bitrix;
    }

    public function iiko(): IIKOLogicFactory
    {
        return $this->iiko;
    }

    public function mailing(): MailingLogicFactory
    {
        return $this->mailing;
    }

    public function frontPad(): FrontPadLogicFactory
    {
        return $this->frontPad;
    }

    public function inlineQuery(): InlineQueryLogicFactory
    {
        return $this->inlineQueries;
    }

    public function quiz(): QuizLogicFactory
    {
        return $this->quiz;
    }

    public function payment(): PaymentLogicFactory
    {
        return $this->payment;
    }

    public function promoCodes(): PromoCodesLogicFactory
    {
        return $this->promoCodes;
    }

    public function yClients(): YClientLogicFactory
    {
        return $this->yClient;
    }

    public function delivery(): DeliveryLogicFactory
    {
        return $this->delivery;
    }

    public function appointment(): AppointmentLogicFactory
    {
        return $this->appointment;
    }


    public function amo(): AmoLogicFactory
    {
        return $this->amo;
    }

    public function geo(): GeoLogicFactory
    {
        return $this->geo;
    }

    public function media(): BotMediaLogicFactory
    {
        return $this->media;
    }

    public function keyboards(): KeyboardLogicFactory
    {
        return $this->keyboard;
    }

    public function botUsers(): BotUserLogicFactory
    {
        return $this->botUser;
    }

    public function companies(): CompanyLogicFactory
    {
        return $this->company;
    }

    public function pages(): BotPageLogicFactory
    {
        return $this->page;
    }

    public function slugs(): BotSlugLogicFactory
    {
        return $this->slug;
    }

    public function products(): ProductLogicFactory
    {
        return $this->product;
    }

    public function dialogs(): BotDialogsLogicFactory
    {
        return $this->dialog;
    }

    public function administrative(): BotAdministrativeLogicFactory
    {
        return $this->administrative;
    }

    public function manager(): ManagerLogicFactory
    {
        return $this->manager;
    }
}
