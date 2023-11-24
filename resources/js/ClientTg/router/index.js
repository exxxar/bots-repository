import {createWebHashHistory , createRouter} from "vue-router";

import Home from '@/ClientTg/Pages/Shop/Home.vue';
import Products from '@/ClientTg/Pages/Shop/Products.vue';
import ProductsInCategory from '@/ClientTg/Pages/Shop/ProductsInCategory.vue';
import Product from '@/ClientTg/Pages/Shop/Product.vue';
import Favorites from '@/ClientTg/Pages/Shop/Favorites.vue';
import Basket from '@/ClientTg/Pages/Shop/Basket.vue';
import CheckOut from '@/ClientTg/Pages/Shop/CheckOut.vue';
import Settings from '@/ClientTg/Pages/Shop/Settings.vue';


import DeliveryMain from '@/ClientTg/Pages/Delivery/Main.vue';

import FoodConstructors from '@/ClientTg/Pages/FoodConstructors.vue';

//support pages
import Terms from '@/ClientTg/Pages/Shop/Terms.vue';
import OurTeam from '@/ClientTg/Pages/Shop/OurTeam.vue';
import ContactUs from '@/ClientTg/Pages/Shop/ContactUs.vue';
import Help from '@/ClientTg/Pages/Shop/Help.vue';
import Wheel from '@/ClientTg/Pages/Shop/Wheel.vue';
import WheelCustom from '@/ClientTg/Pages/Shop/WheelCustom.vue';
import CashOut from '@/ClientTg/Pages/Shop/CashOut.vue';
import SaveUp from '@/ClientTg/Pages/Shop/SaveUp.vue';
import Quest from '@/ClientTg/Pages/Shop/Quest.vue';
import Empty from '@/ClientTg/Pages/Shop/Empty.vue';
import Booking from '@/ClientTg/Pages/Shop/Booking.vue';
import Admins from '@/ClientTg/Pages/Shop/Admins.vue';
import Vip from '@/ClientTg/Pages/Shop/Vip.vue';

import Categories from '@/ClientTg/Pages/Shop/Categories.vue';

import AdminMain from '@/ClientTg/Pages/Admin/Main.vue';
import AdminPromotion from '@/ClientTg/Pages/Admin/Promotion.vue';
import AdminStatistic from '@/ClientTg/Pages/Admin/Statistic.vue';
import AdminWorkStatus from '@/ClientTg/Pages/Admin/WorkStatus.vue';
import AdminOrders from '@/ClientTg/Pages/Admin/Orders.vue';
import AdminUsers from '@/ClientTg/Pages/Admin/Users.vue';
import AdminActions from '@/ClientTg/Pages/Admin/Actions.vue';
import AdminShopManager from '@/ClientTg/Pages/Admin/ShopManager.vue';
import AdminCompanyManager from '@/ClientTg/Pages/Admin/CompanyManager.vue';
import AdminBotManager from '@/ClientTg/Pages/Admin/BotManager.vue';
import AdminBotPage from '@/ClientTg/Pages/Admin/BotManager/Pages.vue';
import AdminBotSlug from '@/ClientTg/Pages/Admin/BotManager/Slugs.vue';
import AdminBotDialog from '@/ClientTg/Pages/Admin/BotManager/Dialogs.vue';
import AdminBotKeyboard from '@/ClientTg/Pages/Admin/BotManager/Keyboards.vue';
import AdminBotAmo from '@/ClientTg/Pages/Admin/BotManager/Amo.vue';
import AdminBonusProduct from '@/ClientTg/Pages/Admin/BonusProduct.vue';
import AdminMessageToUser from '@/ClientTg/Pages/Admin/MessageToUser.vue';


import ManagerMain from '@/ClientTg/Pages/Manager/Main.vue';
import ManagerForm from '@/ClientTg/Pages/Manager/Manager.vue';
import ManagerClients from '@/ClientTg/Pages/Manager/Clients.vue';
import ManagerProfile from '@/ClientTg/Pages/Manager/Profile.vue';
import ManagerPartners from '@/ClientTg/Pages/Manager/Partners.vue';


import DeliverymanForm from '@/ClientTg/Pages/Delivery/DeliveryManForm.vue';

