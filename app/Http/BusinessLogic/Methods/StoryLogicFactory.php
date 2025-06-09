<?php

namespace App\Http\BusinessLogic\Methods;

use App\Facades\BotMethods;
use App\Http\BusinessLogic\Methods\Utilites\LogicUtilities;
use App\Http\Resources\StoryCollection;
use App\Http\Resources\StoryResource;
use App\Models\Story;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StoryLogicFactory extends BaseLogicFactory
{
    use LogicUtilities;

    /**
     * Получение списка историй
     */
    public function list($size = null): StoryCollection
    {
        if (is_null($this->bot)) {
            throw new HttpException(404, "Бот не найден.");
        }

        $size = $size ?? config('app.results_per_page');

        $stories = Story::query()
            ->where('bot_id', $this->bot->id)
            ->paginate($size);

        return new StoryCollection($stories);
    }

    /**
     * Получение истории по ID
     */
    public function getById(int $storyId): StoryResource
    {
        $story = Story::query()->find($storyId);

        if (is_null($story)) {
            throw new HttpException(404, "История не найдена.");
        }

        return new StoryResource($story);
    }

    /**
     * Создание или обновление истории
     */
    public function store(array $data, $files = []): StoryResource
    {
        if (is_null($this->bot)) {
            throw new HttpException(404, "Бот не найден.");
        }

        $validator = Validator::make($data, [
            'id' => 'nullable|integer',
            'title' => 'required|string|max:255',
            //  'thumbnail' => 'nullable|string',
            // 'image' => 'nullable|string',
            'description' => 'nullable|string',
            'config' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $storyData = array_merge($validator->validated(), [
            'bot_id' => $this->bot->id,
        ]);

        if (!empty($data['id'])) {
            $story = Story::query()->find($data['id']);
            if (!$story) {
                throw new HttpException(404, "История не найдена.");
            }
            $story->update($storyData);
        } else {
            $story = Story::create($storyData);
        }

        if (count($files ?? []) > 0) {
            $thumbnail = $files->get("thumbnail");
            $image = $files->get("image");

            $filename = time() . '_' . $thumbnail[0]->getClientOriginalName();
            $thumbnail[0]->move(public_path('images/shop-v2-2/' . $this->bot->bot_domain), $filename);

            if (!is_null($story->thumbnail ?? null)) {
                $oldPath = public_path('images/shop-v2-2/' . $this->bot->bot_domain . "/" . $story->thumbnail);
                if (file_exists($oldPath))
                    unlink($oldPath);
            }

            $story->thumbnail = '/images/shop-v2-2/'.$this->bot->bot_domain . "/" . $filename;

            $filename = time() . '_' . $image[0]->getClientOriginalName();
            $image[0]->move(public_path('images/shop-v2-2/' . $this->bot->bot_domain), $filename);

            if (!is_null($story->image ?? null)) {
                $oldPath = public_path('images/shop-v2-2/' . $this->bot->bot_domain . "/" . $story->image);
                if (file_exists($oldPath))
                    unlink($oldPath);
            }
            $story->image = '/images/shop-v2-2/'.$this->bot->bot_domain . "/" . $filename;

            $story->save();
        }


        return new StoryResource($story);
    }

    /**
     * Удаление истории
     */
    public function destroy(int $storyId): StoryResource
    {
        $story = Story::query()->find($storyId);

        if (is_null($story)) {
            throw new HttpException(404, "История не найдена.");
        }

        $story->delete();

        return new StoryResource($story);
    }
}
