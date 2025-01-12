<script setup>
import ScheduleList from "@/ClientTg/Components/V2/Shop/ScheduleList.vue";
import MainMenuItem from "@/ClientTg/Components/V2/Shop/MainMenuItem.vue";
import ShopScriptEditor from "@/ClientTg/Components/V2/Admin/ScriptEditors/Shop/ShopScriptEditor.vue";
</script>
<template>

    <div class="container py-3" v-if="getSelf">

        <div
            v-if="script_data.is_disabled||false"
            class="alert alert-danger mb-3">
            <p class="mb-0" v-html="script_data.disabled_text||'-'"></p>
        </div>

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
                    :route="'ProfileV2'"
                    :default-image="'profile.png'"
                    :default-text="'Профиль'"
                    :item="preparedMenuItem['profile']||null"/>
            </div>

            <div class="col">
                <MainMenuItem
                    :disabled="script_data.is_disabled"
                    :route="'CatalogV2'"
                    :default-image="'shop.png'"
                    :default-text="'Магазин'"
                    :item="preparedMenuItem['shop']||null"/>
            </div>

            <div class="col">
                <MainMenuItem
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
                    :route="'OrdersV2'"
                    :default-image="'history.png'"
                    :default-text="'История заказов'"
                    :item="preparedMenuItem['history']||null"/>
            </div>

            <div class="col" v-if="script_data.wheel_of_fortune">
                <MainMenuItem
                    :disabled="!script_data.wheel_of_fortune.can_play"
                    :route="'WheelOfFortuneV2'"
                    :default-image="'events.png'"
                    :default-text="'Розыгрыши'"
                    :item="preparedMenuItem['events']||null"/>

            </div>


            <div class="col">
                <MainMenuItem
                    :route="'ContactsV2'"
                    :default-image="'contacts.png'"
                    :default-text="'О Нас & Контакты'"
                    :item="preparedMenuItem['about']||null"/>
            </div>
        </div>

        <div class="divider my-3"> Дополнительно</div>
        <button
            style="box-shadow: 1px 1px 6px 0px #0000004a;"
            @click="switchToPage('Колесо фортуны')"
            class="btn btn-outline-light text-primary w-100 p-3 rounded-3 shadow-sm mb-2">

            <i class="fa fa-dot-circle"></i> Колесо фортуны
        </button>

        <button
            style="box-shadow: 1px 1px 6px 0px #0000004a;"
            @click="switchToPage('Пригласить друзей')"
            class="btn  btn-outline-light text-primary mb-2 w-100 p-3 rounded-3 shadow-sm ">

            <i class="fa fa-people-carry "></i> Пригласить друзей
        </button>

        <h6 class="opacity-75 my-3 text-center" v-if="getSelf.is_admin"><i
            class="fa-solid fa-house-lock mr-2 text-primary"></i>Административные сервисы</h6>

        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4  g-2" v-if="getSelf.is_admin">
            <div class="col">
                <button type="button"
                        @click="goTo('ClientsV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                        <img v-lazy="'/images/shop-v2-2/clients.png'" class="img-fluid" alt="">

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
                        <img v-lazy="'/images/shop-v2-2/utm.png'" class="img-fluid" alt="">

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
                        <img v-lazy="'/images/shop-v2-2/mail.png'" class="img-fluid" alt="">

                        <p class="my-2">Управление рассылками</p>
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

            <div class="col">
                <button type="button"
                        @click="goTo('AdminOrdersV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                        <img v-lazy="'/images/shop-v2-2/orders.png'" class="img-fluid" alt="">

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
                        <img v-lazy="'/images/shop-v2-2/promo.png'" class="img-fluid" alt="">

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
                        <img v-lazy="'/images/shop-v2-2/statistic.png'" class="img-fluid" alt="">

                        <p class="my-2">Статистика</p>
                    </div>

                </button>
            </div>

            <div class="col" v-if="script_data">
                <button type="button"
                        data-bs-toggle="modal" data-bs-target="#script-setting-editor"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                        <img v-lazy="'/images/shop-v2-2/shop-config.png'" class="img-fluid" alt="">

                        <p class="my-2">Настройка магазина</p>
                    </div>

                </button>
            </div>
        </div>
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
            style="box-shadow: 1px 1px 6px 0px #0000004a;"
            @click="startMenu"
            class="btn btn-primary w-100 p-3 rounded-3 shadow-lg">

            <i class="fa-brands fa-telegram"></i> Главное меню
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

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            loadScriptData: false,
            script_data: {
                is_disabled: true,
                wheel_of_fortune: {
                    can_play: false,
                }
            },
        }
    },
    computed: {
        ...mapGetters(['getSelf', 'cartTotalCount']),
        preparedMenuItem() {
            if (!this.bot.config)
                return []

            let data = this.bot.config["icons"] || []

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

        this.loadScriptModuleData()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            if (this.$route.name === "MenuV2")
                this.tg.close()
            else
                this.$router.back()
        })
    },
    methods: {
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

        loadScriptModuleData() {
            this.loadScriptData = false
            return this.$store.dispatch("loadShopModuleData").then((resp) => {
                let data = resp.data
                this.script_data = []

                this.$nextTick(() => {
                    Object.keys(data).forEach(item => {
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
