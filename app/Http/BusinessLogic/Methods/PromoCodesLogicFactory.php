<?php

namespace App\Http\BusinessLogic\Methods;

use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\Resources\ActionStatusResource;
use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\AppointmentEventCollection;
use App\Http\Resources\AppointmentReviewResource;
use App\Http\Resources\PromoCodeCollection;
use App\Http\Resources\PromoCodeResource;
use App\Http\Resources\QuizAnswerResource;
use App\Http\Resources\QuizCollection;
use App\Http\Resources\QuizCommandCollection;
use App\Http\Resources\QuizCommandResource;
use App\Http\Resources\QuizQuestionCollection;
use App\Http\Resources\QuizQuestionResource;
use App\Http\Resources\QuizResource;
use App\Http\Resources\QuizResultCollection;
use App\Models\ActionStatus;
use App\Models\AmoCrm;
use App\Models\AppointmentEvent;
use App\Models\AppointmentReview;
use App\Models\AppointmentService;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\PromoCode;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizCommand;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\FileUpload\InputFile;

class PromoCodesLogicFactory extends BaseLogicFactory
{


    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function activatePromoCodeForDiscount(array $data): object
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(400, "Не все условия функции выполнены!");

        $validator = Validator::make($data, [
            'code' => "required",

        ]);

        if ($validator->fails())
            throw new ValidationException($validator);
        $bot = $this->bot;
        $botUser = $this->botUser;

        $code = PromoCode::query()
            ->with(["botUsers"])
            ->where("bot_id", $bot->id)
            ->where("code", $data["code"])
            ->first();

        if (is_null($code))
            throw new HttpException(404, "Промокод не найден");

        if (!$code->is_active)
            throw new HttpException(403, "Промокод не активен");

        if (!is_null($code->available_to)) {
            if (Carbon::parse($code->available_to)->timestamp < Carbon::now()->timestamp)
                throw new HttpException(400, "Срок действия промокода закончен!");
        }

        $isPromoActivated = !is_null($code->botUsers()
            ->where("bot_user_id", $botUser->id)
            ->first() ?? null);

        if ($isPromoActivated)
            throw new HttpException(400, "Промокод уже активирован");

        $maxActivations = $code->max_activation_count ?? 0;
        $currentActivations = $code->botUsers()->count() ?? 0;

        if ($currentActivations >= $maxActivations) {
            $code->is_active = false;
            $code->save();
            throw new HttpException(400, "Закончились попытки активации промокода!");
        }

        $code->botUsers()->attach([$botUser->id]);
        $code->save();

        if ($code->cashback_amount == 0)
            throw new HttpException(400, "Данный промокод нельзя активировать как скидочный!");

        return (object)[
            "discount" => $code->cashback_amount,
            "discount_in_percent" => $code->config["discount_in_percent"] ?? false,
            "activate_price" => $code->activate_price,
        ];
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function activatePromoCode(array $data): object
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(400, "Не все условия функции выполнены!");

