<template>
    <div class="container py-3">
        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-2">
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

            <div class="col">
                <button type="button"
                        @click="goTo('BotManagerV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2-2/statistic.png'" class="img-fluid" alt="">

                        <p class="my-2">Управление ботом</p>
                    </div>

                </button>
            </div>

            <div class="col">
                <button type="button"
                        @click="goTo('PageManagerV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column">
                        <img v-lazy="'/images/shop-v2-2/statistic.png'" class="img-fluid" alt="">

                        <p class="my-2">Управление страницами</p>
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

                        <p class="my-2">Управление диалогами</p>
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

                        <p class="my-2">Управление скриптами</p>
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

                        <p class="my-2">Интеграция AMO</p>
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

                        <p class="my-2">Интеграция YClients</p>
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

                        <p class="my-2">Интеграция FrontPad</p>
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

                        <p class="my-2">Интеграция IIKO</p>
                    </div>

                </button>
            </div>
        </div>
        </div>
</template>
<script>
export default {
    methods:{
        goTo(name) {
            this.$router.push({name: name})
        },
    }
}
</script>
