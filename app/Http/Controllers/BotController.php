<?php

namespace App\Http\Controllers;

use App\Http\Requests\BotStoreRequest;
use App\Http\Requests\BotUpdateRequest;
use App\Http\Resources\BotCollection;
use App\Http\Resources\BotMenuSlugResource;
use App\Http\Resources\BotMenuTemplateResource;
use App\Http\Resources\BotResource;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\ImageMenuResource;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotType;
use App\Models\Company;
use App\Models\ImageMenu;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BotController extends Controller
{


    public function loadBotsAsTemplate(Request $request)
    {
        $bots = Bot::query()
            ->select("bot_domain", "id")
            ->get();

        return response()->json($bots->toArray());
    }

    public function loadKeyboards(Request $request, $botId)
    {
        $keyboards = BotMenuTemplate::query()
            ->where("bot_id", $botId)
            ->get();

        return response()->json(BotMenuTemplateResource::collection($keyboards));
    }

    public function loadSlugs(Request $request, $botId)
    {
        $slugs = BotMenuSlug::query()
            ->where("bot_id", $botId)
            ->get();

        return response()->json(BotMenuSlugResource::collection($slugs));
    }

    public function index(Request $request): Response
    {
        $bots = Bot::all();

        return new BotCollection($bots);
    }

    public function store(BotStoreRequest $request): Response
    {
        $bot = Bot::create($request->validated());

        return new BotResource($bot);
    }

    public function show(Request $request, Bot $bot): Response
    {
        return new BotResource($bot);
    }

    public function update(BotUpdateRequest $request, Bot $bot): Response
    {
        $bot->update($request->validated());

        return new BotResource($bot);
    }

    public function destroy(Request $request, Bot $bot): Response
    {
        $bot->delete();

        return response()->noContent();
    }

    public function createCompany(Request $request)
    {
        $request->validate([
            'title' => "required|string:255",
            'slug' => "required|string:190|unique:companies,slug",
            'description' => "required|string:255",
            'address' => "required|string:255",
            'email' => "required|string:255",
        ]);

        $creatorId = Auth::user()->id ?? null;

        $imageName = null;
        if ($request->hasFile('company_logo')) {

            $file = $request->file('company_logo');

            $ext = $file->getClientOriginalExtension();

            $imageName = Str::uuid() . "." . $ext;

            $file->storeAs("/public/companies/$request->slug/$imageName");

        }

        $tmp = (object)$request->all();


        $tmp->links = json_decode($tmp->links);
        $tmp->schedule = json_decode($tmp->schedule);
        $tmp->phones = json_decode($tmp->phones);
        $tmp->image = $imageName;
        $tmp->creator_id = $creatorId;
        $tmp->owner_id = $creatorId;

        $company = Company::query()->create((array)$tmp);

        return new CompanyResource($company);
    }

    public function createLocation(Request $request): Response
    {
        $request->validate([
            "lat" => "required",
            "lon" => "required",
            "address" => "required",
            "description" => "required",
            "location_channel" => "required",
            "company_id" => "required"
        ]);


        $company = Company::query()->where("id", $request->company_id)
            ->first();

        if (is_null($company))
            return response()->noContent(400);

        $photos = [];

        if ($request->hasFile('images')) {
            $files = $request->file('images');

            foreach ($files as $key => $file) {
                $ext = $file->getClientOriginalExtension();

                $imageName = Str::uuid() . "." . $ext;

                $file->storeAs("/public/companies/$company->slug/$imageName");
                array_push($photos, $imageName);
            }
        }

        $tmp = (object)$request->all();

        $tmp->images = $photos;
        $tmp->is_active = true;
        $tmp->can_booking = $request->can_booking == "true" ? true : false;

        Location::query()->create((array)$tmp);

        return response()->noContent();
    }

    public function createImageMenu(Request $request){

        $request->validate([
            'title' => "required|string:255",
            'description' => "required|string:255",
            'bot_id' => "required",
        ]);

        $bot = Bot::query()
            ->with(["company"])
            ->where("id", $request->bot_id)
            ->first();

        if (is_null($bot))
            return response()->noContent(400);

        $imageName = null;
        if ($request->hasFile('preview')) {

            $file = $request->file('preview');

            $ext = $file->getClientOriginalExtension();

            $imageName = Str::uuid() . "." . $ext;

            $companySlug = $bot->company->slug;
            $file->storeAs("/public/companies/$companySlug/$imageName");

        }

        $tmp = (object)$request->all();

        $tmp->image = $imageName;

        $imgMenu = ImageMenu::query()->create((array)$tmp);

        return new ImageMenuResource($imgMenu);

    }

    public function createBot(Request $request)
    {
        $request->validate([
            "bot_domain" => "required|unique:bots,bot_domain",
            "bot_token" => "required",
            "bot_token_dev" => "required",
            "order_channel" => "required",
            "main_channel" => "required",
            "balance" => "required",
            "tax_per_day" => "required",
            "description" => "required",

            "social_links" => "required",
            "maintenance_message" => "required",
            "level_1" => "required",
            "selected_bot_template_id" => "required",
            "slugs" => "required",
            "keyboards" => "required",
            "company_id" => "required",
        ]);


        $company = Company::query()->where("id", $request->company_id)
            ->first();

        if (is_null($company))
            return response()->noContent(400);

        $photos = [];

        if ($request->hasFile('images')) {
            $files = $request->file('images');

            foreach ($files as $key => $file) {
                $ext = $file->getClientOriginalExtension();

                $imageName = Str::uuid() . "." . $ext;

                $file->storeAs("/public/companies/$company->slug/$imageName");
                array_push($photos, $imageName);
            }
        }

        $botType = BotType::query()->where("slug","restaurant")->first();

        $tmp = (object)$request->all();

        $tmp->image = is_null($photos) ? null : ($photos[0] ?? null);
        $tmp->level_2 = $request->level_2 ?? 0;
        $tmp->level_3 = $request->level_3 ?? 0;
        $tmp->bot_type_id = $botType->id;

        $tmp->social_links = json_decode($tmp->social_links??'[]');

        $keyboards = json_decode($request->keyboards);
        unset($tmp->keyboards);
        $slugs = json_decode($request->slugs);
        unset($tmp->slugs);
        unset($tmp->selected_bot_template_id);

        //dd($tmp);
        $bot = Bot::query()->create((array)$tmp);

        foreach ($slugs as $slug)
            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => $slug->command,
                'slug' => $slug->slug,
            ]);

        foreach ($keyboards as $keyboard)
            BotMenuTemplate::query()->create([
                'bot_id' => $bot->id,
                'type' => $keyboard->type,
                'slug' => $keyboard->slug,
                'menu' => $keyboard->menu,
            ]);

        return new BotResource($bot);
    }

}