        $validator = Validator::make($data, [
            'code' => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);
        $bot = $this->bot;
        $botUser = $this->botUser;

        $code = PromoCode::query()
            ->with(["botUsers"])
            ->where("bot_id", $bot->id)
            ->where("code", $data["code"])
            ->first();

        if (is_null($code))
            throw new HttpException(404, "Промокод не найден");

        if (!$code->is_active)
            throw new HttpException(403, "Промокод не активен");

        if (!is_null($code->available_to)) {
            if (Carbon::parse($code->available_to)->timestamp < Carbon::now()->timestamp)
                throw new HttpException(400, "Срок действия промокода закончен!");
        }


        $isPromoActivated = !is_null($code->botUsers()->where("bot_user_id", $botUser->id)->first() ?? null);

        if ($isPromoActivated)
            throw new HttpException(400, "Промокод уже активирован");

        $maxActivations = $code->max_activation_count ?? 0;
        $currentActivations = $code->botUsers()->count() ?? 0;

        if ($currentActivations >= $maxActivations) {
            $code->is_active = false;
            $code->save();
            throw new HttpException(400, "Закончились попытки активации промокода!");
        }

        $code->botUsers()->attach([$botUser->id]);
        $code->save();

        $tmpSlotsCount = 0;
        $tmpCashBackCount = 0;
        if (!is_null($botUser->manager)) {
            $botUser->manager->max_company_slot_count += $code->slot_amount ?? 0;
            $botUser->manager->max_bot_slot_count += $code->slot_amount ?? 0;
            $botUser->manager->save();

            $tmpSlotsCount += $code->slot_amount;

            if (count($code->scripts ?? []) > 0) {

                $tmpIds = Collection::make($botUser->manager->scripts)
                    ->pluck("id");

                $scriptsIds = Collection::make($code->scripts)
                    ->pluck("id");

                $botUser->manager->scripts()->sync([...$scriptsIds, ...$tmpIds]);
            }
        }


        if ($code->cashback_amount > 0) {

            $admin = BotUser::query()
                ->where("bot_id", $bot->id)
                ->where("is_admin", true)
                ->orderBy("updated_at", "desc")
                ->first();

            if (is_null($admin))
                throw new HttpException(400, "В системе нет администратора!");

            $cashBackAmount = $code->cashback_amount;
            $tmpCashBackCount += $code->cashback_amount;

            BusinessLogic::administrative()
                ->setBot($bot)
                ->setBotUser($admin)
                ->addCashBack([
                    "user_telegram_chat_id" => $botUser->telegram_chat_id,
                    "amount" => $cashBackAmount,
                    "percent" => 100,
                    "info" => "Мгновенное начисление CashBack в размере $cashBackAmount руб.",
                ]);
        }


        return (object)[
            "cashback" => $tmpCashBackCount,
            "slots" => $tmpSlotsCount
        ];
    }


    /**
     * @throws HttpException
     */
    public function listOfPromoCodes($search = null, $size = null, $order = null, $direction = null): PromoCodeCollection
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Не все условия функции выполнены!");

        $size = $size ?? config('app.results_per_page');

        $codes = PromoCode::query()
            // ->withTrashed()
            ->where("bot_id", $this->bot->id);

        if (!is_null($search))
            $codes = $codes->where(function ($q) use ($search) {
                $q->where("description", 'like', "%$search%");
                $q->orWhere("code", 'like', "%$search%");
            });


        $codes = $codes
            ->orderBy($order ?? 'updated_at', $direction ?? 'DESC')
            ->paginate($size);

