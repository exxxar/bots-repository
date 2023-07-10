<script setup>

import {Head} from '@inertiajs/vue3'


import SideBar from "@/Components/Shop/SideBar.vue";
import ShareMenuBar from "@/Components/Shop/ShareMenuBar.vue";
import HighlightsMenuBar from "@/Components/Shop/HighlightsMenuBar.vue";
</script>
<template>

    <Head>
        <title>CashMan - система твоего бизнеса внутри</title>
        <meta name="description" content="CashMan - система твоего бизнеса внутри"/>
    </Head>

    <notifications position="top right"/>

<!--
    <div id="preloader"><div class="spinner-border color-highlight" role="status"></div></div>
-->

    <div id="page">

        <!-- header and footer bar go here-->
        <div class="header header-fixed header-auto-show header-logo-app">
            <a @click="closeShop" class="header-title header-subtitle">Вернуться в бота</a>
            <a href="#" data-back-button class="header-icon header-icon-1"><i class="fas fa-arrow-left"></i></a>
            <a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-dark"><i class="fas fa-sun"></i></a>
            <a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-light"><i class="fas fa-moon"></i></a>
            <a href="#" data-menu="menu-highlights" class="header-icon header-icon-3"><i class="fas fa-brush"></i></a>
            <a href="#" data-menu="menu-main" class="header-icon header-icon-4"><i class="fas fa-bars"></i></a>
        </div>


        <div id="footer-bar" class="footer-bar-5 bg-dark2-dark mb-2 ml-2 mr-2 rounded-m">

            <router-link
                :active-class="'active-nav'"
                :tag="'a'" :to="prefix+'/favorites'">
                <i class="fa fa-heart"></i><span class="color-white">Избранное</span>
                <strong v-if="$route.path==prefix+'/favorites'"></strong>
            </router-link>

            <router-link
                :active-class="'active-nav'"
                :tag="'a'" :to="prefix+'/products'">
                <i class="fa fa-star"></i><span class="color-white">Продукты</span>
                    <strong v-if="$route.path==prefix+'/products'"></strong>
            </router-link>

            <router-link
                :active-class="'active-nav'"
                :tag="'a'" :to="prefix+'/home'">
                <i class="fa fa-home"></i><span class="color-white">Домой</span>
                <strong v-if="$route.path==prefix+'/home'"></strong>
            </router-link>

            <router-link
                :active-class="'active-nav'"
                :tag="'a'" :to="prefix+'/basket'">
                <i class="fa fa-heart"></i><span class="color-white">Корзина</span><em class="badge bg-green1-dark">3</em>
                <strong v-if="$route.path==prefix+'/basket'"></strong>
            </router-link>

            <router-link
                :active-class="'active-nav'"
                :tag="'a'" :to="prefix+'/settings'">
                <i class="fa fa-cog"></i><span class="color-white">Настройки</span>
                <strong v-if="$route.path==prefix+'/settings'"></strong>
            </router-link>

        </div>

<!--        <div id="footer-bar" class="footer-bar-5">
            <router-link
                :tag="'a'" to="/favorites">
                <i data-feather="heart"
                   data-feather-line="1"
                   data-feather-size="21"
                   data-feather-color="red2-dark"
                   data-feather-bg="red2-fade-light"></i><span>Понравилось</span>
            </router-link>
            <router-link :tag="'a'"
                         to="/products">
                <i data-feather="image" data-feather-line="1" data-feather-size="21" data-feather-color="green1-dark" data-feather-bg="green1-fade-light"></i><span>Продукты</span>
            </router-link>

            <router-link
                :tag="'a'" to="/home">
                <i data-feather="home" data-feather-line="1" data-feather-size="21" data-feather-color="blue2-dark" data-feather-bg="blue2-fade-light"></i><span>Домой</span>
            </router-link>

            <a href="index-media.html">
                <i data-feather="image" data-feather-line="1" data-feather-size="21"
                   data-feather-color="green1-dark" data-feather-bg="green1-fade-light"></i><span>Media</span></a>
&lt;!&ndash;            <a @click="openLink('/global-scripts/shop/home/isushibot')">
                <i data-feather="home" data-feather-line="1" data-feather-size="21"
                   data-feather-color="blue2-dark" data-feather-bg="blue2-fade-light"></i><span>Домой</span></a>&ndash;&gt;
            <a href="index-pages.html" class="active-nav">
                <i data-feather="file" data-feather-line="1"
                   data-feather-size="21" data-feather-color="brown1-dark" data-feather-bg="brown1-fade-light"></i><span>Pages</span></a>
            <a href="/settings"><i data-feather="settings" data-feather-line="1" data-feather-size="21" data-feather-color="gray2-dark" data-feather-bg="gray2-fade-light"></i><span>Settings</span></a>
        </div>-->


        <slot/>

        <!-- end of page content-->
        <ShareMenuBar/>
        <HighlightsMenuBar/>
        <SideBar/>
    </div>

</template>
<script>
import {mapGetters} from "vuex";
import baseJS from '../custom.js'

export default {
    watch: {
        $route(newRouteValue) {
            baseJS.handler()
        },
    },
    computed: {
        prefix(){
          return window.currentPath || ''
        },
        tg() {
            return window.Telegram.WebApp;
        },
        tgUser() {
            const urlParams = new URLSearchParams(this.tg.initData);
            return JSON.parse(urlParams.get('user'));
        }
    },
    mounted() {

    },
    methods: {
        openLink(url){
            this.tg.openLink(url,{
                try_instant_view:true
            })
        },
        closeShop(){
            this.tg.close()
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
