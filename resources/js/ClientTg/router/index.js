import { createWebHashHistory, createRouter } from "vue-router";

import { defineAsyncComponent } from 'vue'

const Home = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/Home.vue'))
const Products = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/Products.vue'))
const ProductsInCategory = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/ProductsInCategory.vue'))
const Product = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/Product.vue'))
const Favorites = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/Favorites.vue'))
const Basket = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/Basket.vue'))
const Schedule = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/Schedule.vue'))
const Quizzes = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Quiz/Quizzes.vue'))
const SingleQuiz = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Quiz/SingleQuiz.vue'))
const Appointments = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Appointment/Appointments.vue'))
const OneArmedBanditPage = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/OneArmedBanditPage.vue'))
const DeliveryMain = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Delivery/Main.vue'))
const ClientPromocode = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/Promocode.vue'))
const FriendsGame = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/FriendsGame.vue'))
const FoodConstructors = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/FoodConstructors.vue'))
const Wheel = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/Wheel.vue'))
const WheelCustom = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/WheelCustom.vue'))
const CashOut = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/CashOut.vue'))
const SaveUp = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/SaveUp.vue'))
const Quest = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/Quest.vue'))
const Empty = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/Empty.vue'))
const Booking = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/Booking.vue'))
const Admins = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/Admins.vue'))
const Vip = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/Vip.vue'))
const ProfileForm = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/ProfileForm.vue'))
const Categories = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Shop/Categories.vue'))
const AdminMain = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/Main.vue'))
const AdminChatLog = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/ChatHistory.vue'))
const AdminPromotion = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/Promotion.vue'))
const AdminStatistic = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/Statistic.vue'))
const AdminWorkStatus = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/WorkStatus.vue'))
const AdminOrders = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/Orders.vue'))
const AdminUsers = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/Users.vue'))
const AdminActions = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/Actions.vue'))
const AdminShopManager = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/ShopManager.vue'))
const AdminCompanyManager = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/CompanyManager.vue'))
const AdminBotManager = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/BotManager.vue'))
const AdminBotPage = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/BotManager/Pages.vue'))
const AdminBotSlug = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/BotManager/Slugs.vue'))
const AdminBotDialog = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/BotManager/Dialogs.vue'))
const AdminBotKeyboard = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/BotManager/Keyboards.vue'))
const AdminBotAmo = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/BotManager/Amo.vue'))
const AdminBotYClients = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/BotManager/YClients.vue'))
const AdminBonusProduct = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/BonusProduct.vue'))
const AdminMessageToUser = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Admin/MessageToUser.vue'))
const ManagerMain = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Manager/Main.vue'))
const ManagerForm = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Manager/Manager.vue'))
const ManagerClients = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Manager/Clients.vue'))
const ManagerProfile = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Manager/Profile.vue'))
const ManagerPartners = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Manager/Partners.vue'))
const DeliverymanForm = defineAsyncComponent(() => import('@/ClientTg/Pages/V1/Delivery/DeliveryManForm.vue'))
const CatalogV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/Catalog.vue'))
const WaiterCatalogV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Waiter/Catalog.vue'))
const ContactsV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/Contacts.vue'))
const OrdersV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/MyOrders.vue'))
const ProfileV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/Profile.vue'))
const ProductV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/Product.vue'))
const ShopCartV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/ShopCart.vue'))
const FeedBackV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Other/FeedBack.vue'))
const MenuV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/Menu.vue'))
const OneArmedBanditV2 = defineAsyncComponent(() => import('@/ClientTg/Components/V2/Games/OneArmedBanditPage.vue'))
const CashBackV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/MyCashBack.vue'))
const FriendsV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/MyFriends.vue'))
const WheelOfFortuneV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/WheelOfFortune.vue'))
const StoryManagerV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/StoryManager.vue'))
const MailingV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Mailing.vue'))
const ClientsV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Clients.vue'))
const ShopV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Shop.vue'))
const AdminOrdersV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Orders.vue'))
const StatisticV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Statistic.vue'))
const PromoCodesV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Promocodes.vue'))
const PagePasswordV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/PagePassword.vue'))
const InstaQuestV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/InstaQuest.vue'))
const RequestPhotoV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/RequestPhoto.vue'))
const FriendsGameV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/FriendsGame.vue'))
const PromoCodeFormV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/PromoCode.vue'))
const VipProfileV2_1 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/VipProfileV1.vue'))
const SimpleProfileV2_1 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/SimpleProfileV1.vue'))
const VipProfileV2_2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/VipProfileV2.vue'))
const WheelCustomV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Games/WheelCustom.vue'))
const AdminMenuV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/AdminMenu.vue'))
const BotManagerV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/BotManager.vue'))
const PageManagerV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Pages.vue'))
const LinkManagerV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Links.vue'))
const IntegrationsV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/IntegrationMenu.vue'))
const PartnersV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Partners.vue'))
const AmoV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Integrations/Amo.vue'))
const CdekV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Integrations/Cdek.vue'))
const DialogsV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Dialogs.vue'))
const SlugsV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Slugs.vue'))
const YClientsV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Integrations/YClients.vue'))
const IikoV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Integrations/Iiko.vue'))
const BitrixV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Integrations/Bitrix.vue'))
const FrontPadV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Integrations/FrontPad.vue'))
const UploadV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Other/FileUpload.vue'))
const ReferralV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/Referral.vue'))
const TableMenuV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/Tables/TableMenu.vue'))
const TableCartV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/Tables/TableCart.vue'))
const TablesManagerV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Tables/TablesManager.vue'))
const TableV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Tables/Table.vue'))
const SendInvoiceV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/Invoice.vue'))
const PageEditorV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Admin/PageEditor.vue'))
const TableBookingV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/Bookings.vue'))
const FoodCalcV2 = defineAsyncComponent(() => import('@/ClientTg/Pages/V2/Shop/FoodCalc.vue'))

