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
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
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


    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function managerRegister(array $data): void
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        $validator = Validator::make($data, [
            "name" => "required",
            "phone" => "required",
            "birthday" => "required",
            "city" => "required",
            // "country" => "required",
            "address" => "required",
            "sex" => "required",
            "referral" => "",
            "info" => "",
            "strengths" => "required",
            "weaknesses" => "required",
            "educations" => "required",
            "social_links" => "required",
            "skills" => "required",
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

        $strengths = json_decode($data["strengths"]??'[]');
        $weaknesses = json_decode($data["weaknesses"]??'[]');
        $educations = json_decode($data["educations"]??'[]');
        $socialLinks = json_decode($data["social_links"]??'[]');
        $skills = json_decode($data["skills"]??'[]');

        $form2 = [
            'bot_user_id'=>$this->botUser->id,
            'info'=>$data["info"] ?? null,
            'referral'=>$data["referral"] ?? null,
            'strengths'=>$strengths,
            'weaknesses'=>$weaknesses,
            'educations'=>$educations,
            'social_links'=>$socialLinks,
            'skills'=>$skills,
            'stable_personal_discount'=>0,
            'permanent_personal_discount'=>0,
            'max_company_slot_count'=>1,
            'max_bot_slot_count'=>1,
            'balance'=>0,
            'verified_at'=>null
        ];

        $this->botUser->update($form1);

        $manager = ManagerProfile::query()
            ->where("bot_user_id", $this->botUser->id)
            ->first();

        if (is_null($manager))
            ManagerProfile::query()->create($form2);
        else
            $manager->update($form2);

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $this->botUser->telegram_chat_id,
                "Ваши анкетные данные приняты в работу! Вы уже менеджер, остались нюансы!"
            );

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

}
