<?php

use App\Http\Controllers\Admin\BotController;
use App\Http\Controllers\Admin\BotPageController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\YClientsController;
use App\Http\Controllers\AmoCrmController;
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

        Route::get('/manager-page', function () {
            Inertia::setRootView("app");

            return Inertia::render('ManagerMainPage');
        })->name('manager-main-page');

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



    });

Route::post("/send-to-channel", [\App\Http\Controllers\Admin\BotController::class, "sendToChannel"]);



Route::prefix("admin")
    ->middleware(['auth', 'verified'])
    ->group(function () {

        Route::post("/vk-auth-link", [\App\Http\Controllers\Globals\VKProductController::class, "getVKAuthLink"]);

        Route::prefix("bots")
            ->controller(BotController::class)
            ->group(function () {
                Route::post("/", "index");
                Route::post('/get-me',"getMe");
                Route::post("/save-y-clients", [YClientsController::class, "saveYClients"]);
                Route::post('/update-shop-link',"updateShopLink");
                Route::post("/save-amo", [AmoCrmController::class, "saveAmoCrm"]);
                Route::post("/bot-update", "updateBot");
                Route::post("/bot-webhook-update", "updateWebhook");
                Route::post("/user-status", "changeUserStatus");
                Route::post("/users", "loadBotUsers");
                Route::post("/store-fields", "storeBotFields");
                Route::get("/load-fields/{botId}", "loadBotFields");
                Route::post("/load-chat-info", "loadChatInfo");
                Route::post("/create-bot-topics", "createBotTopics");
                Route::post("/duplicate", "duplicate");
                Route::delete("/force/{botId}", "forceDelete");
                Route::delete("/{botId}", "destroy");
                Route::get("/restore/{botId}", "restore");
            });

        Route::prefix("media")
            ->controller(\App\Http\Controllers\Admin\MediaController::class)
            ->group(function () {
                Route::post('/', "media");
                Route::get('/preview/{id}', "preview");
                Route::delete('/remove/{id}', "remove");
            });

        Route::prefix("appointments")
            ->controller(\App\Http\Controllers\Admin\AppointmentController::class)
            ->group(function () {
                Route::post("/event-list", "eventList");
                Route::post("/add-event", "addEvent");
                Route::post("/duplicate-event/{id}", "duplicateEvent");
                Route::put("/update-event", "updateEvent");
                Route::delete("/remove-event/{id}", "removeEvent");
                Route::delete("/force-remove-event/{id}", "forceRemoveEvent");
                Route::get("/restore-event/{id}", "restoreEvent");

                Route::post("/time-list/{eventId}", "timeList");
                Route::post("/add-time", "addTime");
                Route::put("/update-time", "updateTime");
                Route::delete("/remove-time", "removeTime");

                Route::post("/service-list/{eventId}", "serviceList");
                Route::post("/add-service", "addService");
                Route::put("/update-service", "updateService");
                Route::delete("/remove-service", "removeService");

                Route::post("/appointment-list/{eventId?}", "appointmentList");
                Route::post("/add-appointment", "addAppointment");
                Route::put("/update-appointment", "updateAppointment");
                Route::delete("/remove-appointment", "removeAppointment");
            });

        Route::prefix("dialog-groups")
            ->controller(\App\Http\Controllers\Admin\BotDialogGroupController::class)
            ->group(function () {
                Route::post("/", "index");
                Route::post("/commands", "commandList");
                Route::post("/swap-group", "swapGroup");
                Route::post("/swap-dialog", "swapDialog");
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

                Route::get("/description", "loadDescriptions");
                Route::post("/telegram-channel-id", "requestTelegramChannel");
                Route::get("/keyboards/{botId}", "loadKeyboards");

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
                Route::post("/global-list", "globalList");
                Route::post("/relocate-actions-data", "relocateData");
                Route::post("/slug", "createSlug");
                Route::post("/all-slugs/{botId}", "allSlugList");
                Route::post("/slug-update", "updateSlug");
                Route::post("/duplicate/{slugId}", "duplicate");
                Route::get("/reload-params/{slugId}", "reloadParams");
                Route::post("/reload-global-scripts", "reloadGlobalScripts");
                Route::get("/action-data-export/{slugId}", "actionDataExport");
                Route::delete("/{slugId}", "destroy");
                Route::get("/restore/{slugId}", "restore");
            });

        Route::prefix("pages")
            ->controller(BotPageController::class)
            ->group(function () {
                Route::post("/", "index");
                Route::post("/page", "createPage");
                Route::post("/page-update", "updatePage");
                Route::post("/duplicate/{pageId}", "duplicate");
                Route::get("/restore/{pageId}", "restorePage");
                Route::delete("/force/{pageId}", "forceDestroy");
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
                Route::post("/products/categories", [\App\Http\Controllers\Admin\ProductController::class, "getCategories"]);
                Route::post("/products/save", [\App\Http\Controllers\Admin\ProductController::class, "saveProduct"]);
                Route::delete("/products/remove/{productId}", [\App\Http\Controllers\Admin\ProductController::class, "destroy"]);
                Route::post("/products/duplicate/{productId}", [\App\Http\Controllers\Admin\ProductController::class, "duplicate"]);
            });

        Route::any('/register-webhooks', [\App\Http\Controllers\Admin\TelegramController::class, "registerWebhooks"]);
        Route::any('/{domain}', [\App\Http\Controllers\Admin\TelegramController::class, "handler"]);
    });
