<script setup>

import {Head} from '@inertiajs/vue3'


</script>
<template>

    <Head>
        <title>CashMan - система твоего бизнеса внутри</title>
        <meta name="description" content="CashMan - система твоего бизнеса внутри"/>
    </Head>
<!--    <Preloader/>
    <AddToCartModal/>
    <CashBackItemInfoModal/>
    <QrCodeModal/>
    <EventCallbackForm/>
    <Notifications/>-->


    <div id="page">

        <!-- header and footer bar go here-->
        <div class="header header-fixed header-auto-show header-logo-app">
            <a @click="closeShop" class="header-title header-subtitle">Вернуться в бота</a>
            <a @click="$router.back()" data-back-button class="header-icon header-icon-1"><i
                class="fas fa-arrow-left"></i></a>
            <a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-dark"><i
                class="fas fa-sun"></i></a>
            <a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-light"><i
                class="fas fa-moon"></i></a>
            <a href="#" data-menu="menu-highlights" class="header-icon header-icon-3"><i class="fas fa-brush"></i></a>
            <a href="#"
               v-if="!$route.meta.hide_menu"
               data-menu="menu-main" class="header-icon header-icon-4"><i class="fas fa-bars"></i></a>
        </div>


        <div id="footer-bar"
             v-if="!$route.meta.hide_menu"
             class="footer-bar-5 bg-dark2-dark mb-2 ml-2 mr-2 rounded-m">

            <router-link
                :active-class="'active-nav'"
                :tag="'a'" :to="'/basket'">
                <i class="fa-solid fa-basket-shopping"></i><span class="color-white">Корзина</span><em
                class="badge bg-green1-dark" v-if="cartTotalCount>0">{{ cartTotalCount }}</em>
                <strong v-if="$route.path=='/basket'"></strong>
            </router-link>


            <router-link
                :active-class="'active-nav'"
                :tag="'a'" :to="'/products'">
                <i class="fa-brands fa-shopify"></i><span class="color-white">Продукты</span>
                <strong v-if="$route.path=='/products'"></strong>
            </router-link>

            <router-link
                :active-class="'active-nav'"
                :tag="'a'" :to="'/home'">
                <i class="fa fa-home"></i><span class="color-white">Домой</span>
                <strong v-if="$route.path=='/home'"></strong>
            </router-link>

            <router-link
                :active-class="'active-nav'"
                :tag="'a'" :to="'/favorites'">
                <i class="fa fa-heart"></i><span class="color-white">Избранное</span><em
                class="badge bg-green1-dark" v-if="favoritesCount>0">{{ favoritesCount }}</em>
                <strong v-if="$route.path=='/favorites'"></strong>
            </router-link>


            <!--
                        <router-link
                            :active-class="'active-nav'"
                            :tag="'a'" :to="'/settings'">
                            <i class="fa fa-cog"></i><span class="color-white">Настройки</span>
                            <strong v-if="$route.path=='/settings'"></strong>
                        </router-link>
            -->

            <a href="#" data-menu="menu-main">
                <i class="fa-solid fa-bars"></i><span class="color-white">Меню</span>
            </a>

        </div>


        <div id="footer-bar"
             v-if="$route.meta.need_admin_menu"
             class="footer-bar-5 bg-dark2-dark mb-2 ml-2 mr-2 rounded-m">

