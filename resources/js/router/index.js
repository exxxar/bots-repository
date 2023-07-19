import {createWebHashHistory , createRouter} from "vue-router";

import Home from '@/Pages/Shop/Home.vue';
import Products from '@/Pages/Shop/Products.vue';
import Product from '@/Pages/Shop/Product.vue';
import Favorites from '@/Pages/Shop/Favorites.vue';
import Basket from '@/Pages/Shop/Basket.vue';
import CheckOut from '@/Pages/Shop/CheckOut.vue';
import Settings from '@/Pages/Shop/Settings.vue';

//support pages
import Terms from '@/Pages/Shop/Terms.vue';
import OurTeam from '@/Pages/Shop/OurTeam.vue';
import ContactUs from '@/Pages/Shop/ContactUs.vue';
import Help from '@/Pages/Shop/Help.vue';


window.currentPath = ''//window.location.pathname

const prefix = window.currentPath || ''

export const routes = [

    {
        name: 'home',
        path: prefix+'/home',
        component: Home,
        meta: (route) => ({ title: 'Главная страница' })
    },
    {
        name: 'products',
        path: prefix+'/products',
        component: Products,
        meta: (route) => ({ title: 'Продукты' })
    },
    {
        name: 'checkout',
        path: prefix+'/checkout',
        component: CheckOut,
        meta: (route) => ({ title: 'Корзина' })
    },
    {
        name: 'help',
        path: prefix+'/help',
        component: Help,
        meta: (route) => ({ title: 'Помощь' })
    },
    {
        name: 'contactus',
        path: prefix+'/contact-us',
        component: ContactUs,
        meta: (route) => ({ title: 'Наши контакты' })
    },
    {
        name: 'ourteam',
        path: prefix+'/our-team',
        component: OurTeam,
        meta: (route) => ({ title: 'Наша команда' })
    },
    {
        name: 'terms',
        path: prefix+'/terms',
        component: Terms,
        meta: (route) => ({ title: 'Условия использования' })
    },
    {
        name: 'product',
        path: prefix+'/products/:productId',
        component: Product,
        meta: (route) => ({ title: 'Продукт #'+route.params.productId })
    },
    {
        name: 'settings',
        path: prefix+'/settings',
        component: Settings,
        meta: (route) => ({ title: 'Настройки' })
    },
    {
        name: 'basket',
        path: prefix+'/basket',
        component: Basket,
        meta: (route) => ({ title: 'Корзина' })
    },
    {
        name: 'favorites',
        path: prefix+'/favorites',
        component: Favorites,
        meta: (route) => ({ title: 'Избранное' })
    },
];

const router = createRouter({
   history: createWebHashHistory(),
    routes: routes,
});

export default router;
