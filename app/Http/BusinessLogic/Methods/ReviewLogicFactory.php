<?php

namespace App\Http\BusinessLogic\Methods;

use App\Enums\OrderStatusEnum;
use App\Enums\OrderTypeEnum;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\BusinessLogic\Methods\Utilites\LogicUtilities;
use App\Http\Resources\ProductCategoryCollection;
use App\Http\Resources\ProductCategoryResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ReviewCollection;
use App\Http\Resources\ReviewResource;
use App\Models\Basket;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOption;
use App\Models\Review;
use App\Models\Transaction;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Mpdf\Mpdf;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\FileUpload\InputFile;

class ReviewLogicFactory
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
    public function setBot($bot): static
    {
        if (is_null($bot))
            throw new HttpException(400, "Бот не задан!");

        $this->bot = $bot;
        return $this;
    }

    /**
     * @throws HttpException
     */
    public function setSlug($slug): static
    {
        if (is_null($slug))
            throw new HttpException(400, "Команда не задана!");

        $this->slug = $slug;
        return $this;
    }

    /**
     * @throws HttpException
     */
    public function setBotUser($botUser): static
    {
        if (is_null($botUser))
            throw new HttpException(400, "Пользователь бота не задан!");

        $this->botUser = $botUser;
        return $this;
    }


    public function reviews($size = null): ReviewCollection
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Условия функции не выполнены!");

        $size = $size ?? config('app.results_per_page');

        $reviews = Review::query()
            ->with(["product"])
            ->where("bot_id", $this->bot->id)
            ->where("bot_user_id", $this->botUser->id)
            ->whereNotNull("product_id")
            ->paginate($size);

        return new ReviewCollection($reviews);
    }

    public function reviewsByProductId($productId, $size = null): ReviewCollection
    {

        $size = $size ?? config('app.results_per_page');

        $reviews = Review::query()
            ->where("product_id", $productId)
            ->paginate($size);

        return new ReviewCollection($reviews);
    }

    /**
     * @throws ValidationException
     */
    public function prepareReviews($orderId, $productsIds = []): void
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Условия функции не выполнены!");

        $this->store([
            "order_id" => $orderId
        ]);

        foreach ($productsIds as $id) {
            $this->store([
                "order_id" => $orderId,
                "product_id" => $id,
            ]);
        }
    }

    public function notifyUserForReview(array $data): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Условия функции не выполнены!");

        $productId = $data["product_id"] ?? null;
        $orderId = $data["order_id"] ?? null;


        $message = "Вы забыли оставить отзыв!";

        if (!is_null($orderId) && is_null($productId))
            $message = "Вы забыли оставить отзыв к заказу №$orderId";

        if (!is_null($productId))
            $message = "Вы забыли оставить отзыв к товару №$productId";


        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage($this->botUser->telegram_chat_id, $message);
    }
    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function store(array $data, array $uploadedPhotos = null): ReviewResource
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
            'id' => "",
            'bot_user_id' => "",
            'product_id' => "",
            'text' => "",
            'rating' => "",
            'images' => "",
            'send_review_at' => "",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $slug = $this->bot->company->slug;

        $photos = !is_null($uploadedPhotos) ?
            $this->uploadPhotos("/public/companies/$slug", $uploadedPhotos) : [];

        $images = $data["images"] ?? null;

        if (!is_null($images))
            $images = json_decode($images);

        $images = count($photos) == 0 ? (is_array($images) ? $images : null) : [...$photos, ...$images];

        $reviewId = $data["id"] ?? null;

        $needUserNotify = ($data["need_user_notify"] ?? false) == "true";

        $tmp = [
            'bot_id' => $this->bot->id,
            'order_id' => $data["order_id"] ?? null,
            'bot_user_id' => $data["bot_user_id"] ?? $this->botUser->id,
            'product_id' => $data["product_id"] ?? null,
            'text' => $data["text"] ?? null,
            'rating' => $data["rating"] ?? 5,
            'images' => $images,
            'send_review_at' => !is_null($data["id"] ?? null) ? Carbon::now()->format("Y-m-d H:i:s") : null,
        ];

        if (!is_null($reviewId)) {
            $review = Review::query()
                ->where("id", $reviewId)
                ->first();

            if (!is_null($review->send_review_at ?? null) && !is_null($data["send_review_at"] ?? null))
                return new ReviewResource($review);

            if (is_null($data["send_review_at"] ?? null) && !is_null($review->send_review_at ?? null)) {
                $tmp["send_review_at"] = null;
                if ($needUserNotify) {
                    BotMethods::bot()
                        ->whereBot($this->bot)
                        ->sendMessage($this->botUser->telegram_chat_id, "Ваш отзыв на товар был удален. Вы можете оставить другой отзыв!");
                }
            }


            $review->update($tmp);
        } else
            $review = Review::query()
                ->create($tmp);

        $productId = $data["product_id"] ?? null;

        if (!is_null($productId)) {
            $product = Product::query()
                ->where("id", $productId)
                ->first();

            if (!is_null($product)) {
                $average = Review::query()
                    ->where("bot_id", $this->bot->id)
                    ->where("product_id", $product->id)
                    ->average("rating") ?? 5;

                $product->rating = $average;
                $product->save();
            }
        }


        return new ReviewResource($review);

    }


    /**
     * @throws HttpException
     */
    public function destroy($reviewId): ReviewResource
    {
        $review = Review::query()
            ->find($reviewId);

        if (is_null($review))
            throw new HttpException(404, "Отзыв не найден");

        $tmpReview = $review;
        $review->delete();

        if (!is_null($tmpReview->product_id ?? null)) {
            $product = Product::query()
                ->where("id", $tmpReview->product_id)
                ->first();

            if (!is_null($product)) {
                $average = Review::query()
                    ->where("bot_id", $this->bot->id)
                    ->where("product_id", $product->id)
                    ->average("rating") ?? 5;

                $product->rating = $average;
                $product->save();
            }
        }


        return new ReviewResource($tmpReview);
    }


}
