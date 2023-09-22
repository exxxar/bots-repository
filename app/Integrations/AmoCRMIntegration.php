<?php

namespace App\Integrations;

use AmoCRM\AmoAPI;
use AmoCRM\AmoAPIException;
use AmoCRM\AmoCompany;
use AmoCRM\AmoContact;
use App\Models\AmoCrm;
use App\Models\Bot;
use App\Models\BotUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class AmoCRMIntegration
{

    private $clientId;
    private $clientSecret;
    private $authCode;
    private $redirectUri;
    private $subdomain;


    public function __construct(object $config)
    {

        $this->clientId = $config->clientId ?? '8b6ca8e7-5ead-4242-85ab-63fe8b26ebcc';
        $this->clientSecret = $config->clientSecret ?? '0WTh7SwhAS42jzPhN1ZEc0ERF7f7YtmmCRB0FwhzPKgOjYdqauPA5hC8mdKXyFFK';
        $this->authCode = $config->authCode ?? 'def50200f7f4efb23557badf4f28bf597b5a818557edc3ec43eb9ccfe7082396e42f394c63ab6488e7921aa84f61ba59165e6cefad71db55574ed2753b5b9058bdb53ad05d86296c4445f40cc9d61b340676e5a7204ec2a686b3a45153c6bb6d92c50b483893015f78b263390026a7523ec1263540946de72ec49650d23563443c1460cc73a03e1b79a196d4b5cc515daf9a4892ced0a5b3097321739837a928d80f2f7f7823c69f762df87934662116b589ac299796754d67eef274b56205f82182cca9d49233a121694042ab384549a7ed85fa7776f27bb1911a91384e2f3e997f6099fafe9d9586367e182654690083161585079317438208281dde19f62bb1af8d1532d56f493d74b6461cca1be6e1b934c0b7ebb6d27fd139bf5cc08163e597b0baea83829883cd18f244bf59ba3d560b06fb66e586120c5d414a1b1b0593ca0a08275a5e490b9f47273f4401f5b48a0a1261b10484a3e0e3bc2023aa897329e3cbf9af88ad2a0059a4f9818ad8fc6540b096b3e3adfe3d3d31e8a2f1d1b9a6da0b716610314bcaf4e1f1c57be0958246f80856a0ad9ec983b2cbc427109b4852e8480584e5596fa2e0b621b841c23d90edab760ad33c988bc74d85887b13735639355806274bdb9bd84dc936e12d16ef155885f880a584d49d4dfcb2aa5babc29b2c805f13cf66';
        $this->redirectUri = $config->domain;
        $this->subdomain = $config->subdomain ?? 'aonktcrmgmailcom';

    }

    public function firstOAuth()
    {

        Log::info("redirectUri = $this->redirectUri");
        try {
            // Первичная авторизация
            AmoAPI::oAuth2($this->subdomain,
                $this->clientId, $this->clientSecret,
                $this->redirectUri, $this->authCode);

            // Получение информации об аккаунте вместе с пользователями и группами
            Log::info(print_r(AmoAPI::getAccount($with = 'users,groups'), true));

        } catch (AmoAPIException $e) {

            Log::info("amo error1 " . print_r($e->getMessage(), true));

            return response()->json([
                "message" => $e->getMessage()
            ], $e->getCode());
            // Log::info(printf('Ошибка авторизации (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage()));
        }
    }

    public function nextOAuth($bot)
    {

        ini_set('max_execution_time', '300000');
        try {

            $test = AmoAPI::oAuth2($this->subdomain);

            $generator = AmoAPI::getCompanies([
                'name' => $bot->bot_domain ?? 'CashMan'
            ]);

            if (is_null($generator[0]["name"] ?? null)) {
                $company = new AmoCompany([
                    'name' => $bot->bot_domain ?? 'CashMan'
                ]);
                $companyId = $company->save();
            } else
                $companyId = $generator[0]["id"];


            //Log::info( print_r($generator,true));
            //Log::info( print_r(AmoAPI::getAccount($with = 'custom_fields'),true));

            //  AmoAPI::loadTokens("")

            //371656 - воронка продаж, в которую нужно слать
            // Получение информации об аккаунте
            //Log::info(print_r(AmoAPI::getAccount(), true));

            //Log::info(print_r(  AmoAPI::getContacts(),true));


            // Log::info(print_r($generator[0]["name"] ?? null, true));
            //return;
            $botUsers = BotUser::query()
                ->where("bot_id", $bot->id)
                ->get();

            foreach ($botUsers as $botUser) {

                $generator = AmoAPI::getContacts([
                    'name' => $botUser->name ?? $botUser->fio_from_telegram ?? $botUser->telegram_chat_id,
                    'company_id' => $companyId
                ]);

                if (!is_null($generator[0]["id"] ?? null))
                    continue;

                $contact = new AmoContact([
                    'name' => $botUser->name ?? $botUser->fio_from_telegram ?? $botUser->telegram_chat_id,
                ]);


                $contact->addCompany($companyId);


                if (is_null($bot->amo->fields ?? null))
                    continue;

                $fields = [];

                foreach ($bot->amo->fields as $field) {
                    $tmpBotUser = $botUser->toArray();

                    $data = $tmpBotUser[$field->field] ?? null;
                    switch ($field->field) {
                        case 'birthday':
                            $data = (is_null($data) ? Carbon::now() : Carbon::parse($tmpBotUser[$field->field]))
                                ->format('d.M.Y');
                            break;
                        case 'sex':
                            $data = is_null($data) ? "Мужской" : ($data == 1 ? "Мужской" : "Женский");
                            break;
                    }
                    $fields[$field->key] = is_null($field->enum) ?
                        [[
                            "value" => $data,
                        ]] :
                        [[
                            "value" => $tmpBotUser[$field->field] ?? '-',
                            "enum" => $field->enum ?? '-'
                        ]];
                }

                Log::info(print_r($fields, true));
                return;
                $contact->setCustomFields($fields);

          /*      $contact->setCustomFields([

                    '211629' => [[
                        'value' => $botUser->phone ?? '-',
                        'enum' => 'MOB'
                    ]],

                    '902909' => [[
                        'value' => $botUser->fio_from_telegram ?? '-',

                    ]],
                    '868299' => [[
                        'value' => $botUser->bot->bot_domain ?? '-',

                    ]],
                    '896303' => [[
                        'value' => Carbon::now(),

                    ]],


                    '335635' => [[
                        'value' => Carbon::parse($botUser->birthday ?? Carbon::now())->format('d.M.Y'),
                    ]],
                    '211631' => [[
                        'value' => $botUser->email ?? '-',
                        'enum' => 'PRIV'
                    ]]
                ]);*/

                $contactId = $contact->save();

            }


        } catch (AmoAPIException $e) {
            Log::info("amo error2 " . print_r($e->getMessage(), true));
            $this->firstOAuth();
            //Log::info(printf('Ошибка авторизации (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage()));
        }

        ini_set('max_execution_time', '300');
    }

    public function getCustomFields($bot)
    {
        try {

            $test = AmoAPI::oAuth2($this->subdomain);
            Log::info(print_r(AmoAPI::getAccount($with = 'custom_fields'), true));
            return AmoAPI::getAccount($with = 'custom_fields');
        } catch (AmoAPIException $e) {
            Log::info("amo error2 " . print_r($e->getMessage(), true));
            $this->firstOAuth();
            //Log::info(printf('Ошибка авторизации (%d): %s' . PHP_EOL, $e->getCode(), $e->getMessage()));
        }

        return null;
    }


}