export const routes = [

    {
        path: '/',
        redirect: '/empty'
    },
    {
        name: 'empty',
        path: '/empty',
        component: Empty,
        meta: {title: 'Ничего не найдено', hide_menu: true}
    },
    {
        name: 'vip',
        path: '/vip',
        component: Vip,
        meta: {title: 'VIP-анкета', hide_menu: true}
    },
    {
        name: 'FriendsGame',
        path: '/friends-game',
        component: FriendsGame,
        meta: {title: 'Приведи друзей', hide_menu: true}
    },


    {
        name: 'ClientPromocode',
        path: '/promocode-main',
        component: ClientPromocode,
        meta: {title: 'Ввод промокода', hide_menu: true}
    },
    {
        name: 'appointments',
        path: '/appointment-events',
        component: Appointments,
        meta: {title: 'Запись', hide_menu: true}
    },

    {
        name: 'profileForm',
        path: '/profile-form',
        component: ProfileForm,
        meta: {title: 'Анкета', hide_menu: true}
    },
    {
        name: 'quizzes',
        path: '/quizzes',
        component: Quizzes,
        meta: {title: 'Квиз', hide_menu: true}
    },
    {
        name: 'quiz',
        path: '/quiz/:quizId',
        component: SingleQuiz,
        meta: {title: 'Квиз', hide_menu: true}
    },
    {
        name: 'schedule',
        path: '/schedule-main',
        component: Schedule,
        meta: {title: 'График работы', hide_menu: true}
    },
    {
        name: 'cashOut',
        path: '/cash-out',
        component: CashOut,
        meta: {title: 'Вывод средств', hide_menu: true}
    },
    {
        name: 'admin',
        path: '/admins',
        component: Admins,
        meta: {title: 'Активные администраторы', hide_menu: true}
    },
    {
        name: 'home',
        path: '/home',
        component: Home,
        meta: {title: 'Главная страница'}
    },
    {
        name: 'booking',
        path: '/book-a-table',
        component: Booking,
        meta: {title: 'Бронирование столика', hide_menu: true}
    },
    {
        name: 'products',
        path: '/products',
        component: Products,
        meta: {title: 'Продукты'}
    },

    {
        name: 'productsInCategory',
        path: '/products-in-category/:categoryId',
        component: ProductsInCategory,
        meta: {title: 'Продукты в категории'}
    },


    {
        name: 'categories',
        path: '/categories',
        component: Categories,
        meta: {title: 'Категории товаров'}
    },


    {
        name: 'OneArmedBanditPage',
        path: '/one-armed-bandit',
        component: OneArmedBanditPage,
        meta: {title: 'Однорукий бандит', hide_menu: true}
    },
    {
        name: 'wheel',
        path: '/wheel-of-fortune',
        component: Wheel,
        meta: {title: 'Колесо фортуны', hide_menu: true}
    },
    {
        name: 'wheelCustom',
        path: '/wheel-of-fortune-custom',
        component: WheelCustom,
        meta: {title: 'Колесо фортуны 2', hide_menu: true}
    },
    {
        name: 'saveup',
        path: '/save-up',
        component: SaveUp,
        meta: {title: 'Накопительная карта', hide_menu: true}
    },
    {
        name: 'instagram',
        path: '/instagram-quest',
        component: Quest,
        meta: {title: 'Инста-квест', hide_menu: true}
    },


    {
        name: 'product',
        path: '/products/:productId',
        component: Product,
        meta: {title: 'Продукт'}
    },

    {
        name: 'basket',
        path: '/basket',
        component: Basket,
        meta: {title: 'Корзина'}
    },
    {
        name: 'favorites',
        path: '/favorites',
        component: Favorites,
        meta: {title: 'Избранное'}
    },
];

