<?php

namespace App\Http\BusinessLogic;

use App\Http\BusinessLogic\Methods\AmoLogicFactory;
use App\Http\BusinessLogic\Methods\BotAdministrativeLogicFactory;
use App\Http\BusinessLogic\Methods\BotDialogsLogicFactory;
use App\Http\BusinessLogic\Methods\BotLogicFactory;
use App\Http\BusinessLogic\Methods\BotPageLogicFactory;
use App\Http\BusinessLogic\Methods\BotSlugLogicFactory;
use App\Http\BusinessLogic\Methods\BotUserLogicFactory;
use App\Http\BusinessLogic\Methods\CompanyLogicFactory;
use App\Http\BusinessLogic\Methods\KeyboardLogicFactory;
use App\Http\BusinessLogic\Methods\ManagerLogicFactory;
use App\Http\BusinessLogic\Methods\ProductLogicFactory;

class BusinessLogic
{
    protected AmoLogicFactory $amo;
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

    public function __construct()
    {
        $this->amo = new AmoLogicFactory();
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
    }

    public function bots(): BotLogicFactory
    {
        return $this->bot;
    }

    public function amo(): AmoLogicFactory
    {
        return $this->amo;
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
