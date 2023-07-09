<script setup>

import {Head} from '@inertiajs/vue3'

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
            <a href="#" data-back-button class="header-title header-subtitle">Back to Pages</a>
            <a href="#" data-back-button class="header-icon header-icon-1"><i class="fas fa-arrow-left"></i></a>
            <a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-dark"><i class="fas fa-sun"></i></a>
            <a href="#" data-toggle-theme class="header-icon header-icon-2 show-on-theme-light"><i class="fas fa-moon"></i></a>
            <a href="#" data-menu="menu-highlights" class="header-icon header-icon-3"><i class="fas fa-brush"></i></a>
            <a href="#" data-menu="menu-main" class="header-icon header-icon-4"><i class="fas fa-bars"></i></a>
        </div>
        <div id="footer-bar" class="footer-bar-5">
            <a href="/global-scripts/shop/products/isushibot">
                <i data-feather="heart" data-feather-line="1"
                   data-feather-size="21" data-feather-color="red2-dark" data-feather-bg="red2-fade-light"></i><span>Продукты</span></a>
            <a href="index-media.html">
                <i data-feather="image" data-feather-line="1" data-feather-size="21"
                   data-feather-color="green1-dark" data-feather-bg="green1-fade-light"></i><span>Media</span></a>
            <a href="/global-scripts/shop/home/isushibot">
                <i data-feather="home" data-feather-line="1" data-feather-size="21"
                   data-feather-color="blue2-dark" data-feather-bg="blue2-fade-light"></i><span>Домой</span></a>
            <a href="index-pages.html" class="active-nav"><i data-feather="file" data-feather-line="1" data-feather-size="21" data-feather-color="brown1-dark" data-feather-bg="brown1-fade-light"></i><span>Pages</span></a>
            <a href="index-settings.html"><i data-feather="settings" data-feather-line="1" data-feather-size="21" data-feather-color="gray2-dark" data-feather-bg="gray2-fade-light"></i><span>Settings</span></a>
        </div>

        <slot/>

        <!-- end of page content-->

        <div id="menu-share"
             class="menu menu-box-bottom menu-box-detached rounded-m"
             data-menu-load="menu-share.html"
             data-menu-height="420"
             data-menu-effect="menu-over">
        </div>

        <div id="menu-highlights"
             class="menu menu-box-bottom menu-box-detached rounded-m"
             data-menu-load="menu-colors.html"
             data-menu-height="510"
             data-menu-effect="menu-over">
        </div>

        <div id="menu-main"
             class="menu menu-box-right menu-box-detached rounded-m"
             data-menu-width="260"
             data-menu-load="menu-main.html"
             data-menu-active="nav-pages"
             data-menu-effect="menu-over">
        </div>



    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["active"],
    data() {
        return {
            load: false,
            bot: null,
            company: null
        }
    },
    computed: {
        ...mapGetters(['getErrors', 'getCurrentBot', 'getCurrentCompany']),
    },
    watch: {
        getErrors: function (newVal, oldVal) {
            Object.keys(newVal).forEach(key => {
                this.$notify({
                    title: "Конструктор ботов",
                    text: newVal[key],
                    type: 'warn'
                });
            })

        }
    },
    mounted() {
        this.loadCurrentCompany()
        this.loadCurrentBot()


        window.addEventListener('store_current_bot-change-event', (event) => {
            this.bot = this.getCurrentBot
        });

        window.addEventListener('store_current_company-change-event', (event) => {
            this.company = this.getCurrentCompany
        });
    },

    methods: {
        loadCurrentCompany(company = null) {
            this.$store.dispatch("updateCurrentCompany", {
                company: company
            }).then(() => {
                this.company = this.getCurrentCompany
            })
        },
        loadCurrentBot(bot = null) {
            this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        resetCompany() {
            this.$store.dispatch("resetCurrentCompany").then(() => {
                this.company = null

                window.dispatchEvent(new CustomEvent('store_current_company-change-event'));
            })
        },
        resetBot() {
            this.$store.dispatch("resetCurrentBot").then(() => {
                this.bot = null

                window.dispatchEvent(new CustomEvent('store_current_bot-change-event'));
            })
        },
        stopAllDialogs() {
            this.$store.dispatch("stopDialogs").then((response) => {
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Все диалоги остановлены",
                    type: 'success'
                });

            }).catch(err => {
            })
        },
        reloadWebhooks() {
            this.load = true
            this.$notify({
                title: "Конструктор ботов",
                text: "Процедура обновления зависимостей началась",
            });
            axios.get("/bot/register-webhooks").then(() => {
                this.load = false
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Зависимости успешно обновлены!",
                    type: 'success'
                });
            }).catch(() => {
                this.load = false

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Неудалось обновить зависимости",
                    type: 'error'
                });
            })
        },
    }
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