        return new PromoCodeCollection($codes);
    }

    public function listOfResults($quizId, $size = null, $order = null, $direction = null): QuizResultCollection
    {
        if (is_null($this->bot))
            throw new HttpException(400, "Не все условия функции выполнены!");

        $size = $size ?? config('app.results_per_page');

        $quiz = Quiz::query()
            ->find($quizId);

        if (is_null($quiz))
            throw new HttpException(404, "Квиз не найден!");


        $results = QuizResult::query()
            ->with(["command"])
            // ->withTrashed()
            ->where("quiz_id", $quizId)
            ->orderBy($order ?? 'updated_at', $direction ?? 'DESC')
            ->paginate($size);

        return new QuizResultCollection($results);
    }

    /**
     * @throws HttpException
     */
    public function removePromoCode($promoCodeId): PromoCodeResource
    {
        $code = PromoCode::query()
            ->where("id", $promoCodeId)
            ->first();

        if (is_null($code))
            throw new HttpException(404, "Промокод не найден");

        $tmp = $code;

        Log::info("каким-то хером удалился промокод");

        $code->delete();

        return new PromoCodeResource($tmp);
    }

    public function generateCertificate($titleText, $prizeText, $finalDate = null)
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Условия функции не выполнены!");

        $date = $finalDate ?? date('d.m.Y');

        $templatePath = storage_path('app/public/certificates/certificate_template.png');

        // Загружаем изображение
        $image = imagecreatefrompng($templatePath);

        // Получаем размеры изображения
        $imageWidth = imagesx($image);
        $imageHeight = imagesy($image);

        // Устанавливаем цвет текста (черный)
        $textColor = imagecolorallocate($image, 0, 0, 0);

        // Указываем путь к шрифту (TrueType)
        $fontPath = storage_path('app/public/certificates/Ura Bum Bum SP.ttf'); // Добавь свой шрифт в public/fonts

        // Размеры шрифта
        $fontSizeName = 20;
        $fontSizeInfo = 16;

        // Высоты строк
        $yName = $imageHeight / 2 - 30;
        $yCourse = $yName + 40;
        $yDate = $yCourse + 30;

        // Функция для отцентровки текста
        $centerText = function ($text, $fontSize, $y) use ($image, $imageWidth, $textColor, $fontPath) {
            $box = imagettfbbox($fontSize, 0, $fontPath, $text);
            $textWidth = abs($box[2] - $box[0]);
            $x = ($imageWidth - $textWidth) / 2;
            imagettftext($image, $fontSize, 0, $x, $y, $textColor, $fontPath, $text);
        };

        $centerText($titleText, $fontSizeName, $yName);
        $centerText("Промокод: $prizeText", $fontSizeInfo, $yCourse);
        $centerText("Дата окончания: $date", $fontSizeInfo, $yDate);

        $qrText = "https://t.me/" . $this->bot->bot_domain;
        // Генерируем QR в PNG и получаем как строку
        $qrPng = QrCode::format('png')->size(100)->margin(1)->generate($qrText);

        // Создаём изображение QR-кода из строки
        $qrImage = imagecreatefromstring($qrPng);

        // Координаты для размещения (правый нижний угол с отступами)
        $qrWidth = imagesx($qrImage);
        $qrHeight = imagesy($qrImage);
        $padding = 30;

        $qrX = ($imageWidth / 2) - $qrWidth + 50;
        $qrY = ($imageHeight / 2) - $qrHeight + 200;

        // Накладываем QR-код
        imagecopy($image, $qrImage, $qrX, $qrY, 0, 0, $qrWidth, $qrHeight);
        // Буферизуем вывод
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();

        // Освобождаем память
        imagedestroy($image);


        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendPhoto(
                $this->botUser->telegram_chat_id,
                "Информация о сертификате",
                InputFile::createFromContents($imageData, "certificate.png")
            );
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function store(array $data): PromoCodeResource
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
            'code' => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $needCertificate = ($data["need_certificate"] ?? false) == "true";

        $tmp = [
            'bot_id' => $this->bot->id,
            'code' => $data["code"],
            'description' => $data["description"] ?? null,
            'slot_amount' => $this->botUser->is_admin ? ($data["slot_amount"] ?? 0) : 0,
            'cashback_amount' => $data["cashback_amount"] ?? 0,
            'activate_price' => $data["activate_price"] ?? 0,
            'max_activation_count' => $data["max_activation_count"] ?? 1,
            'is_active' => ($data["is_active"] ?? false) == "true",
            'available_to' => !isset($data["available_to"]) ? null : Carbon::parse($data["available_to"]),
            'config' => isset($data["config"]) ? json_decode($data["config"]) : (object)[
                "discount_in_percent" => false
            ],
        ];

        if (is_null($data["id"] ?? null)) {

            $isCodeExist = !is_null(PromoCode::query()
                ->where("bot_id", $this->bot->id)
                ->where("code", $data["code"])
                ->first() ?? null);

            if ($isCodeExist)
                throw new HttpException(403, "Данный код уже существует в системе");

            $code = PromoCode::query()->create($tmp);
        } else {
            $code = PromoCode::query()
                ->where("bot_id", $this->bot->id)
                ->find($data["id"]);

            $code->update($tmp);
        }

        $scripts = json_decode($data["scripts"] ?? '[]');

        if (count($scripts) > 0) {
            $scriptsIds = Collection::make($scripts)
                ->pluck("id");

            $code->scripts()->sync($scriptsIds);
        }

        $this->generateCertificate(
            $tmp["description"] ?? 'Промокод на приз',
                $tmp["code"] ?? 'Промокод не найден',
            $tmp["available_to"]);

        return new PromoCodeResource($code);
    }


}
