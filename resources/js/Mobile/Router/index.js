import {createWebHashHistory, createRouter} from "vue-router";

import Home from "@/Mobile/Pages/Base/Home.vue";
import PageTemplate from "@/Mobile/Pages/Base/Page.vue";


export const routes = [
    {
        name: 'Home',
        path: '/',
        component: Home,
        meta: {title: 'Домашняя'}
    },
    {
        name: 'PageTemplate',
        path: '/page/:id',
        component: PageTemplate,
    },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes: [...routes],
});

export default router;
