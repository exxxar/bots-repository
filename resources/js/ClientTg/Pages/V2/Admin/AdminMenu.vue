<template>
    <div class="container py-3">
        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4  g-2">
            <div class="col">
                <button type="button"
                        @click="goTo('ClientsV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                        <img v-lazy="'/images/shop-v2-2/clients.png'" class="menu-item-img img-fluid" alt="">

                        <p class="my-2">Управление клиентами</p>
                    </div>

                </button>
            </div>

            <div class="col">
                <button type="button"
                        @click="goTo('TablesManagerV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                        <img v-lazy="'/images/shop-v2-2/clients.png'" class="menu-item-img img-fluid" alt="">

                        <p class="my-2">Управление столиками</p>
                    </div>

                </button>
            </div>


            <div class="col">
                <button type="button"
                        @click="goTo('LinkManagerV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                        <img v-lazy="'/images/shop-v2-2/utm.png'" class="menu-item-img img-fluid" alt="">

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
                        <img v-lazy="'/images/shop-v2-2/mail.png'" class="menu-item-img img-fluid" alt="">

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
                        <img v-lazy="'/images/shop-v2-2/products.png'" class="menu-item-img img-fluid" alt="">

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
                        <img v-lazy="'/images/shop-v2-2/orders.png'" class="menu-item-img img-fluid" alt="">

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
                        <img v-lazy="'/images/shop-v2-2/promo.png'" class="menu-item-img img-fluid" alt="">

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
                        <img v-lazy="'/images/shop-v2-2/statistic.png'" class="menu-item-img img-fluid" alt="">

                        <p class="my-2">Статистика</p>
                    </div>

                </button>
            </div>

            <div class="col">
                <button type="button"
                        @click="goTo('BotManagerV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                        <img v-lazy="'/images/shop-v2-2/statistic.png'" class="menu-item-img img-fluid" alt="">

                        <p class="my-2">Управление ботом</p>
                    </div>

                </button>
            </div>

            <div class="col">
                <button type="button"
                        @click="goTo('PageManagerV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                        <img v-lazy="'/images/shop-v2-2/statistic.png'" class="menu-item-img img-fluid" alt="">

                        <p class="my-2">Управление страницами</p>
                    </div>

                </button>
            </div>

            <div class="col">
                <button type="button"
                        @click="goTo('DialogsV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                        <img v-lazy="'/images/shop-v2-2/statistic.png'" class="menu-item-img img-fluid" alt="">

                        <p class="my-2">Управление диалогами</p>
                    </div>

                </button>
            </div>

            <div class="col">
                <button type="button"
                        @click="goTo('SlugsV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                        <img v-lazy="'/images/shop-v2-2/statistic.png'" class="menu-item-img img-fluid" alt="">

                        <p class="my-2">Управление скриптами</p>
                    </div>

                </button>
            </div>

            <!--            <div class="col">
                            <button type="button"
                                    @click="goTo('SlugsV2')"
                                    style="min-height:250px;"
                                    class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                                <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                                    <img v-lazy="'/images/shop-v2-2/statistic.png'" class="menu-item-img img-fluid" alt="">

                                    <p class="my-2">Основные функции</p>
                                </div>

                            </button>
                        </div>-->

            <div class="col">
                <button type="button"
                        @click="goTo('IntegrationsV2')"
                        style="min-height:250px;"
                        class="btn shadow-sm border-0 btn-outline-primary w-100  mb-2 card ">
                    <div class="card-body  d-flex justify-content-center align-items-center flex-column w-100">
                        <img
                            style="max-width:150px;"
                            v-lazy="'/images/shop-v2-2/integrations.png'" class="menu-item-img img-fluid" alt="">

                        <p class="my-2">Интеграции</p>
                    </div>

                </button>
            </div>

        </div>

        <hr class="text-light my-3">
        <p class="alert alert-light">
            Вы можете сделать точную копию данного бота без товаров <span
            class="fst-italic">(с тестовым набором данных)</span> и запустить его в один клик.
        </p>
        <button type="button"
                class="btn btn-danger w-100 p-3"
                data-bs-toggle="modal" data-bs-target="#fast-bot-create-modal"
        >
            Создать копию бота
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="fast-bot-create-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Создание бота</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form v-on:submit.prevent="createBot">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control"
                                   placeholder="Токен"
                                   aria-label="Токен"
                                   v-model="botForm.company_name"
                                   maxlength="255"
                                   aria-describedby="bot-token" required>
                            <label id="bot-token">
                                Название заведения
                            </label>
                        </div>

                        <div class="form-floating mb-2">
                            <input type="text" class="form-control"
                                   placeholder="Токен"
                                   aria-label="Токен"
                                   v-model="botForm.bot_token"
                                   maxlength="255"
                                   aria-describedby="bot-token" required>
                            <label id="bot-token">
                                Токен бота
                            </label>
                        </div>

                        <button class="btn btn-primary w-100 p-3">Создать</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            botForm: {
                bot_token: null,
                company_name: null,
            }
        }
    },
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
    },
    mounted() {
        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })
    },
    methods: {
        createBot() {
            this.$store.dispatch("duplicateSelfBot", this.botForm).then(() => {
                this.tg.close()
            })
        },
        goTo(name) {
            this.$router.push({name: name})
        },
    }
}
</script>
<style>
.menu-item-img {
    max-width: 130px;
    max-height: 130px;
    object-fit: cover;
}
</style>
