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
        meta: { title: 'Главная страница' }
    },
    {
        name: 'products',
        path: prefix+'/products',
        component: Products,
        meta: { title: 'Продукты' }
    },
    {
        name: 'checkout',
        path: prefix+'/checkout',
        component: CheckOut,
        meta: { title: 'Корзина' }
    },
    {
        name: 'help',
        path: prefix+'/help',
        component: Help,
        meta: { title: 'Помощь' }
    },
    {
        name: 'contactus',
        path: prefix+'/contact-us',
        component: ContactUs,
        meta: { title: 'Наши контакты' }
    },
    {
        name: 'ourteam',
        path: prefix+'/our-team',
        component: OurTeam,
        meta: { title: 'Наша команда' }
    },
    {
        name: 'terms',
        path: prefix+'/terms',
        component: Terms,
        meta: { title: 'Условия использования' }
    },
    {
        name: 'product',
        path: prefix+'/products/:productId',
        component: Product,
        meta: { title: 'Продукт' }
    },
    {
        name: 'settings',
        path: prefix+'/settings',
        component: Settings,
        meta: { title: 'Настройки' }
    },
    {
        name: 'basket',
        path: prefix+'/basket',
        component: Basket,
        meta: { title: 'Корзина' }
    },
    {
        name: 'favorites',
        path: prefix+'/favorites',
        component: Favorites,
        meta: { title: 'Избранное' }
    },
];

const router = createRouter({
   history: createWebHashHistory(),
    routes: routes,
});

export default router;
