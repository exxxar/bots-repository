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
use App\Http\Controllers\StoryController;
use Illuminate\Support\Facades\Route;


Route::prefix("bot-client")
    ->group(function () {

        Route::get("/{domain}/tables-qr", [ProductController::class, "generateTablesQR"]);

        Route::post("/vk-auth-link", [\App\Http\Controllers\Globals\VKProductController::class, "getVKAuthLink"])
            ->middleware(["tgAuth.admin"]);

        Route::any("/vk-callback", [\App\Http\Controllers\Globals\VKProductController::class, "callback"]);

        Route::post("/send-to-channel", [BotController::class, "sendToChannel"])
            ->middleware(["tgAuth.any"]);

        Route::post("/switch-to-main-menu", [BotController::class, "switchToMainMenu"])
            ->middleware(["tgAuth.any"]);

        Route::post("/switch-to-page", [BotController::class, "switchToPage"])
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

        Route::post('/upload-file', [BotController::class, "uploadFile"])
            ->middleware(["tgAuth.any"]);

        Route::prefix("wheel-of-fortune")
            ->controller(WheelOfFortuneScriptController::class)
            ->middleware(["tgAuth.any", "slug"])
            ->group(function () {
                Route::post('/prepare', "formWheelOfFortunePrepare");
                Route::post('/load-data', "loadData");
                Route::post('/callback', "formWheelOfFortuneCallback");
            });

        Route::prefix("tables")
            ->controller(\App\Http\Controllers\Bots\Web\TableController::class)
            ->middleware(["tgAuth.any", "slug"])
            ->group(function () {
                Route::post('/current', "currentTable");
                Route::post('/table-data', "loadTableData");
                Route::post('/waiter-tables', "waiterTableList");
                Route::post('/close-table', "closeTable");
                Route::post('/table-pay', "tablePay");
                Route::post('/send-order-to-my-chat', "sendOrderToMyChat");
                Route::post('/change-table-waiter', "changeTableWaiter");
                Route::post('/accept-table-order', "changeBasketStatus");
                Route::post('/request-approve-table', "requestApproveTable");
                Route::post('/store-additional-service', "storeAdditionalService");
                Route::post('/self-checkout', "selfCheckout");
                Route::post('/approved-self-basket', "approvedSelfBasket");
                Route::post('/call-waiter', "callWaiter");
                Route::post('/all-orders', "getAllTableOrders");
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
                Route::post('/load-prizes-variants', "loadPrizesVariants");
                Route::post('/load-script-variants', "loadScriptVariants");
                Route::post('/store-params', "storeParams")->middleware(["tgAuth.admin"]);
                Route::post('/callback', "formWheelOfFortuneCallback");
            });


        Route::prefix("basket")
            ->controller(\App\Http\Controllers\Bots\Web\BasketController::class)
            ->middleware(["tgAuth.any"])
            ->group(function () {
                Route::post('/', "loadProductsInBasket");
                Route::post('/checkout', "checkout");
                Route::post('/checkout-link', "checkoutLink");
                Route::post('/increment/{id}', "incrementItem");
                Route::post('/decrement/{id}', "decrementItem");
                Route::post('/inc-product', "incProductInBasket");
                Route::post('/comment-product', "commentProductInBasket");
                Route::post('/dec-product', "decProductInBasket");
                Route::post('/inc-collection', "incCollectionInBasket");
                Route::post('/dec-collection', "decCollectionInBasket");
                Route::delete('/clear', "clearBasket");
                Route::delete('/remove/{id}', "removeBasketItem");

            });

        Route::prefix("friends-game")
            ->controller(\App\Http\Controllers\Globals\FriendsGameScriptController::class)
            ->middleware(["tgAuth.any", "slug"])
            ->group(function () {
                Route::post('/start-game', "startFriendsGame");
                Route::post('/prepare', "friendsGamePrepare");
                Route::post('/finish-game', "finishGame");
            });

        Route::prefix("friends")
            ->controller(\App\Http\Controllers\Bots\Web\BotUsersController::class)
            ->middleware(["tgAuth.any"])
            ->group(function () {
                Route::post('/', "loadFriendList");
            });

        Route::prefix("friends-script")
            ->controller(\App\Http\Controllers\Globals\FriendsScriptController::class)
            ->middleware(["tgAuth.any"])
            ->group(function () {
                Route::post('/load-script-variants', "loadScriptVariants");
                Route::post('/store', "store")->middleware(["slug"]);
                Route::delete('/remove/{id}', "destroy")->middleware(["slug"]);
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

        Route::prefix("request-photo")
            ->controller(\App\Http\Controllers\Globals\RequestPhotoScriptController::class)
            ->middleware(["tgAuth.any", "slug"])
            ->group(function () {
                Route::post('/load-data', "loadData");
                Route::post('/callback', "RequestPhotoCallback");
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
                Route::post("/company-law-update", "editLawParamsCompany");
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

        Route::prefix("stories")
            ->middleware(["tgAuth.any"])
            ->group(function () {
                Route::get("/", [App\Http\Controllers\Bots\Web\StoryController::class, "index"]); // Получить список историй
                Route::get("/{id}", [App\Http\Controllers\Bots\Web\StoryController::class, "show"]); // Получить историю по ID
                Route::post("/", [App\Http\Controllers\Bots\Web\StoryController::class, "store"]); // Создать или обновить историю
                Route::delete("/{id}", [App\Http\Controllers\Bots\Web\StoryController::class, "destroy"]); // Удалить историю
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
                        Route::post("/send-sbp-invoice", [ProductController::class, "sendSBPInvoice"])
                            ->middleware(["slug"]);
                        Route::post("/all", [ProductController::class, "getAllOrders"])->middleware(["tgAuth.admin"]);
                        Route::post("/repeat-order", [ProductController::class, "repeatOrder"]);
                        Route::post("/decline-order", [ProductController::class, "declineOrder"]);
                        Route::post("/change-order-status", [ProductController::class, "changeStatusOrder"])
                            ->middleware(["tgAuth.admin"]);
                        Route::post("/get-order-by-id", [ProductController::class, "loadOrderById"]);
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
                Route::post("/products/export-all-products", [ProductController::class, "exportAllProducts"]);
                Route::post("/products/load-recommended-products", [ProductController::class, "loadRecommendedProducts"]);
                Route::post("/products/by-ids", [ProductController::class, "getProductsByIds"]);
                Route::post("/products/random", [ProductController::class, "randomProducts"]);
                Route::post("/products/categories", [ProductController::class, "getCategories"]);
                Route::post("/products/add-product", [ProductController::class, "saveProduct"]);
                Route::post("/products/change-recommendation-status", [ProductController::class, "changeRecommendationStatus"]);
                Route::post("/products/categories/recommendation-status", [ProductController::class, "changeCategoryRecommendationStatus"]);
                Route::post("/products/remove-all-products", [ProductController::class, "removeAllProducts"]);
                Route::delete("/products/remove-category/{categoryId}", [ProductController::class, "removeCategoryId"]);
                Route::post("/products/categories/status/{id}", [ProductController::class, "changeCategoryStatus"]);
                Route::post("/products/add-category", [ProductController::class, "storeCategory"]);
                Route::post("/products/in-category", [ProductController::class, "getProductsInCategory"]);
                Route::post("/products/category/{productId}", [ProductController::class, "getCategory"]);
                Route::post("/products/{productId}", [ProductController::class, "getProduct"]);
                Route::post("/products/restore-product/{productId}", [ProductController::class, "restore"])
                    ->middleware(["tgAuth.admin"]);
                Route::post("/products/stop-list-product/{productId}", [ProductController::class, "stopList"])
                    ->middleware(["tgAuth.admin"]);

                Route::delete("/products/{productId}", [ProductController::class, "destroy"])
                    ->middleware(["tgAuth.admin"]);

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
                Route::post('/store-products', "storeProducts");
                Route::post('/store', "store");
            });

        Route::prefix("bitrix")
            ->controller(\App\Http\Controllers\Bots\Web\BitrixController::class)
            ->middleware(["tgAuth.admin"])
            ->group(function(){
                Route::post('/load-connections', "index");
                Route::post('/store', "store");
                Route::post('/check', "check");
                Route::delete('/remove/{id}', "remove");
            });

        Route::prefix("cdek")
            ->controller(\App\Http\Controllers\Bots\Web\CdekController::class)
            ->middleware(["tgAuth.any"])
            ->group(function(){
                Route::post('/store', "store");
                Route::post('/calc-basket-tariff', "calcBasketTariff");
                Route::post('/make-order', "makeOrder");
                Route::post('/get-cities', "getCities");
                Route::post('/get-regions', "getRegions");
                Route::post('/get-offices', "getOffices");
                Route::post('/calc-tariff', "calcTariff");
                Route::post('/calc-tariff-by-code/{code}', "calcTariffByCode");
            });

        Route::prefix("admins")
            ->controller(AdminBotController::class)
            ->group(function () {
                Route::post('/', "loadActiveAdminList")
                    ->middleware(["tgAuth.any"]);
                Route::post('/request', "requestCashBack")
                    ->middleware(["tgAuth.any"]);

                Route::post('/request-review', "requestUserReview")
                    ->middleware(["tgAuth.admin"]);

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

                Route::post('/load-traffic-statistic', "trafficStatistic")
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
                Route::post('/profile-form-data', [\App\Http\Controllers\Bots\Web\AdminBotController::class, "storeProfileForm"]);

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
            ->group(function () {
                Route::post("/", "index") ->middleware(["tgAuth.admin"]);
                Route::post("/page", "createPage") ->middleware(["tgAuth.admin"]);
                Route::post("/page-by-id/{id}", "loadPageById") ->middleware(["tgAuth.admin"]);
                Route::post("/page-update", "updatePage")->middleware(["tgAuth.admin"]);
                Route::post("/activate-page-password", "activatePagePassword")
                    ->middleware(["tgAuth.any"]);
                Route::post("/duplicate/{pageId}", "duplicate")->middleware(["tgAuth.admin"]);
                Route::post("/remove/{pageId}", "destroy")->middleware(["tgAuth.admin"]);
            });

        Route::prefix("product-collections")
            ->controller(\App\Http\Controllers\Bots\Web\ProductCollectionController::class)
            ->middleware(["tgAuth.any"])
            ->group(function () {
                Route::post("/", "index");
                Route::post("/global", "globalList");

                Route::post("/store", "store")->middleware(["tgAuth.admin"]);
                Route::post("/remove/{collectionId}", "destroy")->middleware(["tgAuth.admin"]);
                Route::post("/duplicate/{collectionId}", "duplicate")->middleware(["tgAuth.admin"]);

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
                Route::post("/bot-theme", "updateBotTheme");
                Route::post("/bot-icons-update", "updateBotIcons");
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
                Route::post("/store-message-settings", "storeMessageSettings")
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
