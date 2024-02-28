<?php

namespace App\Http\BusinessLogic\Methods;

use App\Exports\BotCashBackExport;
use App\Exports\BotStatisticExport;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\BusinessLogic\Methods\Utilites\LogicUtilities;
use App\Http\Resources\BotCollection;
use App\Http\Resources\BotMenuTemplateResource;
use App\Http\Resources\BotResource;
use App\Http\Resources\BotSecurityCollection;
use App\Http\Resources\BotSecurityResource;
use App\Http\Resources\BotUserResource;
use App\Http\Resources\CashBackCollection;
use App\Http\Resources\CashBackHistoryCollection;
use App\Http\Resources\CashBackResource;
use App\Http\Resources\ImageMenuCollection;
use App\Http\Resources\ImageMenuResource;
use App\Http\Resources\LocationResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use App\Models\BotDialogCommand;
use App\Models\BotDialogGroup;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotPage;
use App\Models\BotType;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use App\Models\Company;
use App\Models\ImageMenu;
use App\Models\Location;
use App\Models\ManagerProfile;
use App\Models\Product;
use App\Models\ReferralHistory;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\FileUpload\InputFile;

class ManagerLogicFactory
{
    use LogicUtilities;


    protected $bot;

    protected $botUser;

    protected $slug;

    public function __construct()
    {
        $this->bot = null;
        $this->botUser = null;
        $this->slug = null;
    }

    /**
     * @throws HttpException
     */
    public function setBot($bot = null): static
    {
        if (is_null($bot))
            throw new HttpException(400, "Бот не задан!");

        $this->bot = $bot;
        return $this;
    }

    /**
     * @throws HttpException
     */
    public function setSlug($slug = null): static
    {
        if (is_null($slug))
            throw new HttpException(400, "Команда не задана!");

        $this->slug = $slug;
        return $this;
    }

    /**
     * @throws HttpException
     */
    public function setBotUser($botUser = null): static
    {
        if (is_null($botUser))
            throw new HttpException(400, "Пользователь бота не задан!");

        $this->botUser = $botUser;
        return $this;
    }


    public function friendList(): array
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $bot = $this->bot;//Bot::query()->find(21);//;
        $botUser = $this->botUser;//BotUser::query()->find(182);//$request->botUser;

        $userIds = ReferralHistory::query()
            ->where("user_sender_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->get()
            ->pluck("user_recipient_id");


        $botUsersLevel1 = BotUser::query()
            ->whereIn("user_id", $userIds)
            ->where("bot_id", $bot->id)
            ->select(["id", "fio_from_telegram", "telegram_chat_id", "parent_id", "user_id", "bot_id"])
            ->get();


        $userIdsLevel2 = ReferralHistory::query()
            ->whereIn("user_sender_id", $botUsersLevel1->pluck("user_id"))
            ->where("bot_id", $bot->id)
            ->get()
            ->pluck("user_recipient_id");

        $botUsersLevel2 = BotUser::query()
            ->whereIn("user_id", $userIdsLevel2)
            ->where("bot_id", $bot->id)
            ->select(["id", "fio_from_telegram", "telegram_chat_id", "parent_id", "user_id", "bot_id"])
            ->get();


        $userIdsLevel3 = ReferralHistory::query()
            ->whereIn("user_sender_id", $botUsersLevel2->pluck("user_id"))
            ->where("bot_id", $bot->id)
            ->get()
            ->pluck("user_recipient_id");

        $botUsersLevel3 = BotUser::query()
            ->whereIn("user_id", $userIdsLevel3)
            ->where("bot_id", $bot->id)
            ->select(["id", "fio_from_telegram", "telegram_chat_id", "parent_id", "user_id", "bot_id"])
            ->get();

        $tmp = $botUsersLevel1->toArray();

        $level1Index = 0;
        foreach ($tmp as $level1) {

            if (!isset($tmp[$level1Index]["child"]))
                $tmp[$level1Index]["child"] = [];

            $level2Index = 0;
            $tmpLevel2 = $botUsersLevel2->toArray() ?? [];

            $test1 = [];
            foreach ($tmpLevel2 as $level2) {


                if (!isset($tmpLevel2[$level2Index]["child"]))
                    $tmpLevel2[$level2Index]["child"] = [];


                $tmpLevel3 = $botUsersLevel3->toArray() ?? [];
                $level3Index = 0;
                $test2 = [];

                foreach ($tmpLevel3 as $level3) {
                    if ($tmpLevel2[$level2Index]["id"] === $tmpLevel3[$level3Index]["parent_id"]) {
                        $test2[] = $tmpLevel3[$level3Index];
                    }


                    $level3Index++;
                }

                $tmpLevel2[$level2Index]["child"] = $test2;

                if ($tmp[$level1Index]["id"] == $tmpLevel2[$level2Index]["parent_id"]) {
                    $test1[] = $tmpLevel2[$level2Index];
                }


                $level2Index++;
            }

            $tmp[$level1Index]["child"] = $test1;

            $level1Index++;

        }

        return $tmp;
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function managerRegister(array $data, $uploadedPhoto = null): void
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "name" => "required",
            "phone" => "required",
           // "birthday" => "required",
            "city" => "required",
            // "country" => "required",
            // "address" => "required",
           // "sex" => "required",
           // "referral" => "",
           // "info" => "",
           // "strengths" => "required",
           // "weaknesses" => "required",
           // "educations" => "required",
           // "social_links" => "required",
          //  "skills" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $birthday = Carbon::parse($data["birthday"] ?? Carbon::now())->format("Y-m-d");
        $form1 = [
            "birthday" => $birthday,
            "name" => $data["name"] ?? null,
            "phone" => $data["phone"] ?? null,
            "city" => $data["city"] ?? null,
            "country" => $data["country"] ?? null,
            "address" => $data["address"] ?? null,
            "sex" => ($data["sex"] ?? false) == "on" ? 1 : 0,
            "email" => $data["email"] ?? null,
            "is_manager" => true,
            "age" => Carbon::now()->year - Carbon::parse($birthday)
                    ->year
        ];

        $strengths = json_decode($data["strengths"] ?? '[]');
        $weaknesses = json_decode($data["weaknesses"] ?? '[]');
        $educations = json_decode($data["educations"] ?? '[]');
        $socialLinks = json_decode($data["social_links"] ?? '[]');
        $skills = json_decode($data["skills"] ?? '[]');

        $imageName = $data["image"] ?? null;

        if (!is_null($uploadedPhoto))
            $imageName = $this->uploadPhotos("/public/companies/" . $this->bot->company->slug, $uploadedPhoto)[0];


        $referral = $data["referral"] ?? null;

        $form2 = [
            'bot_user_id' => $this->botUser->id,
            'info' => $data["info"] ?? null,
            'referral' => $referral,
            'strengths' => $strengths,
            'weaknesses' => $weaknesses,
            'educations' => $educations,
            'social_links' => $socialLinks,
            'skills' => $skills,
            'stable_personal_discount' => 0,
            'permanent_personal_discount' => 0,
            'max_company_slot_count' => 1,
            'max_bot_slot_count' => 1,
            'balance' => 0,
            'verified_at' => null
        ];

        $this->botUser->update($form1);

        if (!is_null($data["id"] ?? null))
            $manager = ManagerProfile::query()
                ->where("bot_user_id", $this->botUser->id)
                ->where("id", $data["id"])
                ->first();


        if (is_null($manager ?? null))
            $manager = ManagerProfile::query()->create($form2);
        else
        {
            unset($form2["max_company_slot_count"]);
            unset($form2["max_bot_slot_count"]);

            $manager->update($form2);
        }



        $manager->image = !is_null($uploadedPhoto)?"/images-by-bot-id/".$this->bot->id.'/'.$imageName : $imageName;
        $manager->save();

        $botUser = $this->botUser->refresh();
        Session::put("bot_user", $botUser);

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $this->botUser->telegram_chat_id,
                "Ваши анкетные данные приняты в работу! Вы уже менеджер, остались нюансы!"
            );

