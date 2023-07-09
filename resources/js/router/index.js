import {createWebHistory, createRouter} from "vue-router";

import Home from '@/Pages/Shop/Home.vue';
import Products from '@/Pages/Shop/Products.vue';


export const routes = [
    {
        name: 'home',
        path: '/home',
        component: Home
    },
    {
        name: 'products',
        path: '/products',
        component: Products
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
