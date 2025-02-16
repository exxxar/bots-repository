<?php

namespace App\Http\BusinessLogic\Methods\Classes;

use App\Http\BusinessLogic\Methods\BaseLogicFactory;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotPage;
use App\Models\BotWarning;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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

        if (!is_null($this->menu->linked ?? null)) {
            foreach ($this->menu->linked as $linkId => $sluggedLink) {
                $this->menu->url = str_replace($this->menu->url, '{' . $linkId . '}', $this->links[$sluggedLink] ?? '');

            }
        }

        $this->bot->menu = [
            "text" => $this->menu->title ?? "Меню",
            "url" => env("APP_URL") . ($this->menu->url ?? null),
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

        foreach ($this->pages as $page) {
            $page = (object)$page;

            $pageSlug = BotMenuSlug::query()
                ->create([
                    'bot_id' => $this->bot->id,
                    'command' => $page->title,
                    'comment' => "Генерируем страницу $page->title",
                    'slug' => Str::uuid(),
                    'is_global' => false,
                ]);

            if (!is_null($page->reply_keyboard ?? null))
                $replyKeyboard = BotMenuTemplate::query()->create([
                    'bot_id' => $this->bot->id,
                    'type' => 'reply',
                    'slug' => Str::uuid(),
                    'menu' => $page->reply_keyboard->menu ?? null,
                    'settings' => $page->reply_keyboard->settings ?? null,
                ]);

            if (!is_null($page->inline_keyboard ?? null))
                $inlineKeyboard = BotMenuTemplate::query()->create([
                    'bot_id' => $this->bot->id,
                    'type' => 'inline',
                    'slug' => Str::uuid(),
                    'menu' => $page->inline_keyboard->menu ?? null,
                    'settings' => null,
                ]);

            BotPage::query()->create([
                'bot_menu_slug_id' => $pageSlug->id,
                'content' => $page->content,
                'images' => null,
                'reply_keyboard_id' => $replyKeyboard->id ?? null,
                'inline_keyboard_id' => $inlineKeyboard->id ?? null,
                'need_log_user_action' => $page->need_log_user_action ?? false,
                'next_bot_menu_slug_id' => $this->links[$page->next_bot_menu_slug_id] ?? null,
                'bot_id' => $this->bot->id,
            ]);
        }

    }

    private function scriptsHandler()
    {
        if (is_null($this->scripts))
            return;

        foreach ($this->scripts as $slug) {
            $script = BotMenuSlug::query()->create([
                'bot_id' => $this->bot->id,
                'command' => $slug->command,
                'comment' => $slug->comment,
                'slug' => $slug->slug,
                'is_global' => $slug->is_global ?? false,
                'config' => $slug->config ?? null,
            ]);
            $this->links[$slug->slug] = $script->id;

            if (!is_null($slug->linked ?? null)) {

                foreach ($slug->linked as $linkId => $sluggedLink) {
                    $config = $slug->config;
                    foreach ($config as $index => $value) {
                        if ($value["key"] == $linkId)
                            $config[$index]["value"] = $this->links[$sluggedLink] ?? null;
                    }
                    $script->config = $config;
                    $script->save();

                }
            }
        }


    }

    public function run(): void
    {
        $this->loadJsonConfig();
        $this->commandsHandler();
        $this->warningHandler();
        $this->scriptsHandler();
        $this->pagesHandler();
        $this->menuHandler();
    }

}
