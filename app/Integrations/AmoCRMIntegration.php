<?php

namespace App\Integrations;

use AmoCRM\AmoAPI;
use AmoCRM\AmoAPIException;
use AmoCRM\AmoContact;
use Illuminate\Support\Facades\Log;

class AmoCRMIntegration
{

    private $clientId;
    private $clientSecret;
    private $authCode;
    private $redirectUri;
    private $subdomain;


    public function __construct()
    {
        $this->clientId = '8b6ca8e7-5ead-4242-85ab-63fe8b26ebcc';
        $this->clientSecret = '0WTh7SwhAS42jzPhN1ZEc0ERF7f7YtmmCRB0FwhzPKgOjYdqauPA5hC8mdKXyFFK';
        $this->authCode = 'def50200799145fedf78acdc05574029ddf9872724a71734fc046e016d6e67cd6c7df2f30ae6715db0f4ca8093fac0fa40d47f7efdb22dafc4a726e7dda492baff38774c945a9a03f2aec4b25fa87cacb7751385a72f7b7cc2b14e410bd3b7e23f42966f59d5d8a62ef7b721bdaaa72fd35b0f2043002c7d97bd137c1be8ae1e41c9f27d91db3d885e0a5bc8a8c5a922c9ae4f78194788826e7a88429c45878332532f511efbe7e2ef21ef591b635d8f2d86294c624404b41fe2a0af5245667f08b34cd1dc7f6a5dd43f410cff750306c157cea22a5a7eb094b4796dc056afad4f4048e017bc920a4300937bf46960008660c375c68c902f92f4c57ff2d80f89bef855bbec434933508a2b93a74976f6fb62cffa5ecf6b0d28fc7f6d0d9aaaa7ba0512f2ea0bcc576860515b4ea977a396c709f28ccfcdfe1147388240d047aa02369799129b88a5350f8308637e685d2413f72f94b8bdc43b12e5b032de1603df033bb4d8da4eb20f19f3029d28174e253fa12984f38c356f51295a4af93172dac049822c50b9b697aeac55a6b9fcde7b44913f39dca777eff6f3e24cbc3b17959f3e1756ef92689d38b3d0beb892acc81bd263c81a38f91f9aefa77151d38cb586fc228d5b385e8ed21191022b7ca08b106fa29a5ac07c0386c95ec8209fe66d0bce95f7bf047f3c5d';
        $this->redirectUri = 'https://your-cashman.com/crm/amo/flera_hus_bot';
        $this->subdomain = 'aonktcrmgmailcom';

    }

    public function firstOAuth()
    {
        try {
            // Первичная авторизация
            AmoAPI::oAuth2($this->subdomain, $this->clientId, $this->clientSecret, $this->redirectUri, $this->authCode);

            // Получение информации об аккаунте вместе с пользователями и группами
            Log::info(print_r(AmoAPI::getAccount($with = 'users,groups'), true));

        } catch (AmoAPIException $e) {
            Log::info(printf('Ошибка авторизации (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage()));
        }
    }

    public function nextOAuth()
    {

        try {

            AmoAPI::oAuth2($this->subdomain);

            // Получение информации об аккаунте
            Log::info(print_r(AmoAPI::getAccount(), true));

            $contact1 = new AmoContact([
                'name'                => 'Тест CashMAN Contact',
                'responsible_user_id' => 6437674
            ]);

            // Установка дополнительных полей
            $contact1->setCustomFields([
                '6532343' => 41,
                '123456' => [[
                    'value' => '+79494320661',
                    'enum'  => 'WORK'
                ]],
                '123467' => [[
                    'value' => 'hans@example.com',
                    'enum'  => 'WORK'
                ]]
            ]);

            $contact1Id = $contact1->save();



        } catch (AmoAPIException $e) {
            Log::info(printf('Ошибка авторизации (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage()));
        }
    }


}
