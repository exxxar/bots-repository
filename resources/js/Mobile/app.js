import './bootstrap';
import Vue3TouchEvents from "vue3-touch-events";

import router from './Router'

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../../vendor/tightenco/ziggy/dist/vue.m';
import VueLazyLoad from 'vue3-lazyload'
import moment from 'moment'
import VueTheMask from 'vue-the-mask'
import VueSocialSharing from 'vue-social-sharing'
import SimpleTypeahead from 'vue3-simple-typeahead';
import 'vue3-simple-typeahead/dist/vue3-simple-typeahead.css'; //Optional default CSS

import PerfectScrollbar from 'vue3-perfect-scrollbar'
import 'vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css'

import store from './Store'

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
            local(date){
                return moment(date).format("YYYY-MM-DDThh:mm")
            },
            timeAgo(date) {
                return moment(date).fromNow()
            },
            current(date) {
                return moment(date).format("YYYY-MM-DD")
            },
            currentFull(date) {
                return moment(date).format("YYYY-MM-DD HH:mm:ss")
            },
        }


        app
            .use(plugin)
            .use(store)
            .use(VueTheMask)
            .use(Notifications)
            .use(Popper)
            .use(router)
            .use(VueSocialSharing)
            .use(PerfectScrollbar)
            .use(SimpleTypeahead)
            .use(Vue3TouchEvents)
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
