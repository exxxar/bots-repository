<?php

use App\Http\Controllers\Admin\BotController;
use App\Http\Controllers\Admin\BotPageController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\AmoCrmController;
use App\Http\Controllers\Bots\AdminBotController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/dashboard', function () {
            Inertia::setRootView("app");
            return Inertia::render('MainPage');
        })
            ->name('dashboard');

        Route::get('/company-page', function () {
            Inertia::setRootView("app");

            return Inertia::render('CompanyPage');
        })->name('company-page');

        Route::get('/media-page', function () {
            Inertia::setRootView("app");

            $files = Storage::disk('public')->allFiles("/companies");

            return Inertia::render('MediaPage', [
                "files" => $files
            ]);
        })->name('media-page');


        Route::get('/user-page', function () {
            Inertia::setRootView("app");

            return Inertia::render('UserPage');
        })->name('user-page');

        Route::get('/mail-page', function () {
            Inertia::setRootView("app");

            return Inertia::render('MailPage');
        })->name('mail-page');

        Route::get('/bot-page', function () {
            Inertia::setRootView("app");

            return Inertia::render('BotPage');
        })->name('bot-page');

        Route::get('/script-page', function () {
            Inertia::setRootView("app");
            return Inertia::render('ScriptPage');
        })->name('script-page');

        Route::get('/visit-card-page', function () {
            Inertia::setRootView("app");
            return Inertia::render('BotVisitCardConstructorPage');
        })->name('visit-card-page');


        Route::post("/vk-auth-link", [\App\Http\Controllers\Globals\VKProductController::class, "getVKAuthLink"]);
        Route::get("/vk-callback", [\App\Http\Controllers\Globals\VKProductController::class, "callback"]);

    });


Route::prefix("admin")
    ->middleware(['auth', 'verified'])
    ->group(function () {

        Route::prefix("bots")
            ->controller(BotController::class)
            ->group(function () {
                Route::post("/", "index");
                Route::post("/save-amo", [AmoCrmController::class, "saveAmoCrm"]);
                Route::post("/bot-update", "updateBot");
                Route::post("/user-status", "changeUserStatus");
                Route::post("/users", "loadBotUsers");
                Route::post("/current-bot-user", "getCurrentBotUser");
                Route::delete("/{botId}", "destroy");
                Route::get("/restore/{botId}", "restore");
            });

        Route::prefix("dialog-groups")
            ->controller(\App\Http\Controllers\Admin\BotDialogGroupController::class)
            ->group(function () {
                Route::post("/", "index");
                Route::post("/swap-group", "swapGroup");
                Route::post("/swap-dialog", "swapDialog");
                Route::post("/attach-dialog-to-slug", "attachDialogToSlug");
                Route::post("/unlink-dialog", "unlinkDialog");
                Route::post("/add-group", "addGroup");
                Route::post("/add-dialog", "addDialog");
                Route::post("/duplicate-dialog", "duplicateDialog");
                Route::post("/stop-dialogs", "stopDialogs");
                Route::post("/update-group", "updateGroup");
                Route::post("/update-dialog", "updateDialog");
                Route::delete("/remove-group/{groupId}", "removeGroup");
                Route::delete("/remove-dialog/{dialogId}", "removeDialog");
            });


        Route::prefix("templates")
            ->controller(BotController::class)
            ->group(function () {
                Route::get("/bots", "loadBotsAsTemplate");

                Route::get("/slugs", "loadAllSlugs");
                Route::get("/description", "loadDescriptions");
                Route::post("/telegram-channel-id", "requestTelegramChannel");
                Route::get("/keyboards/{botId}", "loadKeyboards");
                Route::post("/keyboards/{botId}", "loadKeyboardsByText");
                Route::post("/keyboard-template", "createKeyboardTemplate");
                Route::post("/edit-keyboard-template", "editKeyboardTemplate");
                Route::delete("/remove-keyboard-template/{templateId}", "removeKeyboardTemplate");
                Route::get("/slugs/{botId}", "loadSlugs");
                Route::get("/pages/{botId}", "loadPages");

                Route::post("/location", "createLocation");
                Route::get("/location/{companyId}", "loadLocations");
                Route::get("/image-menu/{botId}", "loadImageMenu");
                Route::post("/bot", "createBot");
                Route::post("/bot-lazy", "createBotLazy");
                Route::post("/image-menu", "createImageMenu");
            });

        Route::prefix("companies")
            ->controller(CompanyController::class)
            ->group(function () {
                Route::post("/", "index");
                Route::post("/company", "createCompany");
                Route::post("/company-update", "editCompany");
                Route::delete("/{companyId}", "destroy");
                Route::get("/restore/{companyId}", "restore");
            });

        Route::prefix("slugs")
            ->controller(\App\Http\Controllers\Admin\BotMenuSlugController::class)
            ->group(function () {
                Route::post("/", "index");
                Route::post("/slug", "createSlug");
                Route::post("/slug-update", "updateSlug");
                Route::post("/duplicate/{slugId}", "duplicate");
                Route::get("/reload-params/{slugId}", "reloadParams");
                Route::delete("/{slugId}", "destroy");
            });

        Route::prefix("pages")
            ->controller(BotPageController::class)
            ->group(function () {
                Route::post("/", "index");
                Route::post("/page", "createPage");
                Route::post("/page-update", "updatePage");
                Route::post("/duplicate/{pageId}", "duplicate");
                Route::delete("/{pageId}", "destroy");
            });


        Route::prefix("users")
            ->controller(\App\Http\Controllers\Admin\BotUsersController::class)
            ->group(function () {
                Route::post("/search", "loadBotUsers");

            });

        Route::prefix("shop")
            ->group(function () {

                Route::post("/products", [\App\Http\Controllers\Admin\ProductController::class, "index"]);
            });

        Route::any('/register-webhooks', [\App\Http\Controllers\Admin\TelegramController::class, "registerWebhooks"]);
        Route::any('/{domain}', [\App\Http\Controllers\Admin\TelegramController::class, "handler"]);
    });