<script setup>

import {Head} from '@inertiajs/vue3'
import CompanyInfo from "@/ClientTg/Components/V2/Admin/CompanyInfo.vue";


</script>
<template>

    <Head>
        <title>CashMan - система твоего бизнеса внутри</title>
        <meta name="description" content="CashMan - система твоего бизнеса внутри"/>
    </Head>

    <header data-bs-theme="dark">

        <div class="navbar shadow shadow-sm">
            <div class="container flex-row-reverse p-2">

                <p class="mb-0  fw-bold d-flex flex-column"
                   style="font-size:12px;"
                   v-if="bot.company">
                    <span v-if="bot.company.address" class="text-primary">
                          <i class="fa-solid fa-location-dot mr-1"></i> {{ bot.company.address }}
                    </span>
                    <span class="text-primary" v-else>{{ bot.title || 'Магазин' }}</span>

                    <span v-if="(bot.company.phones||[]).length>0"
                          class="small d-flex justify-content-end">
                    <a href="javascript:void(0)" class=" text-secondary fw-bold">{{ bot.company.phones[0] }}</a>
                </span>
                </p>
                <p class="mb-0 text-primary"
                   style="font-size:12px;"
                   v-else>{{ bot.title || 'Бот' }}</p>
                <button class="btn btn-link rounded-0 border-0 p-1" type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu">
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
                @click="goTo('FeedBackV2')"
                class="btn btn-link mb-2 w-100 p-3 text-primary">Обратная связь
            </button>


            <p class="text-center mb-3">
                <span v-html="bot.company.description"></span>
                <a
                    v-if="(getSelf||{is_admin:false}).is_admin"
                    data-bs-toggle="modal" data-bs-target="#edit-shop-footer-description-modal"
                    href="javascript:void(0)" class="text-primary ml-2" style="font-size:12px;"><i
                    class="fa-solid fa-pen-to-square"></i> редактировать</a>
            </p>


            <p class="mb-3 text-center" v-if="bot.company.address"><i
                class="fa-solid fa-map-location-dot mr-2"></i>{{ bot.company.address }}</p>
            <p class="mb-0">{{ bot.company.title }}©2024</p>
            <p class="d-flex justify-content-center my-3">
                <a href="javascript:void(0)" @click="scrollTop"><i class="fa-solid fa-arrow-up mr-2"></i>Вернуться
                    наверх</a>
            </p>
        </div>
    </footer>


    <div class="offcanvas offcanvas-start custom-offcanvas"
         style="width: 70%;border-radius: 0px 10px 10px 0px;"
         tabindex="-1" id="sidebar-menu" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h6 class="offcanvas-title" id="offcanvasExampleLabel">{{ bot.title || 'Магазин' }}</h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>


        <div class="offcanvas-body">

            <ul class="list-unstyled">
                <li class="p-1"><a
                    v-bind:class="{'fw-bold':$route.name==='MenuV2'}"
                    @click="goTo('MenuV2')"
                    href="javascript:void(0)"
                    class="text-decoration-none fw-normal"
                > Главное меню</a></li>
                <li class="p-1"><a
                    v-bind:class="{'fw-bold':$route.name==='ProfileV2'}"
                    @click="goTo('ProfileV2')"
                    href="javascript:void(0)"
                    class="text-decoration-none fw-normal"
                > Профиль</a></li>
                <li class="p-1"><a
                    v-bind:class="{'fw-bold':$route.name==='CatalogV2'}"
                    @click="goTo('CatalogV2')"
                    href="javascript:void(0)"
                    class="text-decoration-none fw-normal"
                > Каталог товаров</a></li>
                <li class="p-1"><a
                    v-bind:class="{'fw-bold':$route.name==='ShopCartV2'}"
                    @click="goTo('ShopCartV2')"
                    href="javascript:void(0)"
                    class="text-decoration-none fw-normal"
                > Корзина <span class="fw-bold" v-if="cartTotalCount>0">({{ cartTotalCount }})</span></a></li>
                <li class="p-1"><a
                    v-bind:class="{'fw-bold':$route.name==='OrdersV2'}"
                    @click="goTo('OrdersV2')"
                    href="javascript:void(0)"
                    class="text-decoration-none fw-normal"
                > История заказов</a></li>
                <li class="p-1"><a
                    v-bind:class="{'fw-bold':$route.name==='CashBackV2'}"
                    @click="goTo('CashBackV2')"
                    href="javascript:void(0)"
                    class="text-decoration-none fw-normal"
                > CashBack</a></li>
                <li class="p-1"><a
                    v-bind:class="{'fw-bold':$route.name==='FeedBackV2'}"
                    @click="goTo('FeedBackV2')"
                    href="javascript:void(0)"
                    class="text-decoration-none fw-normal"
                > Оставить отзыв</a></li>
            </ul>

            <div class="border-top my-3 "></div>
            <div
                class="p-2"
                v-if="bot.company">
                <ul class="list-unstyled">
                    <li v-if="(bot.company.phones||[]).length>0"><p class="mb-0">Телефон</p></li>
                    <li v-if="(bot.company.phones||[]).length>0"
                        class="mb-2">
                        <a
                            target="_blank"
                            :href="'tel:'+bot.company.phones[0]" class="text-decoration-none fw-bold">{{ bot.company.phones[0]||'-' }}</a>
                    </li>
                    <li><p class="mb-0">Ссылки</p></li>
                    <li>
                        <a target="_blank"
                           :href="'http://instagram.com/'+bot.company.links.inst"
                           style="font-size:12px;"
                           class="text-primary">
                            <i class="fa-brands fa-instagram mr-1"></i>
                            {{ bot.company.links.inst || 'ссылка' }}
                        </a>
                    </li>
                    <li>
                        <a target="_blank"
                           :href="'https://vk.com/'+bot.company.links.vk"
                           style="font-size:12px;"
                           class="text-primary">
                            <i class="fa-brands fa-vk mr-1"></i>
                            {{ bot.company.links.vk || 'ссылка' }}
                        </a>
                    </li>
                    <li v-if="bot.company.email"><p class="mb-0">Почта</p></li>
                    <li><a :href="'mailto:'+bot.company.email"
                           v-if="bot.company.email"
                           class="text-decoration-none fw-bold"
                    >{{ bot.company.email }}</a></li>
                </ul>
            </div>
            <!--            <div class="border-top my-3 "></div>
                        <div class="dropdown">
                            <button class="btn btn-light w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Тема оформления
                            </button>
                            <ul class="dropdown-menu w-100 bg-light">
                                <li  v-for="(theme, index) in themes">
                                    <button type="button"
                                            @click="switchTheme(index)"
                                            v-bind:class="{'active':currentTheme.indexOf(theme.href)!=-1}"
                                            class="list-group-item list-group-item-action p-2 w-100 text-primary" aria-current="true">
                                        {{theme.title || '-'}}
                                    </button>
                                </li>
                            </ul>
                        </div>-->
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="edit-shop-footer-description-modal" tabindex="-1"
         aria-labelledby="edit-shop-footer-description" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Редактор</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <CompanyInfo></CompanyInfo>

                </div>
                <div class="modal-footer p-2">

                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            currentTheme: '',
            themes: [

                {
                    title: 'Тема 3',
                    href: '/theme5.bootstrap.min.css',
                },
                {
                    title: 'Тема 4',
                    href: '/theme6.bootstrap.min.css',
                },

                {
                    title: 'Тема 5',
                    href: '/theme8.bootstrap.min.css',
                },
                {
                    title: 'Тема 6',
                    href: '/theme9.bootstrap.min.css',
                },
                {
                    title: 'Тема 7',
                    href: '/theme10.bootstrap.min.css',
                }
                ,
                {
                    title: 'Тема 8',
                    href: '/theme11.bootstrap.min.css',
                },
                {
                    title: 'Тема 9',
                    href: '/theme12.bootstrap.min.css',
                },
                {
                    title: 'Тема 10',
                    href: '/theme13.bootstrap.min.css',
                },
                {
                    title: 'Тема 11',
                    href: '/theme14.bootstrap.min.css',
                },
                {
                    title: 'Тема 12',
                    href: '/theme15.bootstrap.min.css',
                },
                {
                    title: 'Тема 13',
                    href: '/theme16.bootstrap.min.css',
                }
            ]
        }
    },
    watch: {
        $route(newRouteValue) {
            let theme = localStorage.getItem("cashman_global_client_theme") || null

            if (theme) {
                this.$nextTick(() => {
                    this.currentTheme = theme
                })
            }


            this.$preloader.show();
            this.$nextTick(() => {
                document.body.scrollTop = document.documentElement.scrollTop = 0;
            })
        },
    },
    computed: {
        ...mapGetters(['getSelf', 'cartTotalCount']),
        tg() {
            return window.Telegram.WebApp;
        },
        bot() {
            return window.currentBot
        },
    },

    mounted() {

        let theme = localStorage.getItem("cashman_global_client_theme") || null

        if (theme) {
            this.$nextTick(() => {
                this.currentTheme = theme
            })
        }

        this.changeTheme(this.tg.colorScheme)
        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })
    },
    methods: {
        switchTheme(index) {
            let changeTheme = document.querySelector("#theme")
            changeTheme.href = this.themes[index].href //`./theme${index}.bootstrap.min.css`
            localStorage.setItem("cashman_global_client_theme", changeTheme.href)


            this.$nextTick(() => {
                this.currentTheme = changeTheme.href
            })


        },
        goTo(name) {
            this.$router.push({name: name})
        },

        changeTheme(name) {
            let themes = document.querySelectorAll("[data-bs-theme]")

            themes.forEach(item => {
                item.setAttribute("data-bs-theme", name)
            })
        },
        scrollTop() {
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

/*.custom-offcanvas {
    border: none;
    box-shadow: inset 1px 1px rgba(255, 255, 255, .2), inset -1px -1px rgba(255, 255, 255, .1), 1px 3px 24px -1px rgba(0, 0, 0, .15);
    background-color: #000000b3;
    background-image: linear-gradient(125deg, rgba(255, 255, 255, .3), rgba(255, 255, 255, .2) 70%);
    -webkit-backdrop-filter: blur(5px);
    backdrop-filter: blur(5px);
    color:white;
}*/
</style>
