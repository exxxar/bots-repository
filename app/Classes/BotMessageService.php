<?php

namespace App\Classes;

use Illuminate\Support\Facades\Storage;

class BotMessageService
{
    protected string $file = 'bot_messages.json';
    protected object|null $bot = null;
    protected object|null $user = null;

    protected array $keyDict = [
        'name' => 'Имя пользователя',
        'cash_back' => 'Сумма бонусных балов у пользователя',
        'age' => 'Возраст клиента',
        'amount' => 'Величина операции (списания \ начисления)',
        'rule_value' => 'Величина, указанная в правиле',
        'level_index' => 'Индекс уровня начисления \ списания бонусных баллов',
        'admin_name' => 'Имя администратора, выполняющего действия',
        'user_link' => 'Ссылка на диалог с пользователем',
    ];

    protected array $defaultMessages = [
        'welcome' => 'Привет, {{name}}! Добро пожаловать.',
        'balance' => 'Ваш кэшбэк: {{cash_back}} руб.',
        'age_info' => 'Вам {{age}} лет.',
        'not_admin' => 'Вы не являетесь администратором данного бота! Данное действие недоступно!',
        'need_review_mark' => "Пожалуйста, поставьте оценку нашей работе!",
        'success_cashback_deduct' => 'Вы успешно списали {{amount}}',
        'cashback_warn_1' => 'Внимание! Сумма списания CashBack {{amount}} руб.\n',
        'cashback_warn_2' => 'Внимание! Сумма чека {{amount}} руб. > {{rule_value}} руб (для уровня {{level_index}})\n',
        'cashback_warn_3' => 'Внимание! Сумма начисления CashBack {{amount}} руб.  > {{rule_value}} руб (для уровня {{level_index}}) \n',
        'cashback_warn_4' => 'Вам начислили <b>{{amount}} руб.</b> CashBack {{level_index}} уровня',
        'cashback_warn_5' => 'Администратор {{admin_name}} успешно начислил <b>  {{amount}} руб.</b> ({{level_index}} уровня) CashBaсk пользователю {{name}} {{user_link}}',
        'cashback_warn_6' => '🚨🚨🚨🚨\n$this->warnText\nОперация выполнена администратором $nameAdmin ($tgAdminId) для пользователя $nameUser ($tgUserId)'
    ];

    public function query($bot): static
    {
        $this->bot = $bot;
        return $this;
    }


    /**
     * Установить данные пользователя
     */
    public function setBotUser($user): self
    {
        $this->user = $user;
        return $this;
    }

    public function message(string $key, array $params = []): string
    {
        return $this->getMessage($key, $params);
    }

    public function getDictionary(): array{
        return $this->keyDict ?? [];
    }
    /**
     * Получить сообщение по ключу
     */
    public function getMessages(): array
    {
        $messages = $this->loadMessages();

        $botDomain = $this->bot->bot_domain ?? 'default';

        $tmpMessages = [];

        foreach ($messages[$botDomain] ?? [] as $key => $value)
            $tmpMessages[$key] = $value;

        foreach ($this->defaultMessages ?? [] as $key => $value)
            if (!isset($tmpMessages[$key]))
                $tmpMessages[$key] = $value;


        return $tmpMessages;
    }

    /**
     * Получить сообщение по ключу
     */
    public function getMessage(string $key, array $params = []): string
    {
        $messages = $this->loadMessages();

        $botDomain = $this->bot->bot_domain ?? 'default';

        $text = $messages[$botDomain][$key]
            ?? $this->defaultMessages[$key]
            ?? "[[$key not found]]";

        return $this->processMessage($text, $params);
    }

    /**
     * Сохранить все сообщения для бота
     */
    public function saveMessages(array $messages): void
    {
        $botDomain = $this->bot->bot_domain ?? 'default';

        $all = $this->loadMessages();
        $all[$botDomain] = $messages;
        $this->storeMessages($all);
    }

    /**
     * Сохранить/обновить одно сообщение
     */
    public function saveMessage(string $key, string $text): void
    {

        $botDomain = $this->bot->bot_domain ?? 'default';

        $all = $this->loadMessages();
        $all[$botDomain][$key] = $text;
        $this->storeMessages($all);
    }

    /**
     * Загрузить JSON из storage
     */
    protected function loadMessages(): array
    {
        if (!Storage::exists($this->file)) {
            return [];
        }

        return json_decode(Storage::get($this->file), true) ?? [];
    }

    /**
     * Сохранить JSON в storage
     */
    protected function storeMessages(array $messages): void
    {
        Storage::put($this->file, json_encode($messages, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    /**
     * Обработка шаблонов {{name}}, {{age}}, {{cashBack}}
     */
    protected function processMessage(string $text, array $params = []): string
    {
        $localParams = [

        ];
        $replacements = array_merge($localParams, $params);

        foreach ($replacements as $key => $value) {
            $text = str_replace('{{' . $key . '}}', $value, $text);
        }

        return $text;
    }
}