<!--            <router-link
                :active-class="'active-nav'"
                :tag="'a'" :to="'/admin-orders'">
                <i class="fa-solid fa-basket-shopping"></i><span class="color-white">Зазказы</span><em
                class="badge bg-green1-dark">0</em>
                <strong v-if="$route.path=='/admin-orders'"></strong>
            </router-link>-->

            <router-link
                :active-class="'active-nav'"
                :tag="'a'" :to="'/admin-bot-manager'">
                <i class="fa-solid fa-robot"></i><span class="color-white">Настройка</span>
                <strong v-if="$route.path=='/admin-bot-manager'"></strong>
            </router-link>


            <router-link
                :active-class="'active-nav'"
                :tag="'a'" :to="'/admin-statistic'">
                <i class="fa-solid fa-ranking-star"></i><span class="color-white">Статистика</span>
                <strong v-if="$route.path=='/admin-statistic'"></strong>
            </router-link>

            <router-link
                :active-class="'active-nav'"
                :tag="'a'" :to="'/admin-main'">
                <i class="fa fa-home"></i><span class="color-white">Главная</span>
                <strong v-if="$route.path=='/admin-main'"></strong>
            </router-link>

            <router-link
                :active-class="'active-nav'"
                :tag="'a'" :to="'/admin-bot-page'">
                <i class="fa-solid fa-file-lines"></i><span class="color-white">Страницы</span>

                <strong v-if="$route.path=='/admin-bot-page'"></strong>
            </router-link>


            <a href="#" data-menu="menu-admin-main">
                <i class="fa-solid fa-bars"></i><span class="color-white">Меню</span>
            </a>


            <!--
                        <a href="#" data-menu="menu-main">
                            <i class="fa-solid fa-bars"></i><span class="color-white">Меню</span>
                        </a>-->

        </div>


        <div id="footer-bar"
             v-if="$route.meta.show_cart&&cartTotalCount>0"
             class="footer-bar-5 bg-transparent mb-2 ml-2 mr-2 rounded-m">
            <button type="button"
                    @click="scrollToBasket"
                    class="btn btn-m btn-full mb-3 rounded-l text-uppercase font-900 shadow-s bg-green2-dark position-relative w-100">
                Перейти в корзину <span class="badge badge-danger" style="margin-top:12px;">{{cartTotalCount}}</span>
            </button>
        </div>



        <slot/>

        <slot name="modals"></slot>
        <!-- end of page content-->
<!--        <ShareMenuBar/>
        <HighlightsMenuBar/>

        <SideBar/>
        <SideBarAdmin/>

        <PageMenuModal/>
        <KeyboardMenuModal/>-->
    </div>

</template>
<script>
import {mapGetters} from "vuex";
import baseJS from '../modules/custom.js'

export default {
    watch: {
        $route(newRouteValue) {
            this.$preloader.show();
            baseJS.handler()
            this.$nextTick(() => {
                document.body.scrollTop = document.documentElement.scrollTop = 0;
            })
        },
    },
    computed: {
        ...mapGetters(['cartTotalCount', 'favoritesCount','getSelf']),
        tg() {
            return window.Telegram.WebApp;
        },
    },
    mounted() {
        console.log(window.currentBot)
    },
    methods: {
        openLink(url) {
            this.tg.openLink(url, {
                try_instant_view: true
            })
        },
        closeShop() {
            this.tg.close()
        },
        scrollToBasket(){
            document.querySelector("#basket").scrollIntoView({
                behavior: 'smooth'
            });
        }

    },


}
</script>

<style lang="scss">
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}


body {
    font-size: .875rem;
}

.feather {
    width: 16px;
    height: 16px;
}

/*
 * Sidebar
 */

.sidebar {
    position: fixed;
    top: 0;
    /* rtl:raw:
    right: 0;
    */
    bottom: 0;
    /* rtl:remove */
    left: 0;
    z-index: 100; /* Behind the navbar */
    padding: 48px 0 0; /* Height of navbar */
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}

@media (max-width: 767.98px) {
    .sidebar {
        top: 5rem;
    }
}

.sidebar-sticky {
    height: calc(100vh - 48px);
    overflow-x: hidden;
    overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
}

.sidebar .nav-link {
    font-weight: 500;
    color: #333;
}

.sidebar .nav-link .feather {
    margin-right: 4px;
    color: #727272;
}

.sidebar .nav-link.active {
    color: #2470dc;
}

.sidebar .nav-link:hover .feather,
.sidebar .nav-link.active .feather {
    color: inherit;
}

.sidebar-heading {
    font-size: .75rem;
}

/*
 * Navbar
 */

.navbar-brand {
    padding-top: .75rem;
    padding-bottom: .75rem;
    background-color: rgba(0, 0, 0, .25);
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
}

.navbar .navbar-toggler {
    top: .25rem;
    right: 1rem;
}

.navbar .form-control {
    padding: .75rem 1rem;
}

.form-control-dark {
    color: #fff;
    background-color: rgba(255, 255, 255, .1);
    border-color: rgba(255, 255, 255, .1);
}

.form-control-dark:focus {
    border-color: transparent;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, .25);
}

.cursor-pointer {
    cursor: pointer;
}

.ml-2 {
    margin-left: 10px;
}

.mr-2 {
    margin-right: 10px;
}


.bot-label {
    border-radius: 5px;
    border: 1px white solid;
    height: 40px;

    p {
        color: white;
        padding: 10px;
        box-sizing: border-box;
    }
}
</style>
