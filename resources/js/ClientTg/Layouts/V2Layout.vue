<script setup>

import {Head} from '@inertiajs/vue3'
import CompanyInfo from "@/ClientTg/Components/V2/Admin/CompanyInfo.vue";

import ScheduleList from "@/ClientTg/Components/V2/Shop/ScheduleList.vue";

import ProductInfo from "@/ClientTg/Components/V2/Shop/ProductInfo.vue"
</script>
<template>

    <Head>
        <title>CashMan - система твоего бизнеса внутри</title>
        <meta name="description" content="CashMan - система твоего бизнеса внутри"/>
    </Head>

    <header
        v-if="!needHideMenu"
        data-bs-theme="dark">
        <div class="navbar shadow shadow-sm">
            <div class="container flex-row-reverse p-2">

                <a
                    @click="goTo('ProfileV2')"
                    class="badge bg-primary btn"
                    v-if="loaded_cashback"
                    href="javascript:void(0)">
                    {{cashback || 0}} <i class="fa-solid fa-ruble-sign"></i>
                </a>

                <span
                    data-bs-toggle="modal" data-bs-target="#bot-info-modal"
                    class="text-primary fw-bold cursor-pointer">{{ bot.title || 'Магазин' }}</span>

                <button class="btn btn-link rounded-0 border-0 p-1" type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </header>

    <slot/>
    <ProductInfo/>


    <footer class="text-body-secondary" style="padding:0px 0px 90px 0px;">

        <div class="container d-flex justify-content-center flex-column align-items-center">

            <button
                v-if="$route.name!='FeedBackV2'"
                @click="goTo('FeedBackV2')"
                class="btn btn-link mb-2 w-100 p-3 text-primary">Обратная связь
            </button>


            <p class="text-center mb-3">
                <span v-html="bot.company.description"></span>
                <br>
                <a
                    v-if="(getSelf||{is_admin:false}).is_admin"
                    data-bs-toggle="modal" data-bs-target="#edit-shop-footer-description-modal"
                    href="javascript:void(0)" class="text-primary ml-2" style="font-size:12px;"><i
                    class="fa-solid fa-pen-to-square"></i> редактировать</a>
            </p>


            <p class="mb-3 text-center" v-if="bot.company.address"><i
                class="fa-solid fa-map-location-dot mr-2"></i>{{ bot.company.address }}</p>
            <p class="mb-0">{{ bot.company.title }}©{{ (new Date()).getFullYear() }}</p>
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
                > {{ preparedMenuItem['profile']?.title || 'Профиль' }} </a></li>
                <li class="p-1"><a
                    v-bind:class="{'fw-bold':$route.name==='CatalogV2'}"
                    @click="goTo('CatalogV2')"
                    href="javascript:void(0)"
                    class="text-decoration-none fw-normal"
                > {{ preparedMenuItem['shop']?.title || 'Магазин' }} </a></li>
                <li class="p-1"><a
                    v-bind:class="{'fw-bold':$route.name==='ShopCartV2'}"
                    @click="goTo('ShopCartV2')"
                    href="javascript:void(0)"
                    class="text-decoration-none fw-normal"
                > {{ preparedMenuItem['basket']?.title || 'Корзина' }} <span class="fw-bold" v-if="cartTotalCount>0">({{
                        cartTotalCount
                    }})</span></a></li>
                <li class="p-1"><a
                    v-bind:class="{'fw-bold':$route.name==='OrdersV2'}"
                    @click="goTo('OrdersV2')"
                    href="javascript:void(0)"
                    class="text-decoration-none fw-normal"
                > {{ preparedMenuItem['history']?.title || 'История заказов' }} </a></li>
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
                            :href="'tel:'+bot.company.phones[0]"
                            class="text-decoration-none fw-bold">{{ bot.company.phones[0] || '-' }}</a>
                    </li>
                    <li v-if="links.inst||links.vk"><p class="mb-0">Ссылки</p></li>
                    <li v-if="links.inst">
                        <a target="_blank"
                           :href="'http://instagram.com/'+links.inst"
                           style="font-size:12px;"
                           class="text-primary">
                            <i class="fa-brands fa-instagram mr-1"></i>
                            {{ links.inst || 'ссылка' }}
                        </a>
                    </li>
                    <li v-if="links.vk">
                        <a target="_blank"
                           :href="'https://vk.com/'+links.vk"
                           style="font-size:12px;"
                           class="text-primary">
                            <i class="fa-brands fa-vk mr-1"></i>
                            {{ links.vk || 'ссылка' }}
                        </a>
                    </li>
                    <li v-if="bot.company.email"><p class="mb-0">Почта</p></li>
                    <li><a :href="'mailto:'+bot.company.email"
                           target="_blank"
                           v-if="bot.company.email"
                           class="text-decoration-none fw-bold"
                    >{{ bot.company.email }}</a></li>
                    <li v-if="links.site"><p class="mb-0">Сайт</p></li>
                    <li><a :href="links.site"
                           target="_blank"
                           v-if="links.site"
                           class="text-decoration-none fw-bold"
                    >{{ links.site }}</a></li>
                    <template v-if="bot.settings?.manager?.link">
                        <li><p class="mt-3 mb-0">Связаться с сотрудником</p></li>
                        <li style="position: sticky;bottom: 0px;"><a :href="bot.settings?.manager?.link"
                               target="_blank"
                               class="btn btn-primary p-3 w-100"
                        >{{ bot.settings?.manager?.title || 'Написать' }}</a></li>
                    </template>
                </ul>
            </div>

            <div class="dropdown" v-if="(getSelf||{is_admin:false}).is_admin">
                <button class="btn btn-light w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                    Тема оформления
                </button>
                <ul class="dropdown-menu w-100 bg-light" v-if="themes.length>0">
                    <li v-for="(theme, index) in themes">
                        <button type="button"
                                @click="switchTheme(index)"
                                v-bind:class="{'active':currentTheme.indexOf(theme.href)!=-1}"
                                class="list-group-item list-group-item-action p-2 w-100 text-primary"
                                aria-current="true">
                            {{ theme.title || '-' }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="bot-info-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable" >
            <div class="modal-content">

                <div class="modal-body" style="max-height:400px;">
                                 <p class="mb-0  fw-bold d-flex flex-column align-items-center"
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

                    <div
                        v-if="!isWork"
                        class="my-3 alert alert-primary" role="alert">
                        В данный момент мы <span class="text-primary fw-bold">не работаем</span>.
                        Вы можете ознакомиться с нашим
                        <span
                            data-bs-toggle="modal" data-bs-target="#schedule-list-display"
                            class="text-primary fw-bold text-decoration-underline cursor-pointer">графиком работы</span>.
                    </div>


                <p class="text-center mb-3">
                    <span v-html="bot.company.description"></span>
                    <br>
                    <a
                        v-if="(getSelf||{is_admin:false}).is_admin"
                        data-bs-toggle="modal" data-bs-target="#edit-shop-footer-description-modal"
                        href="javascript:void(0)" class="text-primary ml-2 my-3" style="font-size:12px;"><i
                        class="fa-solid fa-pen-to-square"></i> Редактировать</a>
                </p>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="schedule-list-display" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">График работы</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ScheduleList
                        v-if="bot"
                        :schedule="bot.company.schedule"></ScheduleList>
                    <p v-if="(getSelf||{is_admin:false}).is_admin" class="my-2 d-flex justify-content-center"><a
                        data-bs-toggle="modal" data-bs-target="#edit-shop-footer-description-modal"
                        href="javascript:void(0)" class="text-primary ml-2" style="font-size:12px;"><i
                        class="fa-solid fa-pen-to-square"></i> редактировать</a></p>
                </div>
            </div>
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
            </div>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            loaded_cashback:false,
            cashback:0,
            install_modal: null,
            currentTheme: '',
            themes: []
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
            /*  this.$nextTick(() => {
                  document.body.scrollTop = document.documentElement.scrollTop = 0;
              })*/
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
        self(){
          return window.self || null
        },
        isWork() {
            if (!window.isCorrectSchedule(this.bot.company.schedule))
                return true

            return (this.bot.company || {is_work: true}).is_work
        },
        preparedMenuItem() {
            if (!this.bot.settings)
                return []

            let data = this.bot.settings["icons"] || []

            let arr = [];
            data.forEach(item => {
                arr[item.slug] = item
            })


            return arr
        },
        needHideMenu() {
            let urlParams = new URLSearchParams(window.location.search);
            return urlParams.has('hide_menu') || this.$route.meta.hide_menu
        },
        links() {
            return {
                inst: (this.bot.company.links || {inst: null}).inst || null,
                vk: (this.bot.company.links || {vk: null}).vk || null,
                map_link: (this.bot.company.links || {map_link: null}).map_link || null,
                site: (this.bot.company.links || {site: null}).site || null,
            }
        }
    },

    mounted() {

        this.loaded_cashback = false
        this.$store.dispatch("loadSelf").then(() => {
            window.self = this.getSelf
            this.cashback = this.getSelf.cashBack?.amount || 0
            this.loaded_cashback = true
        })

        /*    this.tg.checkHomeScreenStatus((resp) => {
                let needInstall = localStorage.getItem("cashman_need_install_" + (this.bot.bot_domain || 'any_bot')) || null
                if (resp.status === 'missed' && needInstall == null) {
                    this.tg.addToHomeScreen()
                    localStorage.setItem("cashman_need_install_" + (this.bot.bot_domain || 'any_bot'), "false")
                }

            })*/

        if (this.bot.settings?.themes) {
            this.themes = this.bot.settings?.themes || []
        }

        console.log("theme", window.theme)

        if (window.theme) {
            let changeTheme = document.querySelector("#theme")
            changeTheme.href = window.theme
            localStorage.setItem("cashman_global_client_theme_" + (this.bot.bot_domain || 'any_bot'), changeTheme.href)

        }


        let theme = localStorage.getItem("cashman_global_client_theme_" + (this.bot.bot_domain || 'any_bot')) || null

        if (theme) {
            this.$nextTick(() => {
                this.currentTheme = theme
            })
        }

        this.changeTheme(this.tg.colorScheme)

        this.tg.expand()

        this.tg.BackButton.hide()

    },
    methods: {
        switchTheme(index) {
            let changeTheme = document.querySelector("#theme")
            changeTheme.href = this.themes[index].href //`./theme${index}.bootstrap.min.css`
            localStorage.setItem("cashman_global_client_theme_" + (this.bot.bot_domain || 'any_bot'), changeTheme.href)


            this.$nextTick(() => {
                this.currentTheme = changeTheme.href
            })

            this.$store.dispatch("updateBotTheme", {
                theme: changeTheme.href
            }).then(() => {
                this.$notify({
                    title: 'Изменение темы',
                    text: 'Тема успешно обновлена',
                    type: 'success'
                })
            }).catch(() => {
                this.$notify({
                    title: 'Изменение темы',
                    text: 'Ошибка изменения темы',
                    type: 'error'
                })
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

</style>