export const adminRoutes = [
    {
        name: 'AdminMain',
        path: '/admin-main',
        component: AdminMain,
        meta: {title: 'Админ панель: Главная', hide_menu: true, need_admin_menu: true}
    },
    {
        name: 'AdminChatLog',
        path: '/admin-chat-history/:botUserId',
        component: AdminChatLog,
        meta: {title: 'История чата', hide_menu: true, need_admin_menu: true}
    },


    {
        name: 'AdminPromotion',
        path: '/admin-promotion',
        component: AdminPromotion,
        meta: {title: 'Админ панель: Реклама', hide_menu: true, need_admin_menu: true}
    },

    {
        name: 'AdminMessageToUser',
        path: '/admin-callback',
        component: AdminMessageToUser,
        meta: {title: 'Админ панель: Сообщение к пользователю', hide_menu: true, need_admin_menu: true}
    },
    {
        name: 'AdminShopManager',
        path: '/admin-shop-manager',
        component: AdminShopManager,
        meta: {title: 'Админ панель: Магазин', hide_menu: true, need_admin_menu: true}
    },
    {
        name: 'AdminCompanyManager',
        path: '/admin-company-manager',
        component: AdminCompanyManager,
        meta: {title: 'Профиль компании', hide_menu: true, need_admin_menu: true}
    },
    {
        name: 'AdminBotManager',
        path: '/admin-bot-manager',
        component: AdminBotManager,
        meta: {title: 'Настройки бота', hide_menu: true, need_admin_menu: true}
    },

    {
        name: 'AdminBotAmo',
        path: '/admin-bot-amo',
        component: AdminBotAmo,
        meta: {title: 'AMO CRM', hide_menu: true, need_admin_menu: true}
    },

    {
        name: 'AdminBotYClients',
        path: '/admin-bot-y-clients',
        component: AdminBotYClients,
        meta: {title: 'YClients', hide_menu: true, need_admin_menu: true}
    },

    {
        name: 'AdminBotPage',
        path: '/admin-bot-page',
        component: AdminBotPage,
        meta: {title: 'Страницы бота', hide_menu: true, need_admin_menu: true}
    },

    {
        name: 'AdminBotSlug',
        path: '/admin-bot-slug',
        component: AdminBotSlug,
        meta: {title: 'Скрипты бота', hide_menu: true, need_admin_menu: true}
    },

    {
        name: 'AdminBotDialog',
        path: '/admin-bot-dialog',
        component: AdminBotDialog,
        meta: {title: 'Диалоги бота', hide_menu: true, need_admin_menu: true}
    },

    {
        name: 'AdminBotKeyboard',
        path: '/admin-bot-keyboard',
        component: AdminBotKeyboard,
        meta: {title: 'Клавитуары бота', hide_menu: true, need_admin_menu: true}
    },

    {
        name: 'AdminStatistic',
        path: '/admin-statistic',
        component: AdminStatistic,
        meta: {title: 'Админ панель: Статистика', hide_menu: true, need_admin_menu: true}
    },
    {
        name: 'AdminWorkStatus',
        path: '/admin-work-status',
        component: AdminWorkStatus,
        meta: {title: 'Админ панель: Работа', hide_menu: true, need_admin_menu: true}
    },
    {
        name: 'AdminUsers',
        path: '/admin-users',
        component: AdminUsers,
        meta: {title: 'Админ панель: Пользователи', hide_menu: true, need_admin_menu: true}
    },
    {
        name: 'AdminActions',
        path: '/admin-actions',
        component: AdminActions,
        meta: {title: 'Админ панель: События', hide_menu: true, need_admin_menu: true}
    },
    {
        name: 'AdminOrders',
        path: '/admin-orders',
        component: AdminOrders,
        meta: {title: 'Админ панель: Заказы', hide_menu: true, need_admin_menu: true}
    },

    {
        name: 'AdminBonusProduct',
        path: '/admin-bonus-product',
        component: AdminBonusProduct,
        meta: {title: 'Админ панель: Бонусы', hide_menu: true, need_admin_menu: true}
    },
]

