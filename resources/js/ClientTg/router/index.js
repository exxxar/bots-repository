import {createWebHashHistory, createRouter} from "vue-router";

import Home from '@/ClientTg/Pages/V1/Shop/Home.vue';
import Products from '@/ClientTg/Pages/V1/Shop/Products.vue';
import ProductsInCategory from '@/ClientTg/Pages/V1/Shop/ProductsInCategory.vue';
import Product from '@/ClientTg/Pages/V1/Shop/Product.vue';
import Favorites from '@/ClientTg/Pages/V1/Shop/Favorites.vue';
import Basket from '@/ClientTg/Pages/V1/Shop/Basket.vue';
import CheckOut from '@/ClientTg/Pages/V1/Shop/CheckOut.vue';
import Settings from '@/ClientTg/Pages/V1/Shop/Settings.vue';
import Schedule from '@/ClientTg/Pages/V1/Shop/Schedule.vue';
import Quizzes from '@/ClientTg/Pages/V1/Quiz/Quizzes.vue';
import SingleQuiz from '@/ClientTg/Pages/V1/Quiz/SingleQuiz.vue';
import Appointments from '@/ClientTg/Pages/V1/Appointment/Appointments.vue';
import OneArmedBanditPage from '@/ClientTg/Pages/V1/OneArmedBanditPage.vue';


import DeliveryMain from '@/ClientTg/Pages/V1/Delivery/Main.vue';
import PaymentSBP from '@/ClientTg/Pages/V1/Shop/PaymentSBP.vue';
import ClientPromocode from '@/ClientTg/Pages/V1/Shop/Promocode.vue';
import FriendsGame from '@/ClientTg/Pages/V1/Shop/FriendsGame.vue';

import FoodConstructors from '@/ClientTg/Pages/V1/FoodConstructors.vue';

//support pages
import Terms from '@/ClientTg/Pages/V1/Shop/Terms.vue';
import OurTeam from '@/ClientTg/Pages/V1/Shop/OurTeam.vue';
import ContactUs from '@/ClientTg/Pages/V1/Shop/ContactUs.vue';
import Help from '@/ClientTg/Pages/V1/Shop/Help.vue';
import Wheel from '@/ClientTg/Pages/V1/Shop/Wheel.vue';
import WheelCustom from '@/ClientTg/Pages/V1/Shop/WheelCustom.vue';
import CashOut from '@/ClientTg/Pages/V1/Shop/CashOut.vue';
import SaveUp from '@/ClientTg/Pages/V1/Shop/SaveUp.vue';
import Quest from '@/ClientTg/Pages/V1/Shop/Quest.vue';
import Empty from '@/ClientTg/Pages/V1/Shop/Empty.vue';
import Booking from '@/ClientTg/Pages/V1/Shop/Booking.vue';
import Admins from '@/ClientTg/Pages/V1/Shop/Admins.vue';
import Vip from '@/ClientTg/Pages/V1/Shop/Vip.vue';
import ProfileForm from '@/ClientTg/Pages/V1/Shop/ProfileForm.vue';

import Categories from '@/ClientTg/Pages/V1/Shop/Categories.vue';

import AdminMain from '@/ClientTg/Pages/V1/Admin/Main.vue';
import AdminChatLog from '@/ClientTg/Pages/V1/Admin/ChatHistory.vue';
import AdminPromotion from '@/ClientTg/Pages/V1/Admin/Promotion.vue';
import AdminStatistic from '@/ClientTg/Pages/V1/Admin/Statistic.vue';
import AdminWorkStatus from '@/ClientTg/Pages/V1/Admin/WorkStatus.vue';
import AdminOrders from '@/ClientTg/Pages/V1/Admin/Orders.vue';
import AdminUsers from '@/ClientTg/Pages/V1/Admin/Users.vue';
import AdminActions from '@/ClientTg/Pages/V1/Admin/Actions.vue';
import AdminShopManager from '@/ClientTg/Pages/V1/Admin/ShopManager.vue';
import AdminCompanyManager from '@/ClientTg/Pages/V1/Admin/CompanyManager.vue';
import AdminBotManager from '@/ClientTg/Pages/V1/Admin/BotManager.vue';
import AdminBotPage from '@/ClientTg/Pages/V1/Admin/BotManager/Pages.vue';
import AdminBotSlug from '@/ClientTg/Pages/V1/Admin/BotManager/Slugs.vue';
import AdminBotDialog from '@/ClientTg/Pages/V1/Admin/BotManager/Dialogs.vue';
import AdminBotKeyboard from '@/ClientTg/Pages/V1/Admin/BotManager/Keyboards.vue';
import AdminBotAmo from '@/ClientTg/Pages/V1/Admin/BotManager/Amo.vue';
import AdminBotYClients from '@/ClientTg/Pages/V1/Admin/BotManager/YClients.vue';
import AdminBonusProduct from '@/ClientTg/Pages/V1/Admin/BonusProduct.vue';
import AdminMessageToUser from '@/ClientTg/Pages/V1/Admin/MessageToUser.vue';


