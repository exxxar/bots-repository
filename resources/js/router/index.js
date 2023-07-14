import {createWebHashHistory , createRouter} from "vue-router";

import Home from '@/Pages/Shop/Home.vue';
import Products from '@/Pages/Shop/Products.vue';
import Product from '@/Pages/Shop/Product.vue';
import Favorites from '@/Pages/Shop/Favorites.vue';
import Basket from '@/Pages/Shop/Basket.vue';
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
        component: Home
    },
    {
        name: 'products',
        path: prefix+'/products',
        component: Products
    },
    {
        name: 'help',
        path: prefix+'/help',
        component: Help
    },
    {
        name: 'contactus',
        path: prefix+'/contact-us',
        component: ContactUs
    },
    {
        name: 'ourteam',
        path: prefix+'/our-team',
        component: OurTeam
    },
    {
        name: 'terms',
        path: prefix+'/terms',
        component: Terms
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
