import {createWebHashHistory , createRouter} from "vue-router";

import Home from 'ClientTg@/Pages/Shop/Home.vue';
import Products from 'ClientTg@/Pages/Shop/Products.vue';
import Product from 'ClientTg@/Pages/Shop/Product.vue';
import Favorites from 'ClientTg@/Pages/Shop/Favorites.vue';
import Basket from 'ClientTg@/Pages/Shop/Basket.vue';
import CheckOut from 'ClientTg@/Pages/Shop/CheckOut.vue';
import Settings from 'ClientTg@/Pages/Shop/Settings.vue';

//support pages
import Terms from 'ClientTg@/Pages/Shop/Terms.vue';
import OurTeam from 'ClientTg@/Pages/Shop/OurTeam.vue';
import ContactUs from 'ClientTg@/Pages/Shop/ContactUs.vue';
import Help from 'ClientTg@/Pages/Shop/Help.vue';
import Wheel from 'ClientTg@/Pages/Shop/Wheel.vue';
import Quest from 'ClientTg@/Pages/Shop/Quest.vue';
import Empty from 'ClientTg@/Pages/Shop/Empty.vue';
import Booking from 'ClientTg@/Pages/Shop/Booking.vue';
import Admins from 'ClientTg@/Pages/Shop/Admins.vue';
import Vip from 'ClientTg@/Pages/Shop/Vip.vue';

import AdminMain from 'ClientTg@/Pages/Admin/Main.vue';
import AdminPromotion from 'ClientTg@/Pages/Admin/Promotion.vue';
import AdminStatistic from 'ClientTg@/Pages/Admin/Statistic.vue';
import AdminWorkStatus from 'ClientTg@/Pages/Admin/WorkStatus.vue';
import AdminOrders from 'ClientTg@/Pages/Admin/Orders.vue';
import AdminUsers from 'ClientTg@/Pages/Admin/Users.vue';
import AdminActions from 'ClientTg@/Pages/Admin/Actions.vue';
import AdminShopManager from 'ClientTg@/Pages/Admin/ShopManager.vue';

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
        name: 'adminmain',
        path: '/admin-main',
        component: AdminMain,
        meta: { title: 'Админ панель: Главная', hide_menu:true, need_admin_menu:true }
    },
    {
        name: 'adminpromotion',
        path: '/admin-promotion',
        component: AdminPromotion,
        meta: { title: 'Админ панель: Реклама', hide_menu:true, need_admin_menu:true }
    },
    {
        name: 'adminshopmanager',
        path: '/admin-shop-manager',
        component: AdminShopManager,
        meta: { title: 'Админ панель: Магазин', hide_menu:true, need_admin_menu:true }
    },
    {
        name: 'adminstatistic',
        path: '/admin-statistic',
        component: AdminStatistic,
        meta: { title: 'Админ панель: Статистика', hide_menu:true, need_admin_menu:true }
    },
    {
        name: 'adminworkstatus',
        path: '/admin-work-status',
        component: AdminWorkStatus,
        meta: { title: 'Админ панель: Работа', hide_menu:true, need_admin_menu:true }
    },
    {
        name: 'adminusers',
        path: '/admin-users',
        component: AdminUsers,
        meta: { title: 'Админ панель: Пользователи', hide_menu:true, need_admin_menu:true }
    },
    {
        name: 'adminactions',
        path: '/admin-actions',
        component: AdminActions,
        meta: { title: 'Админ панель: События', hide_menu:true, need_admin_menu:true }
    },
    {
        name: 'adminorders',
        path: '/admin-orders',
        component: AdminOrders,
        meta: { title: 'Админ панель: Заказы', hide_menu:true, need_admin_menu:true }
    },
]

const router = createRouter({
   history: createWebHashHistory(),
    routes: [...routes, ...adminRoutes],
});

export default router;
