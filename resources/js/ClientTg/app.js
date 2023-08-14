import './bootstrap';

import BotNotification from './modules/notifications.js'
import BotPages from './modules/pages.js'
import BotPreloader from './modules/preloader.js'
import Cart from './modules/cart.js'
import CashBack from './modules/cashback.js'

import router from './router'

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../../vendor/tightenco/ziggy/dist/vue.m';
import VueLazyLoad from 'vue3-lazyload'
import moment from 'moment'

import VueTheMask from 'vue-the-mask'
import VueSocialSharing from 'vue-social-sharing'

import PerfectScrollbar from 'vue3-perfect-scrollbar'
import 'vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css'

import store from './store'

import mitt from 'mitt'
import Popper from "vue3-popper";


const eventBus = mitt()

window.eventBus = eventBus;
import Notifications from '@kyvg/vue3-notification'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {

        const app = createApp({render: () => h(App, props)})

        app.config.globalProperties.$filters = {
            timeAgo(date) {
                return moment(date).fromNow()
            },
            current(date) {
                return moment(date).format("YYYY-MM-DD")
            },
        }

        app.config.globalProperties.$botNotification = BotNotification
        app.config.globalProperties.$cart = Cart
        app.config.globalProperties.$preloader = BotPreloader
        app.config.globalProperties.$cashback = CashBack
        app.config.globalProperties.$botPages = BotPages

        app
            .use(plugin)
            .use(store)
            .use(VueTheMask)
            .use(Notifications)
            .use(Popper)
            .use(router)
            .use(VueSocialSharing)
            .use(PerfectScrollbar)
            .use(ZiggyVue, Ziggy)
            .use(VueLazyLoad,
            {
                loading: '/images/cashman.jpg',
                error: '/images/error.png'
            })
            .mount(el);

        // app.config.globalProperties.eventBus = eventBus

        return app
    },
    progress: {
        color: '#4B5563',
    },
});
