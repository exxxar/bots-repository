<script setup>
import ScheduleList from "@/ClientTg/Components/V2/Shop/ScheduleList.vue";
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
        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-2">
            <div class="col">
                <button type="button"
                        @click="goTo('ProfileV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2-2/profile.png'" class="img-fluid" alt="">

                        <p class="my-2">Профиль</p>
                    </div>

                </button>
            </div>

            <div class="col">
                <button type="button"
                        @click="goTo('CatalogV2')"
                        style="min-height:250px;"
                        :disabled="script_data.is_disabled"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2-2/shop.png'" class="img-fluid" alt="">

                        <p class="my-2">Магазин</p>
                        <span style="font-size:12px;" v-if="script_data.is_disabled"><i class="fa-solid fa-lock"></i> закрыто</span>
                    </div>

                </button>
            </div>

            <div class="col">

                <button type="button"
                        @click="goTo('ShopCartV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2-2/basket.png'" class="img-fluid" alt="">

                        <p class="my-2">Корзина
                            <span class="badge bg-primary" v-if="cartTotalCount>0   ">{{ cartTotalCount }}</span>
                        </p>
                    </div>

                </button>

            </div>

            <div class="col">

                <button type="button"
                        @click="goTo('OrdersV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2-2/history.png'" class="img-fluid" alt="">

                        <p class="my-2"> История заказов</p>
                    </div>

                </button>

            </div>

            <div class="col" v-if="script_data.wheel_of_fortune">

                <button type="button"
                        :disabled="!script_data.wheel_of_fortune.can_play||!loadScriptData"
                        @click="goTo('WheelOfFortuneV2')"
                        style="min-height:250px;"

                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2-2/events.png'" class="img-fluid" alt="">

                        <p class="my-2"> Колесо фортуны</p>
                        <span style="font-size:12px;" v-if="!script_data.wheel_of_fortune.can_play||!loadScriptData"><i
                            class="fa-solid fa-lock"></i> закрыто</span>
                    </div>

                </button>

            </div>

            <!--            <div class="col" v-if="getSelf.is_admin">
                            <button type="button"
                                    @click="goTo('AdminV2')"
                                    style="min-height:250px;"
                                    class="btn shadow-sm btn-outline-primary w-100  mb-2 card">
                                <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                                    <img v-lazy="'/images/shop-v2/home.png'" class="img-fluid" alt="">

                                    <p class="my-2">Админ.панель</p>
                                </div>

                            </button>

                        </div>-->

            <!--            <div class="col">

                            <button type="button"

                                    @click="goTo('WheelOfFortuneV2')"
                                    style="min-height:250px;"
                                    class="btn shadow-sm btn-outline-primary w-100  mb-2 card">
                                <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                                    <img v-lazy="'/images/shop-v2/gift.png'" class="img-fluid" alt="">

                                    <p class="my-2"> Колесо фортуны</p>
                                </div>

                            </button>


                        </div>-->

            <div class="col">

                <button type="button"
                        @click="goTo('ContactsV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm btn-outline-primary w-100 border-0 mb-2 card">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2-2/contacts.png'" class="img-fluid" alt="">

                        <p class="my-2">О Нас & Контакты</p>
                    </div>

                </button>

            </div>
        </div>


        <h6 class="opacity-75 my-3 text-center" v-if="getSelf.is_admin"><i
            class="fa-solid fa-house-lock mr-2 text-primary"></i>Административные сервисы</h6>

        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-2" v-if="getSelf.is_admin">
            <div class="col">
                <button type="button"
                        @click="goTo('ClientsV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2-2/clients.png'" class="img-fluid" alt="">

                        <p class="my-2">Управление клиентами</p>
                    </div>

                </button>
            </div>

            <div class="col">
                <button type="button"
                        @click="goTo('MailingV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
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
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
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
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
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
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
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
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
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
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2-2/statistic.png'" class="img-fluid" alt="">

                        <p class="my-2">Настройка скрипта</p>
                    </div>

                </button>
            </div>
        </div>
    </div>

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
                wheel_of_fortune: {
                    can_play: false,
                }
            },
        }
    },
    computed: {
        ...mapGetters(['getSelf', 'cartTotalCount']),

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
        loadScriptModuleData() {
            this.loadScriptData = false
            return this.$store.dispatch("loadShopModuleData").then((resp) => {
                this.script_data = []

                this.$nextTick(() => {
                    Object.keys(resp).forEach(item => {
                        this.script_data[item] = resp[item]
                    })

                    this.loadScriptData = true
                    console.log("uploaded data", this.script_data)
                })
            })
        },
    }
}
</script>
