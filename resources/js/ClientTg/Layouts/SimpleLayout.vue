<script setup>

import {Head} from '@inertiajs/vue3'


</script>
<template>

    <Head>
        <title>CashMan - система твоего бизнеса внутри</title>
        <meta name="description" content="CashMan - система твоего бизнеса внутри"/>
    </Head>

    <header data-bs-theme="dark">
        <div class="text-bg-dark collapse" id="navbarHeader" style="">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-md-7 py-4">
                        <h4>О нас</h4>
                        <p class="text-body-primary mb-2">
                            {{bot.long_description || 'Без описания'}}
                        </p>
                    </div>
                    <div class="col-sm-4 offset-md-1 py-4" v-if="bot.company">
                        <h4>Контакты</h4>
                        <ul class="list-unstyled">
                            <li v-if="(bot.company.phones||[]).length>0"><p class="mb-0">Телефон</p></li>
                            <li v-if="(bot.company.phones||[]).length>0" v-for="phone in bot.company.phones">
                                <a :href="'tel:'+phone" class="text-white">{{phone}}</a>
                            </li>
                            <li v-if="(bot.social_links||[]).length>0"><p class="mb-0">Ссылки</p></li>
                            <li v-if="(bot.social_links||[]).length>0" v-for="link in bot.social_links"><a :href="link.url" class="text-white">{{link.title || 'ссылка'}}</a></li>
                            <li><p class="mb-0">Почта</p></li>
                            <li><a :href="'mailto:'+bot.company.email"
                                   v-if="bot.company.email"
                                   class="text-white">{{bot.company.email}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a href="#" class="navbar-brand d-flex align-items-center px-3">
                    <strong><i class="fa-brands fa-shopify mr-2"></i> {{ $route.meta.title || 'Меню' }}</strong>
                </a>
                <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </header>

    <slot/>

    <footer class="text-body-secondary" style="padding:0px 0px 90px 0px;">

        <div class="container g-2 d-flex justify-content-center flex-column align-items-center">

            <button
                v-if="$route.name!='FeedBackV2'"
                @click="goToFeedBackPage"
                class="btn btn-link mb-2 w-100 p-3 text-primary">Обратная связь</button>

            <p class="mb-3 text-center" v-html="bot.company.description"></p>
            <p class="mb-3 text-center" v-if="bot.company.address"><i class="fa-solid fa-map-location-dot mr-2"></i>{{bot.company.address}}</p>
            <p class="mb-0">{{bot.company.title}}©2024</p>
            <p class="d-flex justify-content-center my-3">
                <a href="javascript:void(0)" @click="scrollTop"><i class="fa-solid fa-arrow-up mr-2"></i>Вернуться наверх</a>
            </p>
        </div>
    </footer>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    watch: {
        $route(newRouteValue) {
            console.log("router",this.$route.name)

            this.$preloader.show();
            this.$nextTick(() => {
                document.body.scrollTop = document.documentElement.scrollTop = 0;
            })
        },
    },
    computed: {
        ...mapGetters(['getSelf']),
        tg() {
            return window.Telegram.WebApp;
        },
        bot(){
            return window.currentBot
        },
    },
    mounted() {
        this.changeTheme(this.tg.colorScheme)
        this.tg.BackButton.show()

        this.tg.BackButton.onClick(()=>{
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item=>item.click())

            this.$router.back()
        })
    },
    methods: {
        goToFeedBackPage(){
            this.$router.push({name: 'FeedBackV2'})
        },
        changeTheme(name) {
            let themes = document.querySelectorAll("[data-bs-theme]")

            themes.forEach(item => {
                console.log("item", item)
                item.setAttribute("data-bs-theme", name)
            })
        },
        scrollTop(){
            window.scrollTo(0, 80);
        },
        openLink(url) {
            this.tg.openLink(url, {
                try_instant_view: true
            })
        },
        closeShop() {
            this.tg.close()
        },

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