export const routes = [

    {
        path: '/',
        redirect: '/empty'
    },
    {
        name: 'empty',
        path: '/empty',
        component: Empty,
        meta: { title: 'Ничего не найдено', hide_menu:true }
    },
    {
        name: 'vip',
        path: '/vip',
        component: Vip,
        meta: { title: 'VIP-анкета', hide_menu:true }
    },
    {
        name: 'cashOut',
        path: '/cash-out',
        component: CashOut,
        meta: { title: 'Вывод средств', hide_menu:true }
    },
    {
        name: 'admin',
        path: '/admins',
        component: Admins,
        meta: { title: 'Активные администраторы', hide_menu:true }
    },
    {
        name: 'home',
        path: '/home',
        component: Home,
        meta: { title: 'Главная страница' }
    },
    {
        name: 'booking',
        path: '/book-a-table',
        component: Booking,
        meta: { title: 'Бронирование столика', hide_menu:true }
    },
    {
        name: 'products',
        path: '/products',
        component: Products,
        meta: { title: 'Продукты' }
    },

    {
        name: 'productsInCategory',
        path: '/products-in-category/:categoryId',
        component: ProductsInCategory,
        meta: { title: 'Продукты в категории' }
    },



    {
        name: 'categories',
        path: '/categories',
        component: Categories,
        meta: { title: 'Категории товаров' }
    },
    {
        name: 'checkout',
        path: '/checkout',
        component: CheckOut,
        meta: { title: 'Корзина' }
    },
    {
        name: 'wheel',
        path: '/wheel-of-fortune',
        component: Wheel,
        meta: { title: 'Колесо фортуны', hide_menu:true }
    },
    {
        name: 'wheelCustom',
        path: '/wheel-of-fortune-custom',
        component: WheelCustom,
        meta: { title: 'Колесо фортуны 2', hide_menu:true }
    },
    {
        name: 'saveup',
        path: '/save-up',
        component: SaveUp,
        meta: { title: 'Накопительная карта', hide_menu:true }
    },
    {
        name: 'instagram',
        path: '/instagram-quest',
        component: Quest,
        meta: { title: 'Инста-квест', hide_menu:true }
    },
    {
        name: 'help',
        path: '/help',
        component: Help,
        meta: { title: 'Помощь' }
    },
    {
        name: 'contactus',
        path: '/contact-us',
        component: ContactUs,
        meta: { title: 'Наши контакты' }
    },
    {
        name: 'ourteam',
        path: '/our-team',
        component: OurTeam,
        meta: { title: 'Наша команда' }
    },
    {
        name: 'terms',
        path: '/terms',
        component: Terms,
        meta: { title: 'Условия использования' }
    },
    {
        name: 'product',
        path: '/products/:productId',
        component: Product,
        meta: { title: 'Продукт' }
    },
    {
        name: 'settings',
        path: '/settings',
        component: Settings,
        meta: { title: 'Настройки' }
    },
    {
        name: 'basket',
        path: '/basket',
        component: Basket,
        meta: { title: 'Корзина' }
    },
    {
        name: 'favorites',
        path: '/favorites',
        component: Favorites,
        meta: { title: 'Избранное' }
    },
];

