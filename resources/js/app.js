import './bootstrap';

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';
import VueLazyLoad from 'vue3-lazyload'
import moment from 'moment'

import VueTheMask from 'vue-the-mask'
import VueSocialSharing from 'vue-social-sharing'

import store from './store'

import mitt from 'mitt'

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
        }

        app
            .use(plugin)
            .use(store)
            .use(VueTheMask)
            .use(Notifications)
            .use(VueSocialSharing)
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
