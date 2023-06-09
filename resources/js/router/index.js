import {createWebHashHistory , createRouter} from "vue-router";

import Home from '@/Pages/Shop/Home.vue';
import Products from '@/Pages/Shop/Products.vue';
import Product from '@/Pages/Shop/Products.vue';
import Favorites from '@/Pages/Shop/Favorites.vue';
import Basket from '@/Pages/Shop/Basket.vue';
import Settings from '@/Pages/Shop/Settings.vue';

window.currentPath = ''//window.location.pathname

const prefix = window.currentPath || ''

export const routes = [

    {
        name: 'home',
        path: prefix+'/home',
        component: Home
    },
    {
        name: 'products',
        path: prefix+'/products',
        component: Products
    },
    {
        name: 'product',
        path: prefix+'/products/:productId',
        component: Product
    },
    {
        name: 'settings',
        path: prefix+'/settings',
        component: Settings
    },
    {
        name: 'basket',
        path: prefix+'/basket',
        component: Basket
    },
    {
        name: 'favorites',
        path: prefix+'/favorites',
        component: Favorites
    },
];

const router = createRouter({
   history: createWebHashHistory(),
    routes: routes,
});

export default router;
