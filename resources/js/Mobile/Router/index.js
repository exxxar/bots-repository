import {createWebHashHistory, createRouter} from "vue-router";

import Home from '@/ClientTg/Pages/V1/Shop/Home.vue';

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

];

const router = createRouter({
    history: createWebHashHistory(),
    routes: [...routes],
});

export default router;