export const managerRoutes = [
    {
        name: 'ManagerMain',
        path: '/manager-main',
        component: ManagerMain,
        meta: {title: 'Менеджер: Главная', hide_menu: true}
    },
    {
        name: 'ManagerForm',
        path: '/manager-form',
        component: ManagerForm,
        meta: {title: 'Менеджер: Регистрация', hide_menu: true}
    },
    {
        name: 'ManagerClients',
        path: '/manager-clients',
        component: ManagerClients,
        meta: {title: 'Менеджер: Клиенты', hide_menu: true}
    },

    {
        name: 'ManagerProfile',
        path: '/manager-profile',
        component: ManagerProfile,
        meta: {title: 'Менеджер: Профиль', hide_menu: true}
    },

    {
        name: 'ManagerPartners',
        path: '/manager-partners',
        component: ManagerPartners,
        meta: {title: 'Менеджер: Партнеры', hide_menu: true}
    },

]

export const deliveryRoutes = [
    {
        name: 'DeliveryMain',
        path: '/delivery-main',
        component: DeliveryMain,
        meta: {title: 'Доставка: Главная', hide_menu: true, show_cart: true}
    },

    {
        name: 'DeliverymanForm',
        path: '/deliveryman-form',
        component: DeliverymanForm,
        meta: {title: 'Регистрация доставщика', hide_menu: true}
    },

    {
        name: 'FoodConstructors',
        path: '/food-constructor-main',
        component: FoodConstructors,
        meta: {title: 'Конструктор еды: Главная', hide_menu: true}
    },


];

