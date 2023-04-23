<?php

namespace App\Classes;

trait TextTrait
{
    protected $content = [];

    protected function loadContent() :void{
        $this->content = \App\Facades\BotMethods::allText()
            ->toArray();
    }

    protected function text($key, ...$params): string{
        return "";
    }
}
