<?php

namespace App\Http\BusinessLogic\Methods\Classes;

use App\Http\BusinessLogic\Methods\BaseLogicFactory;
use App\Models\BotMenuSlug;
use App\Models\BotPage;
use App\Models\BotWarning;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BotConstructor extends BaseLogicFactory
{

    private int $currentType = 0;
    private object|null $menu = null;
    private array|null $commands = null;
    private array|null $scripts = null;
    private array|null $pages = null;
    private array|null $warnings = null;

    private array $links = [];


    public function setBotType($type = 0): static
    {
        $this->currentType = 0;
        return $this;
    }

    private function checkAndGetLinkedId($key)
    {

    }

    private function loadJsonConfig(): void
    {

        $jsonString = file_get_contents(base_path() . 'botTypes.json');
        $data = json_decode($jsonString, true);

        if (!$data) {
            throw new HttpException(404, "Файл конфигурации ботов не найден!");
        }


        $entry = Collection::make($data)
            ->where("id", $this->currentType)
            ->first();

        if (!is_null($entry)) {
            $this->pages = $entry["pages"] ?? null;
            $this->menu = $entry["menu"] ?? null;
            $this->commands = $entry["commands"] ?? null;
            $this->scripts = $entry["scripts"] ?? null;
            $this->warnings = $entry["warnings"] ?? null;
        }


    }

    private function menuHandler()
    {
        if (is_null($this->menu))
            return;

        $this->bot->menu = [
            "text" => $this->menu->title ?? "Меню",
            "url" => $this->menu->url ?? null,
        ];

        $this->bot->menu->save();

    }

    private function commandsHandler()
    {
        if (is_null($this->commands))
            return;

        $this->bot->commands = $this->commands ?? [];
        $this->bot->save();
    }

    private function warningHandler()
    {
        if (is_null($this->warnings))
            return;

        foreach ($this->warnings as $warn)
            BotWarning::query()->create([
                'bot_id' => $this->bot->id,
                'rule_key' => $warn->rule_key ?? null,
                'rule_value' => $warn->rule_value ?? null,
                'is_active' => true
            ]);

    }

    private function pagesHandler()
    {
        if (is_null($this->pages))
            return;

        if (isset($pages->data))
            foreach ($pages->data as $page) {
                $page = (object)$page;

                $tmpSlug = BotMenuSlug::query()->find($page->bot_menu_slug_id);

                if (!is_null($tmpSlug)) {
                    $tmpSlug = $tmpSlug->replicate();
                    $tmpSlug->bot_id = $bot->id;
                    $tmpSlug->save();

                    BotPage::query()->create([
                        'bot_menu_slug_id' => $tmpSlug->id,
                        'content' => $page->content,
                        'images' => $page->images,
                        'reply_keyboard_id' => $page->reply_keyboard_id,
                        'inline_keyboard_id' => $page->inline_keyboard_id,
                        'bot_id' => $bot->id,
                    ]);
                }
            }

    }

    private function scriptsHandler()
    {
        if (is_null($this->scripts))
            return;

        foreach ($this->scripts as $slug)
            BotMenuSlug::query()->create([
                'bot_id' => $this->bot->id,
                'command' => $slug->command,
                'comment' => $slug->comment,
                'slug' => $slug->slug,
                'is_global' => $slug->is_global ?? false,
                'config' => $slug->config ?? null,
            ]);

    }

    public function handler(): void
    {
        $this->loadJsonConfig();
        $this->commandsHandler();
        $this->warningHandler();
        $this->menuHandler();
        $this->scriptsHandler();
        $this->pagesHandler();
    }

}
