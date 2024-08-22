<?php

use App\Http\Controllers\Bots\Web\AdminBotController;
use App\Http\Controllers\Bots\Web\AmoCrmController;
use App\Http\Controllers\Bots\Web\BotController;
use App\Http\Controllers\Bots\Web\BotDialogsController;
use App\Http\Controllers\Bots\Web\BotMenuSlugController;
use App\Http\Controllers\Bots\Web\BotPageController;
use App\Http\Controllers\Bots\Web\BotUsersController;
use App\Http\Controllers\Bots\Web\CompanyController;
use App\Http\Controllers\Bots\Web\ProductController;
use App\Http\Controllers\Bots\Web\QueueController;
use App\Http\Controllers\Bots\Web\YClientsController;
use App\Http\Controllers\Globals\AboutBotScriptController;
use App\Http\Controllers\Globals\BonusProductScriptController;
use App\Http\Controllers\Globals\InstagramQuestScriptController;
use App\Http\Controllers\Globals\ProfileFormScriptController;
use App\Http\Controllers\Globals\ShopScriptController;
use App\Http\Controllers\Globals\WheelOfFortuneCustomScriptController;
use App\Http\Controllers\Globals\WheelOfFortuneScriptController;
use Illuminate\Support\Facades\Route;


Route::prefix("bot-client")
    ->group(function () {

        Route::post("/vk-auth-link", [\App\Http\Controllers\Globals\VKProductController::class, "getVKAuthLink"])
            ->middleware(["tgAuth.admin"]);

        Route::any("/vk-callback", [\App\Http\Controllers\Globals\VKProductController::class, "callback"]);

        Route::post("/send-to-channel", [BotController::class, "sendToChannel"])
            ->middleware(["tgAuth.any"]);

        /*Route::post("/send-to-queue", [BotController::class, "sendToQueue"])
            ->middleware(["tgAuth.any"]);*/

        Route::post("/telegram-channel-id", [BotController::class, "requestTelegramChannel"])
            ->middleware(["tgAuth.any"]);

        Route::post("/manager-notes", [BotController::class, "requestManagerNotes"])
            ->middleware(["tgAuth.any"]);


        Route::post('/self', [BotController::class, "getSelf"])
            ->middleware(["tgAuth.any"]);

        Route::post('/bot', [BotController::class, "getBot"])
            ->middleware(["tgAuth.admin"]);

        Route::post('/manage-bot', [BotController::class, "getManagerBot"])
            ->middleware(["tgAuth.manager"]);


        Route::post('/callback', [BotController::class, "sendCallback"])
            ->middleware(["tgAuth.any", "slug"]);

        Route::post('/feedback', [BotController::class, "sendFeedback"])
            ->middleware(["tgAuth.any"]);

        Route::prefix("wheel-of-fortune")
            ->controller(WheelOfFortuneScriptController::class)
            ->middleware(["tgAuth.any", "slug"])
            ->group(function () {
                Route::post('/prepare', "formWheelOfFortunePrepare");
                Route::post('/load-data', "loadData");
                Route::post('/callback', "formWheelOfFortuneCallback");
            });

        Route::prefix("mailing")
            ->controller(QueueController::class)
            ->middleware(["tgAuth.admin"])
            ->group(function () {
                Route::post('/', "list");
                Route::post('/send-to-queue', "store");
                Route::delete('/remove/{id}', "remove");
            });

        Route::prefix("wheel-of-fortune-custom")
            ->controller(WheelOfFortuneCustomScriptController::class)
            ->middleware(["tgAuth.any", "slug"])
            ->group(function () {
                Route::post('/prepare', "formWheelOfFortunePrepare");
                Route::post('/load-data', "loadData");
                Route::post('/store-params', "storeParams")->middleware(["tgAuth.admin"]);
                Route::post('/callback', "formWheelOfFortuneCallback");
            });

        Route::prefix("friends-game")
            ->controller(\App\Http\Controllers\Globals\FriendsGameScriptController::class)
            ->middleware(["tgAuth.any", "slug"])
            ->group(function () {
                Route::post('/start-game', "startFriendsGame");
                Route::post('/prepare', "friendsGamePrepare");
                Route::post('/finish-game', "finishGame");
            });

        Route::prefix("media")
            ->controller(\App\Http\Controllers\Bots\MediaController::class)
            ->middleware(["tgAuth.admin"])
            ->group(function () {
                Route::post('/', "media");
                Route::get('/preview/{id}', "preview");
                Route::delete('/remove/{id}', "remove");
            });

        Route::prefix("chat-history")
            ->controller(\App\Http\Controllers\Bots\Web\ChatLogController::class)
            ->middleware(["tgAuth.admin"])
            ->group(function () {
                Route::post('/', "history");
            });


        Route::prefix("quizzes")
            ->controller(\App\Http\Controllers\Globals\QuizScriptController::class)
            ->middleware(["tgAuth.any", "slug"])
            ->group(function () {
                Route::post("/quiz-command-store", "quizCommandStore");
                Route::post('/list-of-quiz', "loadQuizList");
                Route::post('/check-answer', "checkAnswers");
                Route::post('/check-all-answers', "checkAllAnswers");
                Route::post('/quiz-complete', "completeQuiz");
                Route::post('/start-quiz', "startQuiz");
                Route::post('/check-quiz-command', "checkQuizCommand");
                Route::post('/load-single-quiz', "loadSingleQuiz");
                Route::post("/list-of-quiz-commands/{quizId}", "listOfQuizCommands");
                Route::post('/list-of-results/{quizId}', "loadQuizResultList");
                Route::post('/list-of-quiz-questions/{quizId}', "listOfQuizQuestions");

                /*   Route::post('/check', "check")
                       ->middleware(["tgAuth.admin"]);
                   Route::post('/exchange', "exchange")
                       ->middleware(["tgAuth.admin"]);
                   Route::post('/load-action-data', "loadActionData")
                       ->middleware(["tgAuth.admin"]);*/
            });

        Route::prefix("bonus-product")
            ->controller(BonusProductScriptController::class)
            ->middleware(["tgAuth.any", "slug"])
            ->group(function () {
                Route::post('/prepare', "prepare");
                Route::post('/check', "check")
                    ->middleware(["tgAuth.admin"]);
                Route::post('/exchange', "exchange")
                    ->middleware(["tgAuth.admin"]);
                Route::post('/load-action-data', "loadActionData")
                    ->middleware(["tgAuth.admin"]);
            });

        Route::prefix("instagram-quest")
            ->controller(InstagramQuestScriptController::class)
            ->middleware(["tgAuth.any", "slug"])
            ->group(function () {
                Route::post('/load-data', "loadData");
                Route::post('/prepare', "instagramQuestPrepare");
                Route::post('/callback', "instagramQuestCallback");
            });

        Route::prefix("schedule")
            ->controller(\App\Http\Controllers\Globals\ScheduleBotScriptController::class)
            ->middleware(["tgAuth.any", "slug"])
            ->group(function () {
                Route::post('/load-data', "loadData");
            });

        Route::prefix("about-bot")
            ->controller(AboutBotScriptController::class)
            ->group(function () {
                Route::get('/callback/{botDomain}', "callbackFormGet");
                Route::post('/callback/{botDomain}', "callbackFormPost");
            });

        Route::prefix("companies")
            ->controller(CompanyController::class)
            ->middleware(["tgAuth.admin"])
            ->group(function () {
                Route::post("/", "index");
                Route::post("/company-update", "editCompany");
                Route::post("/company", "loadCompany");
                Route::post("/location-list", "loadLocations");
                Route::post("/location", "createLocation");
            });

        Route::prefix("companies")
            ->controller(CompanyController::class)
            ->middleware(["tgAuth.manager"])
            ->group(function () {
                Route::post("/manager-companies-list", "managerCompaniesList");
                Route::delete("/{companyId}", "destroy");

            });

        Route::prefix("payments")
            ->controller(\App\Http\Controllers\Globals\PaymentSBPScriptController::class)
            ->group(function () {
                Route::get("/providers", "getProviders");

            });

        Route::prefix("promocodes")
            ->middleware(["tgAuth.any"])
            ->controller(\App\Http\Controllers\Globals\PromocodeScriptController::class)
            ->group(function () {
                Route::post("/", "list")->middleware(["tgAuth.admin"]);
                Route::post("/store", "store")->middleware(["tgAuth.admin"]);
                Route::post("/activate", "activate")
                    ->middleware(["slug"]);
                Route::post("/activate-shop-discount", "activateShopDiscount");
                Route::delete("/{id}", "remove")->middleware(["tgAuth.admin"]);
            });

        Route::prefix("appointments")
            ->controller(\App\Http\Controllers\Bots\Web\AppointmentController::class)
            ->middleware(["tgAuth.any"])
            ->group(function () {
                Route::post("/event-list", "eventList");
                Route::post("/schedule-list/{eventId}", "scheduleList");

                Route::post("/store-review", "storeReview");
                Route::post("/review-list/{eventId}", "reviewList");
                Route::delete("/remove-review/{reviewId}", "removeReview");

                Route::post("/service-category-list/{eventId}", "serviceCategoryList");
                Route::post("/service-list/{eventId}", "serviceList");

                Route::post("/appointment-list/{eventId}", "appointmentList");
                Route::post("/store-appointment", "storeAppointment");
                Route::delete("/remove-appointment", "removeAppointment");
            });

        Route::prefix("shop")
            ->middleware(["tgAuth.any"])
            ->group(function () {

                Route::prefix("wheel-of-fortune-v3")
                    ->controller(\App\Http\Controllers\Globals\SimpleDeliveryController::class)
                    ->middleware(["tgAuth.any", "slug"])
                    ->group(function () {
                        Route::post('/prepare', "formWheelOfFortuneV3Prepare");
                        Route::post('/callback', "formWheelOfFortuneV3Callback");
                    });

                Route::prefix("orders")
                    ->group(function () {
                        Route::post("/", [ProductController::class, "getOrders"]);
                        Route::post("/all", [ProductController::class, "getAllOrders"])->middleware(["tgAuth.admin"]);
                        Route::post("/repeat-order", [ProductController::class, "repeatOrder"]);
                        Route::post("/add-cashback-to-order", [ProductController::class, "addCashBackToOrder"])->middleware(["tgAuth.admin"]);
                        Route::post("/get-delivery-price", [ProductController::class, "getDeliveryPrice"])
                            ->middleware(["slug"]);
                    });

                Route::prefix("reviews")
                    ->group(function () {
                        Route::post("/", [ProductController::class, "getReviews"]);
                        Route::post("/by-product-id", [ProductController::class, "getReviewsByProductId"]);
                        Route::post("/store-review", [ProductController::class, "storeReview"]);
                        Route::post("/notify-user", [ProductController::class, "notifyUser"]);


                    });

                Route::post("/products", [ProductController::class, "index"]);
                Route::post("/products-by-category", [ProductController::class, "listByCategories"]);
                Route::post("/products/load-data", [\App\Http\Controllers\Globals\SimpleDeliveryController::class, "loadData"])
                    ->middleware(["slug"]);
                Route::post("/checkout", [ProductController::class, "checkout"]);

                Route::post("/checkout-instruction", [ProductController::class, "checkoutInstruction"])
                    ->middleware(["slug"]);
                Route::post("/checkout-link", [ProductController::class, "createCheckoutLink"])
                    ->middleware(["slug"]);
                Route::post("/products/store-category", [ProductController::class, "storeCategory"]);
                Route::post("/products/by-ids", [ProductController::class, "getProductsByIds"]);
                Route::post("/products/random", [ProductController::class, "randomProducts"]);
                Route::post("/products/categories", [ProductController::class, "getCategories"]);
                Route::post("/products/add-product", [ProductController::class, "saveProduct"]);
                Route::post("/products/remove-all-products", [ProductController::class, "removeAllProducts"]);
                Route::delete("/products/remove-category/{categoryId}", [ProductController::class, "removeCategoryId"]);
                Route::post("/products/categories/status/{id}", [ProductController::class, "changeCategoryStatus"]);
                Route::post("/products/add-category", [ProductController::class, "storeCategory"]);
                Route::post("/products/in-category", [ProductController::class, "getProductsInCategory"]);
                Route::post("/products/category/{productId}", [ProductController::class, "getCategory"]);
                Route::post("/products/{productId}", [ProductController::class, "getProduct"]);
            });

        Route::prefix("iiko")
            ->controller(\App\Http\Controllers\Bots\Web\IikoController::class)
            ->middleware(["tgAuth.admin"])
            ->group(function(){
                Route::post('/', "index");
                Route::post('/token', "getToken");
                Route::post('/organizations', "getOrganizations");
                Route::post('/terminals', "getTerminals");
                Route::post('/menu', "getMenu");
                Route::post('/products', "getProducts");
                Route::post('/store', "store");
            });

        Route::prefix("admins")
            ->controller(AdminBotController::class)
            ->group(function () {
                Route::post('/', "loadActiveAdminList")
                    ->middleware(["tgAuth.any"]);
                Route::post('/request', "requestCashBack")
                    ->middleware(["tgAuth.any"]);

                Route::post('/send-invoice', "sendInvoice")
                    ->middleware(["tgAuth.admin"]);

                Route::post('/send-page-to-user', "sendPageToUser")
                    ->middleware(["tgAuth.admin"]);


                Route::post('/add', "addAdmin")
                    ->middleware(["tgAuth.admin"]);
                Route::post('/send-approve', "sendApprove")
                    ->middleware(["tgAuth.admin"]);

                Route::post('/remove', "removeAdmin")
                    ->middleware(["tgAuth.admin"]);
                Route::post('/self-remove', "selfRemoveAdmin")
                    ->middleware(["tgAuth.admin"]);
                Route::post('/work-status', "workStatus")
                    ->middleware(["tgAuth.admin"]);

                Route::post('/load-statistic', "statistic")
                    ->middleware(["tgAuth.admin"]);
                Route::post('/download-bot-statistic', "exportBotStatistic")
                    ->middleware(["tgAuth.admin"]);
                Route::post('/download-bot-users', "exportBotUsers")
                    ->middleware(["tgAuth.admin"]);
                Route::post('/download-cashback-history', "exportCashBackHistory")
                    ->middleware(["tgAuth.admin"]);
            });

        Route::prefix("actions")
            ->controller(\App\Http\Controllers\Admin\BotUsersController::class)
            ->group(function () {
                Route::post("/history", "loadActionStatusHistories")
                    ->middleware(["tgAuth.admin"]);

            });

        Route::prefix("users")
            ->controller(\App\Http\Controllers\Admin\BotUsersController::class)
            ->group(function () {
                Route::post("/search", "loadBotUsers")
                    ->middleware(["tgAuth.admin"]);

            });

        Route::prefix("bot-users")
            ->controller(BotUsersController::class)
            ->group(function () {
                Route::post("/update-bot-user", "updateBotUser")
                    ->middleware(["tgAuth.admin"]);
                Route::post("/update-profile", "updateProfile")
                    ->middleware(["tgAuth.any"]);
                Route::post("/get-user-profile-photos", "getUserProfilePhotos")
                    ->middleware(["tgAuth.any"]);
            });


        Route::prefix("profile-form")
            ->middleware(["tgAuth.any"])
            ->controller(ProfileFormScriptController::class)
            ->group(function () {
                Route::post("/load-profile-data", "loadProfileFormData")
                    ->middleware(["slug"]);
                Route::post("/store-profile-data", "updateProfileFormData")
                    ->middleware(["slug"]);
            });

        Route::prefix("cashback")
            ->middleware(["tgAuth.any"])
            ->group(function () {
                Route::post('/receiver', [\App\Http\Controllers\Admin\CashBackHistoryController::class, "receiver"]);
                Route::post('/history', [\App\Http\Controllers\Admin\CashBackHistoryController::class, "index"]);
                Route::post('/add', [\App\Http\Controllers\Bots\Web\AdminBotController::class, "addCashBack"])
                    ->middleware(["tgAuth.admin"]);
                Route::post('/remove', [\App\Http\Controllers\Bots\Web\AdminBotController::class, "removeCashBack"])
                    ->middleware(["tgAuth.admin"]);
                Route::post('/vip', [\App\Http\Controllers\Bots\Web\AdminBotController::class, "vipStore"])
                    ->middleware(["slug"]);
                Route::post('/user-message', [\App\Http\Controllers\Bots\Web\AdminBotController::class, "messageToUser"])
                    ->middleware(["tgAuth.admin"]);
                Route::post('/request-user-data', [\App\Http\Controllers\Bots\Web\AdminBotController::class, "requestUserData"])
                    ->middleware(["tgAuth.admin"]);
                Route::post('/request-refresh-menu', [\App\Http\Controllers\Bots\Web\AdminBotController::class, "requestRefreshMenu"])
                    ->middleware(["tgAuth.admin"]);
                Route::post('/load-data', [\App\Http\Controllers\Globals\CashBackScriptController::class, "loadData"])
                    ->middleware(["slug"]);
            });

        Route::prefix("cash-out")
            ->middleware(["tgAuth.any"])
            ->group(function () {
                Route::post('/withdraw-money', [\App\Http\Controllers\Globals\RequestMoneyWithdrawScriptController::class, "withDrawMoney"])
                    ->middleware(["slug"]);
            });

        Route::prefix("pages")
            ->controller(BotPageController::class)
            ->middleware(["tgAuth.admin"])
            ->group(function () {
                Route::post("/", "index");
                Route::post("/page", "createPage");
                Route::post("/page-update", "updatePage");
                Route::post("/duplicate/{pageId}", "duplicate");
                Route::post("/remove/{pageId}", "destroy");
            });

        Route::prefix("bots")
            ->controller(BotController::class)
            ->middleware(["tgAuth.admin"])
            ->group(function () {
                Route::post("/", "index");
                Route::post("/save-amo", [AmoCrmController::class, "saveAmoCrm"]);
                Route::post("/save-y-clients", [YClientsController::class, "saveYClients"]);
                Route::post("/load-amo-fields", [AmoCrmController::class, "loadAmoFields"]);
                Route::post("/sync-amo", [AmoCrmController::class, "syncAmoCrm"]);
                Route::post("/bot-update", "updateBot");
                Route::post("/bot-params-update", "updateBotParams");
                Route::post("/user-status", "changeUserStatus");
                Route::post("/users", "loadBotUsers");
                Route::post("/image-menu", "loadImageMenu");
                Route::post("/slugs", "loadSlugs");
                Route::post("/duplicate", "duplicate");
                Route::post("/keyboards", "loadKeyboards");
                Route::post("/keyboard-template", "createKeyboardTemplate");
                Route::post("/remove-keyboard-template/{keyboardId}", "removeKeyboardTemplate");
                Route::post("/edit-keyboard-template", "editKeyboardTemplate");
                Route::post('/switch-status', "switchBotStatus");
                Route::post('/update-shop-link', "updateShopLink");
                Route::post("/restore/{botId}", "restore");

            });

        Route::prefix("manager")
            ->controller(\App\Http\Controllers\Bots\Web\ManagerProfileController::class)
            ->middleware(["tgAuth.any"])
            ->group(function () {
                Route::post("/register", "registerManager");
                Route::post('/load-data', [\App\Http\Controllers\Globals\ManagerScriptController::class, "loadData"]);
                Route::post('/friends-web', [\App\Http\Controllers\Globals\ManagerScriptController::class, "getFriendList"]);
            });

        Route::prefix("bots")
            ->controller(BotController::class)
            ->middleware(["tgAuth.any"])
            ->group(function () {
                Route::post("/simple-bot-list", "simpleList")
                    ->middleware(["tgAuth.manager"]);
                Route::post("/bot-lazy", "createBotLazy")
                    ->middleware(["tgAuth.manager"]);
                Route::post("/store-fields", "storeBotFields")
                    ->middleware(["tgAuth.admin"]);
                Route::get("/load-fields", "loadBotFields");
                Route::post('/manager-switch-status', "switchBotStatusManager")
                    ->middleware(["tgAuth.manager"]);
                Route::post("/manager-bot-update", "updateBotByManager")
                    ->middleware(["tgAuth.manager"]);
                Route::delete("/remove-my-manager/{botId}", "destroyByManager")
                    ->middleware(["tgAuth.manager"]);
            });

        Route::prefix("slugs")
            ->controller(BotMenuSlugController::class)
            ->middleware(["tgAuth.admin"])
            ->group(function () {
                Route::post("/", "index");
                Route::post("/global-list", "globalList");

                Route::post("/slug", "createSlug");
                Route::post("/slug-update", "updateSlug");
                Route::post("/slug-script-params", "updateScriptParams")
                    ->middleware(["slug"]);
                Route::post("/duplicate/{slugId}", "duplicate");
                Route::get("/reload-params/{slugId}", "reloadParams");
                Route::delete("/{slugId}", "destroy");
            });

        Route::prefix("dialogs")
            ->middleware(["tgAuth.admin"])
            ->controller(BotDialogsController::class)
            ->group(function () {
                Route::post("/", "index");
            });

        Route::get("/simple/{botDomain}", [ShopScriptController::class, "simpleHomePage"])
            ->where("slug", "[0-9]+|route");

        Route::get("/{botDomain}", [ShopScriptController::class, "shopHomePage"])
            ->where("slug", "[0-9]+|route");


    });

Route::prefix("bot-manager-client")
    ->group(function () {
        Route::get("/{botDomain}", [\App\Http\Controllers\Globals\ManagerScriptController::class, "managerHomePage"])
            ->where("slug", "[0-9]+|route");
    });
