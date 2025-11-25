<script setup>

import MainMenuItem from "@/ClientTg/Components/V2/Shop/MainMenuItem.vue";
import ShopScriptEditor from "@/ClientTg/Components/V2/Admin/ScriptEditors/Shop/ShopScriptEditor.vue";
import StoryList from "@/ClientTg/Components/V2/Shop/Stories/StoryList.vue";
</script>
<template>

    <div class="container py-3"
         v-touch:swipe.left="doSwipeLeft"
         v-touch:swipe.right="doSwipeRight"
         v-if="getSelf">

        <div
            v-if="script_data.is_disabled"
            class="alert alert-danger mb-3">
            <p class="mb-0" v-html="script_data.disabled_text||'-'"></p>
        </div>

        <StoryList
            v-if="(stories||[]).length>0"
            :stories="stories||[]"/>

        <template v-if="getSelf.is_admin&&(stories||[]).length>0">
            <a href="javascript:void(0)"
               style="font-size:10pt;"
               class="text-center d-block my-2"
               @click="goTo('StoryManagerV2')">Редактировать</a>
        </template>

        <h6 class="opacity-75 mb-3 text-center"><i class="fa-solid fa-house-chimney mr-2 text-primary"></i>Доступные
            сервисы</h6>

        <div class="row g-2" v-if="bot">
            <div class="col-12">
                <div
                    v-if="!isWork"
                    class="alert alert-light" role="alert">
                    В данный момент мы <span class="text-primary fw-bold">не работаем</span>.
                    Вы можете ознакомиться с нашим
                    <span
                        data-bs-toggle="modal" data-bs-target="#schedule-list-display"
                        class="text-primary fw-bold text-decoration-underline cursor-pointer">графиком работы</span>.
                </div>
            </div>
        </div>
        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-2">
            <div class="col">
                <MainMenuItem
                    v-if="preparedMenuItem['profile']?.is_visible"
                    :route="'ProfileV2'"
                    :default-image="'profile.png'"
                    :default-text="'Профиль'"
                    :item="preparedMenuItem['profile']||null"/>
            </div>

            <template v-if="bot.settings?.can_use_booking||false">
                <div class="col">
                    <MainMenuItem
                        v-if="preparedMenuItem['booking']?.is_visible"
                        :route="'TableBookingV2'"
                        :default-image="'profile.png'"
                        :default-text="'Бронирование столика'"
                        :item="preparedMenuItem['booking']||null"/>
                </div>
            </template>
            <div class="col">
                <MainMenuItem
                    v-if="preparedMenuItem['shop']?.is_visible"
                    :disabled="script_data.is_disabled"
                    :route="'CatalogV2'"
                    :default-image="'shop.png'"
                    :default-text="'Магазин'"
                    :item="preparedMenuItem['shop']||null"/>
            </div>

            <div class="col">
                <MainMenuItem
                    v-if="preparedMenuItem['basket']?.is_visible"
                    :route="'ShopCartV2'"
                    :default-image="'basket.png'"
                    :default-text="'Корзина'"
                    :item="preparedMenuItem['basket']||null">
                    <template #post-text>
                        <span class="badge bg-primary" v-if="cartTotalCount>0">{{ cartTotalCount }}</span>
                    </template>
                </MainMenuItem>
            </div>


            <div class="col">
                <MainMenuItem
                    v-if="preparedMenuItem['history']?.is_visible"
                    :route="'OrdersV2'"
                    :default-image="'history.png'"
                    :default-text="'История заказов'"
                    :item="preparedMenuItem['history']||null"/>
            </div>

            <div class="col" v-if="script_data.wheel_of_fortune">
                <MainMenuItem
                    v-if="preparedMenuItem['events']?.is_visible"
                    :disabled="!script_data.wheel_of_fortune.can_play"
                    :route="'WheelOfFortuneV2'"
                    :default-image="'events.png'"
                    :default-text="'Розыгрыши'"
                    :item="preparedMenuItem['events']||null"/>

            </div>


            <div class="col">
                <MainMenuItem
                    v-if="preparedMenuItem['about']?.is_visible"
                    :route="'ContactsV2'"
                    :default-image="'contacts.png'"
                    :default-text="'О Нас & Контакты'"
                    :item="preparedMenuItem['about']||null"/>
            </div>
        </div>

        <div class="divider my-3"> Дополнительно</div>
        <button
            v-if="preparedMenuItem['wheel_of_fortune_btn']?.is_visible"
            style="box-shadow: 1px 1px 6px 0px #0000004a;"
            @click="switchToPage('Колесо фортуны')"
            class="btn btn-outline-light text-primary w-100 p-3 rounded-3 shadow-sm mb-2">

            <i class="fa fa-dot-circle"></i>
            {{ preparedMenuItem['wheel_of_fortune_btn'].title || 'Колесо фортуны' }}

        </button>

        <button
            style="box-shadow: 1px 1px 6px 0px #0000004a;"
            v-if="preparedMenuItem['friends_btn']?.is_visible"
            @click="switchToPage('Пригласить друзей')"
            class="btn  btn-outline-light text-primary mb-2 w-100 p-3 rounded-3 shadow-sm ">

            <i class="fa fa-people-carry "></i>
            {{ preparedMenuItem['friends_btn'].title || 'Пригласить друзей' }}
        </button>

        <template v-if="getSelf.is_admin">
            <h6 class="opacity-75 my-3 text-center"><i
                class="fa-solid fa-house-lock mr-2 text-primary"></i>Управление магазином</h6>

            <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4  g-2">
                <div class="col">
                    <button type="button"
                            @click="goTo('SendInvoiceV2')"
                            style="min-height:250px;"
                            class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                        <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                            <img v-lazy="'/images/shop-v2-2/clients.png'" class="img-fluid" alt="">

                            <p class="my-2">Счет на оплату</p>
                        </div>

                    </button>
                </div>
                <div class="col">
                    <button type="button"
                            @click="goTo('ShopV2')"
                            style="min-height:250px;"
                            class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                        <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                            <img v-lazy="'/images/shop-v2-2/products.png'" class="img-fluid" alt="">

                            <p class="my-2">Управление товарами</p>
                        </div>

                    </button>
                </div>
                <div class="col" v-if="script_data">
                    <button type="button"
                            data-bs-toggle="modal" data-bs-target="#script-setting-editor"
                            style="min-height:250px;"
                            class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                        <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                            <img v-lazy="'/images/shop-v2-2/orders.png'" class="img-fluid" alt="">

                            <p class="my-2">Настройка магазина</p>
                        </div>

                    </button>
                </div>

                <div class="col">
                    <button type="button"
                            @click="goTo('PartnersV2')"
                            style="min-height:250px;"
                            class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                        <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                            <img
                                style="max-width:150px;"
                                v-lazy="'/images/shop-v2-2/partners.png'" class="img-fluid" alt="">

                            <p class="my-2">Работа с партнерами</p>
                        </div>

                    </button>
                </div>

                <div class="col">
                    <button type="button"
                            @click="goTo('StoryManagerV2')"
                            style="min-height:250px;"
                            class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                        <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                            <img v-lazy="'/images/shop-v2-2/promo.png'" class="img-fluid" alt="">

                            <p class="my-2">Управление историями</p>
                        </div>

                    </button>
                </div>

                <div class="col">
                    <button type="button"
                            @click="goTo('TablesManagerV2')"
                            style="min-height:250px;"
                            v-if="script_data.need_table_list||false"
                            class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                        <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                            <img v-lazy="'/images/shop-v2-2/clients.png'" class="menu-item-img img-fluid" alt="">

                            <p class="my-2">Управление столиками</p>
                        </div>

                    </button>
                </div>


            </div>
            <h6 class="opacity-75 my-3 text-center"><i
                class="fa-solid fa-house-lock mr-2 text-primary"></i>Другие админ сервисы</h6>


            <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4  g-2">
                <div class="col">
                    <button type="button"
                            @click="goTo('ClientsV2')"
                            style="min-height:250px;"
                            class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                        <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                            <img
                                style="max-width:150px;"
                                v-lazy="'/images/shop-v2-2/clients.png'" class="img-fluid" alt="">

                            <p class="my-2">Управление клиентами</p>
                        </div>

                    </button>
                </div>


                <div class="col">
                    <button type="button"
                            @click="goTo('LinkManagerV2')"
                            style="min-height:250px;"
                            class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                        <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                            <img
                                style="max-width:150px;"
                                v-lazy="'/images/shop-v2-2/utm.png'" class="img-fluid" alt="">

                            <p class="my-2">UTM-метки</p>
                        </div>

                    </button>
                </div>

                <div class="col">
                    <button type="button"
                            @click="goTo('MailingV2')"
                            style="min-height:250px;"
                            class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                        <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                            <img
                                style="max-width:150px;"
                                v-lazy="'/images/shop-v2-2/mail.png'" class="img-fluid" alt="">

                            <p class="my-2">Управление рассылками</p>
                        </div>

                    </button>
                </div>


                <div class="col">
                    <button type="button"
                            @click="goTo('AdminOrdersV2')"
                            style="min-height:250px;"
                            class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                        <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                            <img
                                style="max-width:150px;"
                                v-lazy="'/images/shop-v2-2/orders.png'" class="img-fluid" alt="">

                            <p class="my-2">Управление заказами</p>
                        </div>

                    </button>
                </div>

                <div class="col">
                    <button type="button"
                            @click="goTo('PromoCodesV2')"
                            style="min-height:250px;"
                            class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                        <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                            <img
                                style="max-width:150px;"
                                v-lazy="'/images/shop-v2-2/promo.png'" class="img-fluid" alt="">

                            <p class="my-2">Управление промокодами</p>
                        </div>

                    </button>
                </div>


                <div class="col">
                    <button type="button"
                            @click="goTo('StatisticV2')"
                            style="min-height:250px;"
                            class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                        <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                            <img
                                style="max-width:150px;"
                                v-lazy="'/images/shop-v2-2/statistic.png'" class="img-fluid" alt="">

                            <p class="my-2">Статистика</p>
                        </div>

                    </button>
                </div>


            </div>

            <!--            <button
                            style="box-shadow: 1px 1px 6px 0px #0000004a;"
                            @click="switchToPage('/adminmenu')"
                            class="btn  btn-outline-light text-primary mb-2 w-100 p-3 rounded-3 shadow-sm ">

                            <i class="fa fa-people-carry "></i> Основная админ. панель
                        </button>-->
        </template>

    </div>

    <div class="menu-preloader" v-if="!loadScriptData">
        <div class="d-flex flex-column align-items-center">
            <div class="spinner-grow bg-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="py-2">Загружаем...</p>
        </div>
    </div>

    <nav

        class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
        style="border-radius:10px 10px 0px 0px;">

        <button
            v-if="preparedMenuItem['main_menu_btn']?.is_visible"
            style="box-shadow: 1px 1px 6px 0px #0000004a;"
            @click="startMenu"
            class="btn btn-primary w-100 p-3 rounded-3 shadow-lg">

            <i class="fa-brands fa-telegram"></i> {{ preparedMenuItem['main_menu_btn'].title || 'Главное меню' }}
        </button>
    </nav>

    <div class="modal fade" id="script-setting-editor" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Редактор</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ShopScriptEditor v-if="loadScriptData"
                                      v-model="script_data">
                    </ShopScriptEditor>
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
            loadScriptData: false,
            stories: [],

            script_data: {
                is_disabled: true,
                wheel_of_fortune: {
                    can_play: false,
                }
            },
        }
    },
    computed: {
        ...mapGetters(['getSelf', 'cartTotalCount', 'getStories']),
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
        isWork() {
            if (!window.isCorrectSchedule(this.bot.company.schedule))
                return true

            return (this.bot.company || {is_work: true}).is_work
        },
        tg() {
            return window.Telegram.WebApp;
        },
        bot() {
            return window.currentBot
        },
    },
    mounted() {
        this.tg.BackButton.show()

        if (this.bot.settings?.self_updated) {
            this.script_data = this.bot.settings
            this.loadStories()
            this.loadScriptData = true
        } else
            this.loadScriptModuleData().then(() => {
                this.loadStories()
            })


        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
            /*if (this.$route.name === "MenuV2")
                this.tg.close()
            else*/

        })
    },
    methods: {
        doSwipeLeft() {
            this.$router.push({name: 'ShopCartV2'})

        },
        doSwipeRight() {
            this.$router.push({name: 'CatalogV2'})

        },
        goTo(name) {
            this.$router.push({name: name})
        },
        switchToPage(page) {
            this.$store.dispatch("switchToPage", {
                page: page
            }).then(() => {
                this.tg.close();
            }).catch(() => {
                this.$notify({
                    title: "Упс...",
                    text: "Данная функция временно недоступна...",
                    type: "error"
                })
            })
        },
        startMenu() {
            this.$store.dispatch("switchToMainMenu")
            this.tg.close();


        },
        loadStories() {
            this.$store.dispatch("loadStories")
                .then((resp) => {

                    this.stories = this.getStories || []

                })
        },
        loadScriptModuleData() {
            this.loadScriptData = false
            return this.$store.dispatch("loadShopModuleData").then((resp) => {
                let data = resp.data
                this.script_data = []

                this.$nextTick(() => {
                    if (data)
                        Object.keys(data).forEach(item => {
                            if (item)
                                this.script_data[item] = data[item]
                        })

                    this.loadScriptData = true
                })
            })
        },
    }
}
</script>
<style>
.menu-preloader {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1033;
    width: 100%;
    height: 100vh;
    background-color: #000000d9;

    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