        if (is_null($referral))
            return;

        $pattern_simple = "/([0-9]{3})([0-9]+)/";

        $string = base64_decode($referral);

        preg_match_all($pattern_simple, $string, $matches);

        $telegramChatId = $matches[2][0] ?? null;

        $refBotUser = BotUser::query()
            ->where("bot_id", $this->bot->id)
            ->where("telegram_chat_id", $telegramChatId)
            ->first();

        if (is_null($refBotUser))
            return;

        if ($this->botUser->telegram_chat_id == $refBotUser->telegram_chat_id) {
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendMessage(
                    $this->botUser->telegram_chat_id,
                    "Вы перешли по своей собственной ссылке... вы, конечно, себе друг, но CashBack достанется кому-то одному..."
                );

            return;

        }


        $ref = ReferralHistory::query()
            ->where("user_sender_id", $refBotUser->user_id)
            ->where("user_recipient_id", $this->botUser->user_id)
            ->where("bot_id", $this->botUser->bot_id)
            ->first();

        if (is_null($ref)) {
            ReferralHistory::query()->create([
                'user_sender_id' => $refBotUser->user_id,
                'user_recipient_id' => $this->botUser->user_id,
                'bot_id' => $this->botUser->bot_id,
                'activated' => true,
            ]);

            $userName1 = BotMethods::prepareUserName($this->botUser);
            $userName2 = BotMethods::prepareUserName($refBotUser);

            $this->botUser->parent_id = $refBotUser->id;
            $this->botUser->save();

            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendMessage(
                    $refBotUser->telegram_chat_id,
                    "По вашей ссылке перешел пользователь $userName1"
                )
                ->sendMessage(
                    $this->botUser->telegram_chat_id,
                    "Вас и вашего друга $userName2 теперь обьеденяет еще и CashBack;)"
                );
        }


    }


    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function switchBotStatusManager(array $data): void
    {

        if (is_null($this->botUser))
            throw new HttpException(404, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
            "botId" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $data["botId"])
            ->first();

        if (is_null($bot))
            throw new HttpException(404, "Бот не найден!");

        if ($bot->company->creator_id != $this->botUser->id)
            throw new HttpException(403, "Бот не принадлежит менеджеру!");

        $bot->is_active = !$bot->is_active;
        $bot->save();
    }

    public function createVisitCardBot()
    {

    }

    public function createBusinessBot()
    {

    }

}
