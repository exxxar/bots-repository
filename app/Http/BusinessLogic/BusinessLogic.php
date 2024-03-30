<?php

namespace App\Http\BusinessLogic;

use App\Http\BusinessLogic\Methods\AmoLogicFactory;
use App\Http\BusinessLogic\Methods\AppointmentLogicFactory;
use App\Http\BusinessLogic\Methods\InlineQueryLogicFactory;
use App\Http\BusinessLogic\Methods\MailingLogicFactory;
use App\Http\BusinessLogic\Methods\PaymentLogicFactory;
use App\Http\BusinessLogic\Methods\PromoCodesLogicFactory;
use App\Http\BusinessLogic\Methods\QuizLogicFactory;
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

    public function __construct()
    {
        $this->amo = new AmoLogicFactory();
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
    }

    public function bots(): BotLogicFactory
    {
        return $this->bot;
    }

    public function mailing(): MailingLogicFactory
    {
        return $this->mailing;
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