const simplePrefix = "/s"
export const simpleRoutes = [


    {
        name: 'FoodCalcV2',
        path: simplePrefix + '/food-calc',
        component: FoodCalcV2,
        meta: {title: 'Калькулятор'}
    },
    {
        name: 'UploadV2',
        path: simplePrefix + '/upload',
        component: UploadV2,
        meta: {title: 'Загрузка файлов'}
    },

    {
        name: 'SendInvoiceV2',
        path: simplePrefix + '/send-invoice',
        component: SendInvoiceV2,
        meta: {title: 'СБП'}
    },

    {
        name: 'ReferralV2',
        path: simplePrefix + '/referral',
        component: ReferralV2,
        meta: {title: 'Информация о друге'}
    },
    {
        name: 'CatalogV2',
        path: simplePrefix + '/menu',
        component: CatalogV2,
        meta: {title: 'Меню магазина'}
    },

    {
        name: 'TableBookingV2',
        path: simplePrefix + '/booking',
        component: TableBookingV2,
        meta: {title: 'Бронирование столиков'}
    },
    {
        name: 'WaiterCatalogV2',
        path: simplePrefix + '/waiter',
        component: WaiterCatalogV2,
        meta: {title: 'Каталог'}
    },

    {
        name: 'ContactsV2',
        path: simplePrefix + '/contacts',
        component: ContactsV2,
        meta: {title: 'Контакты'}
    },

    {
        name: 'ProductV2',
        path: simplePrefix + '/product/:productId',
        component: ProductV2,
        meta: {title: 'Товар'}
    },

    {
        name: 'OrdersV2',
        path: simplePrefix + '/orders',
        component: OrdersV2,
        meta: {title: 'Заказы'}
    },


    {
        name: 'ShopCartV2',
        path: simplePrefix + '/cart',
        component: ShopCartV2,
        meta: {title: 'Корзина'}
    },
    /*{
        name: 'PaymentV2',
        path: simplePrefix + '/payment',
        component: PaymentV2,
        meta: {title: 'Оплата'}
    },*/
    {
        name: 'ProfileV2',
        path: simplePrefix + '/profile',
        component: ProfileV2,
        meta: {title: 'Профиль'}
    },

    {
        name: 'FeedBackV2',
        path: simplePrefix + '/feedback',
        component: FeedBackV2,
        meta: {title: 'Обратная связь', hide_menu: true}
    },
    {
        name: 'MenuV2',
        path: simplePrefix + '/catalog',
        component: MenuV2,
        meta: {title: 'Каталог сервисов'}
    },
    {
        name: 'TableMenuV2',
        path: simplePrefix + '/table-menu',
        component: TableMenuV2,
        meta: {title: 'Столик'}
    },
    {
        name: 'TableCartV2',
        path: simplePrefix + '/table-cart',
        component: TableCartV2,
        meta: {title: 'Корзина'}
    },
    {
        name: 'OneArmedBanditV2',
        path: simplePrefix + '/bandit',
        component: OneArmedBanditV2,
        meta: {title: 'Однорукий бандит'}
    },

    {
        name: 'CashBackV2',
        path: simplePrefix + '/cashback',
        component: CashBackV2,
        meta: {title: 'История бонусов'}
    },

    {
        name: 'FriendsV2',
        path: simplePrefix + '/friends',
        component: FriendsV2,
        meta: {title: 'Друзья'}
    },

    {
        name: 'WheelOfFortuneV2',
        path: simplePrefix + '/wheel',
        component: WheelOfFortuneV2,
        meta: {title: 'Колесо фортуны'}
    },
    {
        name: 'StoryManagerV2',
        path: simplePrefix + '/stories',
        component: StoryManagerV2,
        meta: {title: 'Истории'}
    },

    {
        name: 'MailingV2',
        path: simplePrefix + '/admin/mailing',
        component: MailingV2,
        meta: {title: 'Рассылка'}
    },
    {
        name: 'TablesManagerV2',
        path: simplePrefix + '/admin/tables-manager',
        component: TablesManagerV2,
        meta: {title: 'Управление столиками'}
    },
    {
        name: 'TableV2',
        path: simplePrefix + '/admin/tables-manager/:tableId',
        component: TableV2,
        meta: {title: 'Столик'}
    },
    {
        name: 'PageEditorV2',
        path: simplePrefix + '/admin/page-editor/:pageId',
        component: PageEditorV2,
        meta: {title: 'Реактор страницы'}
    },
    {
        name: 'ClientsV2',
        path: simplePrefix + '/admin/clients',
        component: ClientsV2,
        meta: {title: 'Клиенты'}
    },
    {
        name: 'ShopV2',
        path: simplePrefix + '/admin/shop',
        component: ShopV2,
        meta: {title: 'Магазин'}
    },

    {
        name: 'AdminOrdersV2',
        path: simplePrefix + '/admin/orders',
        component: AdminOrdersV2,
        meta: {title: 'Заказы'}
    },
    {
        name: 'LinkManagerV2',
        path: simplePrefix + '/admin/links',
        component: LinkManagerV2,
        meta: {title: 'UTM-метки'}
    },
    {
        name: 'StatisticV2',
        path: simplePrefix + '/admin/statistic',
        component: StatisticV2,
        meta: {title: 'Статистика'}
    },
    {
        name: 'AdminMenuV2',
        path: simplePrefix + '/admin/menu',
        component: AdminMenuV2,
        meta: {title: 'Меню'}
    },


    {
        name: 'PromoCodesV2',
        path: simplePrefix + '/admin/promocodes',
        component: PromoCodesV2,
        meta: {title: 'Промокоды'}
    },
    {
        name: 'PagePasswordV2',
        path: simplePrefix + '/enter-page-password',
        component: PagePasswordV2,
        meta: {title: 'Ввод ключа'}
    },

    {
        name: 'InstaQuestV2',
        path: simplePrefix + '/new-insta-quest',
        component: InstaQuestV2,
        meta: {title: 'Квест', hide_menu: true}
    },
    {
        name: 'RequestPhotoV2',
        path: simplePrefix + '/request-photo',
        component: RequestPhotoV2,
        meta: {title: 'Запрос фото', hide_menu: true}
    },
    {
        name: 'FriendsGameV2',
        path: simplePrefix + '/new-friends-game',
        component: FriendsGameV2,
        meta: {title: 'Квест', hide_menu: true}
    },

    {
        name: 'PromoCodeFormV2',
        path: simplePrefix + '/new-promo-code',
        component: PromoCodeFormV2,
        meta: {title: 'Промокоды', hide_menu: true}
    },
    {
        name: 'VipProfileV2_1',
        path: simplePrefix + '/new-vip',
        component: VipProfileV2_1,
        meta: {title: 'Анкета пользователя', hide_menu: true}
    },

    {
        name: 'VipProfileV2_2',
        path: simplePrefix + '/new-profile-form',
        component: VipProfileV2_2,
        meta: {title: 'Анкета пользователя', hide_menu: true}
    },

    {
        name: 'SimpleProfileV2_1',
        path: simplePrefix + '/simple-profile-form',
        component: SimpleProfileV2_1,
        meta: {title: 'Анкета пользователя', hide_menu: true}
    },

    {
        name: 'WheelCustomV2',
        path: simplePrefix + '/wheel-of-fortune-custom',
        component: WheelCustomV2,
        meta: {title: 'Колесо фортуны', hide_menu: true}
    },
    {
        name: 'BotManagerV2',
        path: simplePrefix + '/admin/bot',
        component: BotManagerV2,
        meta: {title: 'Бот'}
    },
    {
        name: 'PageManagerV2',
        path: simplePrefix + '/admin/pages',
        component: PageManagerV2,
        meta: {title: 'Страницы'}
    },
    {
        name: 'PartnersV2',
        path: simplePrefix + '/admin/partners',
        component: PartnersV2,
        meta: {title: 'Партнеры'}
    },
    {
        name: 'IntegrationsV2',
        path: simplePrefix + '/admin/integrations',
        component: IntegrationsV2,
        meta: {title: 'Интеграции'}
    },

    {
        name: 'AmoV2',
        path: simplePrefix + '/admin/amo',
        component: AmoV2,
        meta: {title: 'AMO'}
    },
    {
        name: 'CdekV2',
        path: simplePrefix + '/admin/cdek',
        component: CdekV2,
        meta: {title: 'CDEK'}
    },
    {
        name: 'BitrixV2',
        path: simplePrefix + '/admin/bitrix',
        component: BitrixV2,
        meta: {title: 'Bitrix24'}
    },
    {
        name: 'IikoV2',
        path: simplePrefix + '/admin/iiko',
        component: IikoV2,
        meta: {title: 'IIKO'}
    },
    {
        name: 'FrontPadV2',
        path: simplePrefix + '/admin/frontpad',
        component: FrontPadV2,
        meta: {title: 'FrontPad'}
    },
    {
        name: 'DialogsV2',
        path: simplePrefix + '/admin/dialogs',
        component: DialogsV2,
        meta: {title: 'Диалоги'}
    },
    {
        name: 'SlugsV2',
        path: simplePrefix + '/admin/scripts',
        component: SlugsV2,
        meta: {title: 'Скрипты'}
    },
    {
        name: 'YClientsV2',
        path: simplePrefix + '/admin/y-clients',
        component: YClientsV2,
        meta: {title: 'YClients'}
    },
];

const fastoranPrefix = "/f"
export const fastoranRoutes = [
    {
        name: 'YClientsV2',
        path: fastoranPrefix + '/',
        component: YClientsV2,
        meta: {title: 'YClients'}
    },
];


const router = createRouter({
    history: createWebHashHistory(),
    routes: [...routes, ...adminRoutes, ...managerRoutes, ...deliveryRoutes, ...simpleRoutes],
});

export default router;