export const adminRoutes = [
    {
        name: 'AdminMain',
        path: '/admin-main',
        component: AdminMain,
        meta: { title: 'Админ панель: Главная', hide_menu:true, need_admin_menu:true }
    },
    {
        name: 'AdminPromotion',
        path: '/admin-promotion',
        component: AdminPromotion,
        meta: { title: 'Админ панель: Реклама', hide_menu:true, need_admin_menu:true }
    },

    {
        name: 'AdminMessageToUser',
        path: '/admin-callback',
        component: AdminMessageToUser,
        meta: { title: 'Админ панель: Сообщение к пользователю', hide_menu:true, need_admin_menu:true }
    },
    {
        name: 'AdminShopManager',
        path: '/admin-shop-manager',
        component: AdminShopManager,
        meta: { title: 'Админ панель: Магазин', hide_menu:true, need_admin_menu:true }
    },
    {
        name: 'AdminCompanyManager',
        path: '/admin-company-manager',
        component: AdminCompanyManager,
        meta: { title: 'Профиль компании', hide_menu:true, need_admin_menu:true }
    },
    {
        name: 'AdminBotManager',
        path: '/admin-bot-manager',
        component: AdminBotManager,
        meta: { title: 'Настройки бота', hide_menu:true, need_admin_menu:true }
    },

    {
        name: 'AdminBotAmo',
        path: '/admin-bot-amo',
        component: AdminBotAmo,
        meta: { title: 'AMO CRM', hide_menu:true, need_admin_menu:true }
    },

    {
        name: 'AdminBotPage',
        path: '/admin-bot-page',
        component: AdminBotPage,
        meta: { title: 'Страницы бота', hide_menu:true, need_admin_menu:true }
    },

    {
        name: 'AdminBotSlug',
        path: '/admin-bot-slug',
        component: AdminBotSlug,
        meta: { title: 'Скрипты бота', hide_menu:true, need_admin_menu:true }
    },

    {
        name: 'AdminBotDialog',
        path: '/admin-bot-dialog',
        component: AdminBotDialog,
        meta: { title: 'Диалоги бота', hide_menu:true, need_admin_menu:true }
    },

    {
        name: 'AdminBotKeyboard',
        path: '/admin-bot-keyboard',
        component: AdminBotKeyboard,
        meta: { title: 'Клавитуары бота', hide_menu:true, need_admin_menu:true }
    },

    {
        name: 'AdminStatistic',
        path: '/admin-statistic',
        component: AdminStatistic,
        meta: { title: 'Админ панель: Статистика', hide_menu:true, need_admin_menu:true }
    },
    {
        name: 'AdminWorkStatus',
        path: '/admin-work-status',
        component: AdminWorkStatus,
        meta: { title: 'Админ панель: Работа', hide_menu:true, need_admin_menu:true }
    },
    {
        name: 'AdminUsers',
        path: '/admin-users',
        component: AdminUsers,
        meta: { title: 'Админ панель: Пользователи', hide_menu:true, need_admin_menu:true }
    },
    {
        name: 'AdminActions',
        path: '/admin-actions',
        component: AdminActions,
        meta: { title: 'Админ панель: События', hide_menu:true, need_admin_menu:true }
    },
    {
        name: 'AdminOrders',
        path: '/admin-orders',
        component: AdminOrders,
        meta: { title: 'Админ панель: Заказы', hide_menu:true, need_admin_menu:true }
    },

    {
        name: 'AdminBonusProduct',
        path: '/admin-bonus-product',
        component: AdminBonusProduct,
        meta: { title: 'Админ панель: Бонусы', hide_menu:true, need_admin_menu:true }
    },
]

export const managerRoutes = [
    {
        name: 'ManagerMain',
        path: '/manager-main',
        component: ManagerMain,
        meta: { title: 'Менеджер: Главная', hide_menu:true}
    },
    {
        name: 'ManagerForm',
        path: '/manager-form',
        component: ManagerForm,
        meta: { title: 'Менеджер: Регистрация', hide_menu:true}
    },
    {
        name: 'ManagerClients',
        path: '/manager-clients',
        component: ManagerClients,
        meta: { title: 'Менеджер: Клиенты', hide_menu:true}
    },

    {
        name: 'ManagerProfile',
        path: '/manager-profile',
        component: ManagerProfile,
        meta: { title: 'Менеджер: Профиль', hide_menu:true}
    },

    {
        name: 'ManagerPartners',
        path: '/manager-partners',
        component: ManagerPartners,
        meta: { title: 'Менеджер: Партнеры', hide_menu:true}
    },

]

export const deliveryRoutes = [
    {
        name: 'DeliveryMain',
        path: '/delivery-main',
        component: DeliveryMain,
        meta: { title: 'Доставка: Главная', hide_menu:true}
    },

    {
        name: 'DeliverymanForm',
        path: '/deliveryman-form',
        component: DeliverymanForm,
        meta: { title: 'Регистрация доставщика', hide_menu:true}
    },

    {
        name: 'FoodConstructors',
        path: '/food-constructor-main',
        component: FoodConstructors,
        meta: { title: 'Конструктор еды: Главная', hide_menu:true}
    },


];

const router = createRouter({
   history: createWebHashHistory(),
    routes: [...routes, ...adminRoutes, ...managerRoutes, ...deliveryRoutes],
});

export default router;