import ManagerMain from '@/ClientTg/Pages/V1/Manager/Main.vue';
import ManagerForm from '@/ClientTg/Pages/V1/Manager/Manager.vue';
import ManagerClients from '@/ClientTg/Pages/V1/Manager/Clients.vue';
import ManagerProfile from '@/ClientTg/Pages/V1/Manager/Profile.vue';
import ManagerPartners from '@/ClientTg/Pages/V1/Manager/Partners.vue';


import DeliverymanForm from '@/ClientTg/Pages/V1/Delivery/DeliveryManForm.vue';


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
        name: 'PaymentSBP',
        path: '/payment-sbp-main/:amount',
        component: PaymentSBP,
        meta: {title: 'Оплата по СБП', hide_menu: true}
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
        name: 'checkout',
        path: '/checkout',
        component: CheckOut,
        meta: {title: 'Корзина'}
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
        name: 'help',
        path: '/help',
        component: Help,
        meta: {title: 'Помощь'}
    },
    {
        name: 'contactus',
        path: '/contact-us',
        component: ContactUs,
        meta: {title: 'Наши контакты'}
    },
    {
        name: 'ourteam',
        path: '/our-team',
        component: OurTeam,
        meta: {title: 'Наша команда'}
    },
    {
        name: 'terms',
        path: '/terms',
        component: Terms,
        meta: {title: 'Условия использования'}
    },
    {
        name: 'product',
        path: '/products/:productId',
        component: Product,
        meta: {title: 'Продукт'}
    },
    {
        name: 'settings',
        path: '/settings',
        component: Settings,
        meta: {title: 'Настройки'}
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

import CatalogV2 from '@/ClientTg/Pages/V2/Shop/Catalog.vue';
import ContactsV2 from '@/ClientTg/Pages/V2/Shop/Contacts.vue';
import OrdersV2 from '@/ClientTg/Pages/V2/Shop/MyOrders.vue';
import ProfileV2 from '@/ClientTg/Pages/V2/Shop/Profile.vue';
import ProductV2 from '@/ClientTg/Pages/V2/Shop/Product.vue';
import ShopCartV2 from '@/ClientTg/Pages/V2/Shop/ShopCart.vue';
import PaymentV2 from '@/ClientTg/Pages/V2/Shop/Payment.vue';
import FeedBackV2 from "@/ClientTg/Pages/V2/Other/FeedBack.vue";
import MenuV2 from "@/ClientTg/Pages/V2/Shop/Menu.vue";
import OneArmedBanditV2 from '@/ClientTg/Components/V2/Games/OneArmedBanditPage.vue';
import CashBackV2 from '@/ClientTg/Pages/V2/Shop/MyCashBack.vue';
import FriendsV2 from '@/ClientTg/Pages/V2/Shop/MyFriends.vue';
import WheelOfFortuneV2 from '@/ClientTg/Pages/V2/Shop/WheelOfFortune.vue';
import MailingV2 from '@/ClientTg/Pages/V2/Admin/Mailing.vue';
import ClientsV2 from '@/ClientTg/Pages/V2/Admin/Clients.vue';
import ShopV2 from '@/ClientTg/Pages/V2/Admin/Shop.vue';
import AdminOrdersV2 from '@/ClientTg/Pages/V2/Admin/Orders.vue';
import StatisticV2 from '@/ClientTg/Pages/V2/Admin/Statistic.vue';
import PromoCodesV2 from '@/ClientTg/Pages/V2/Admin/Promocodes.vue';
import InstaQuestV2 from "@/ClientTg/Pages/V2/Shop/InstaQuest.vue";
import FriendsGameV2 from "@/ClientTg/Pages/V2/Shop/FriendsGame.vue";


const simplePrefix = "/s"
export const simpleRoutes = [

    {
        name: 'CatalogV2',
        path: simplePrefix + '/catalog',
        component: CatalogV2,
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
    {
        name: 'PaymentV2',
        path: simplePrefix + '/payment',
        component: PaymentV2,
        meta: {title: 'Оплата'}
    },
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
        meta: {title: 'Обратная связь'}
    },
    {
        name: 'MenuV2',
        path: simplePrefix + '/menu',
        component: MenuV2,
        meta: {title: 'Меню системы'}
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
        name: 'MailingV2',
        path: simplePrefix + '/admin/mailing',
        component: MailingV2,
        meta: {title: 'Рассылка'}
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
        name: 'StatisticV2',
        path: simplePrefix + '/admin/statistic',
        component: StatisticV2,
        meta: {title: 'Статистика'}
    },

    {
        name: 'PromoCodesV2',
        path: simplePrefix + '/admin/promocodes',
        component: PromoCodesV2,
        meta: {title: 'Промокоды'}
    },

    {
        name: 'InstaQuestV2',
        path: simplePrefix + '/insta-quest',
        component: InstaQuestV2,
        meta: {title: 'Квест',  hide_menu: true}
    },

    {
        name: 'FriendsGameV2',
        path: simplePrefix + '/friends-game',
        component: FriendsGameV2,
        meta: {title: 'Квест',  hide_menu: true}
    },


];


const router = createRouter({
    history: createWebHashHistory(),
    routes: [...routes, ...adminRoutes, ...managerRoutes, ...deliveryRoutes, ...simpleRoutes],
});

export default router;
