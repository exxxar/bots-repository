<script setup>
import ScheduleList from "@/ClientTg/Components/V2/Shop/ScheduleList.vue";
</script>
<template>
    <div class="container g-2 my-3" v-if="getSelf">
        <h6 class="opacity-75 mb-3 text-center"><i class="fa-solid fa-house-chimney mr-2 text-primary"></i>Доступные
            сервисы</h6>

        <div class="row" v-if="bot">
            <div class="col-12">
                <div
                    v-if="isWork"
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
                        <img v-lazy="'/images/shop-v2/profile.png'" class="img-fluid" alt="">

                        <p class="my-2">Профиль</p>
                    </div>

                </button>
            </div>

            <div class="col">
                <button type="button"
                        @click="goTo('CatalogV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2/shop.png'" class="img-fluid" alt="">

                        <p class="my-2">Магазин</p>
                    </div>

                </button>
            </div>

            <div class="col">

                <button type="button"
                        @click="goTo('ShopCartV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2/cart.png'" class="img-fluid" alt="">

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
                        <img v-lazy="'/images/shop-v2/history.png'" class="img-fluid" alt="">

                        <p class="my-2"> История заказов</p>
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
                        <img v-lazy="'/images/shop-v2/contacts.png'" class="img-fluid" alt="">

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
                        <img v-lazy="'/images/shop-v2/client-admin.webp'" class="img-fluid" alt="">

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
                        <img v-lazy="'/images/shop-v2/mailing.webp'" class="img-fluid" alt="">

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
                        <img v-lazy="'/images/shop-v2/shop-admin.webp'" class="img-fluid" alt="">

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
                        <img v-lazy="'/images/shop-v2/order-admin.webp'" class="img-fluid" alt="">

                        <p class="my-2">Управление заказами</p>
                    </div>

                </button>
            </div>

            <div class="col">
                <button type="button"
                        @click="goTo('StatisticV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2/statistic.webp'" class="img-fluid" alt="">

                        <p class="my-2">Статистика</p>
                    </div>

                </button>
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
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {}
    },
    computed: {
        ...mapGetters(['getSelf', 'cartTotalCount']),

        isWork(){
            if (!window.isCorrectSchedule(this.bot.company.schedule))
                return true

          return (this.bot.company || {is_work:true}).is_work
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
        }
    }
}
</script>
