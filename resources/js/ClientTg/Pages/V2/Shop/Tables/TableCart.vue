<script setup>
import CartProductList from "@/ClientTg/Components/V2/Shop/Cart/CartProductList.vue";
import ProfileCard from "@/ClientTg/Components/V2/Shop/ProfileCard.vue";
import PreloaderV1 from "@/ClientTg/Components/V2/Shop/Other/PreloaderV1.vue";
</script>

<template>
    <div class="container py-3">
        <div class="row">
            <div class="col-12 justify-content-center d-flex">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link"
                           @click="changeTab(0)"
                           v-bind:class="{'active':tab===0}"
                           aria-current="page"
                           href="javascript:void(0)">Столик</a>
                    </li>
                    <li class="nav-item" v-if="cartTotalCount>0">
                        <a class="nav-link"
                           @click="changeTab(1)"
                           v-bind:class="{'active':tab===1}"
                           aria-current="page"
                           href="javascript:void(0)">Корзина</a>
                    </li>
                    <li class="nav-item" v-if="table.id">
                        <a class="nav-link"
                           @click="changeTab(2)"
                           v-bind:class="{'active':tab===2}"
                           href="javascript:void(0)">Заказы столика</a>
                    </li>

                </ul>
            </div>
        </div>

        <template v-if="tab===0">

            <template v-if="table.id">
                <div class="alert alert-danger my-2 text-black" v-if="table.closed_at!=null">
                    Внимание! Данный столик уже <strong class="fw-bold text-primary">закрыт</strong>! Операции со
                    столиком
                    недоступны!
                </div>

                <h6 class="divider my-3">Информация</h6>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <p class="d-flex justify-content-between mb-0">Номер столика <span
                            class="fw-bold text-primary">#{{ table.number || '1' }}</span></p>
                    </li>
                    <li class="list-group-item">
                        <p class="d-flex justify-content-between mb-0">Столик оформлен на
                            <a
                                v-if="table.creator"
                                href="javascript:void(0)"
                                data-bs-toggle="modal" data-bs-target="#creator-profile-modal"
                                class="fw-bold text-primary" style="text-align:right;">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                {{ table.creator.fio_from_telegram || '-' }}
                            </a>
                            <span v-else>
                            не указано
                        </span>
                        </p>
                    </li>
                    <li class="list-group-item">
                        <p class="d-flex justify-content-between mb-0">Вас обслуживает
                            <a
                                v-if="table.officiant"
                                href="javascript:void(0)"
                                data-bs-toggle="modal" data-bs-target="#waiter-profile-modal"
                                style="text-align:right;"
                                class="fw-bold text-primary">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                {{ table.officiant.name || table.officiant.fio_from_telegram || '-' }}
                            </a>
                            <span v-else>
                            не указано
                        </span>
                        </p>
                    </li>
                    <li class="list-group-item">
                        <p class="d-flex justify-content-between mb-0">Кол-во гостей <span
                            class="fw-bold text-primary">{{ table.clients.length }} чел.</span>
                        </p>
                    </li>
                    <li class="list-group-item">
                        <p class="d-flex justify-content-between mb-0">Заказано позиция Вами <span
                            class="fw-bold text-primary">{{ self_summary_count }} ед.</span></p>
                    </li>
                    <li class="list-group-item">
                        <p class="d-flex justify-content-between mb-0">Заказано позиция Всего <span
                            class="fw-bold text-primary">{{ summary_count }} ед.</span></p>
                    </li>

                    <li class="list-group-item">
                        <p class="d-flex justify-content-between mb-0 fw-bold">Итого по Вам <span
                            class="fw-bold text-primary">{{ self_summary_price }} ₽ ({{
                                self_summary_count
                            }} ед.)</span>
                        </p>
                    </li>
                    <li class="list-group-item">
                        <p class="d-flex justify-content-between mb-0">Дополнительные услуги <span
                            class="fw-bold text-primary">{{
                                (table.additional_services || []).length
                            }} ед. на {{ servicePrice }} ₽</span></p>
                    </li>
                    <li class="list-group-item">
                        <p class="d-flex justify-content-between mb-0 fw-bold">Итого по столику <span
                            class="fw-bold text-primary">{{ fullTablePrice }} ₽ ({{ summary_count }} ед.)</span></p>
                    </li>
                </ul>


                <button
                    :disabled="table.officiant_id==null"
                    type="button"
                    @click="callWaiter(false)"
                    class="btn btn-outline-primary p-3 w-100 my-3">
                    <i class="fa-regular fa-bell"></i>
                    <span
                        v-if="spent_time_counter<=0"
                        class="color-primary">Позвать официанта</span>
                    <span
                        v-else
                        class="color-primary">Осталось ждать {{ spent_time_counter }} сек.</span>
                </button>


                <template v-if="settings.need_bonuses_section||false">
                    <p class="divider mb-3">
                    <span class="d-flex flex-column align-items-center">
                        Бонусы <small style="font-size:10px;">(нажми для использования)</small>
                    </span>
                    </p>
                    <div class="card my-3"
                         v-bind:class="{'text-bg-primary':orderForm.use_cashback}"
                         @click="orderForm.use_cashback=!orderForm.use_cashback">
                        <div
                            class="card-body">
                            <p class="d-flex justify-content-between mb-0">
                                <span> Списать баллы</span>
                                <strong>{{ cashbackLimit }}₽</strong>
                            </p>
                        </div>
                    </div>
                </template>


                <form v-on:submit.prevent="startTablePay">
                    <p class="divider mb-3">Оплата заказа</p>
                    <div class="form-floating mb-2">
                        <input type="text"
                               class="form-control"
                               v-model="orderForm.client.name"
                               id="floatingInput" placeholder="name@example.com" required>
                        <label for="floatingInput"><i class="fa-solid fa-signature"></i> Ф.И.О.</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text"
                               v-mask="['+7(###)###-##-##']"
                               v-model="orderForm.client.phone"
                               class="form-control" id="floatingInput" placeholder="name@example.com" required>
                        <label for="floatingInput"><i class="fa-solid fa-mobile-screen-button"></i> Номер
                            телефона</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input"
                               v-model="orderForm.client.have_birthday"
                               type="checkbox" role="switch" id="client-have-birthday">
                        <label class="form-check-label" for="client-have-birthday">У меня День рождения!</label>
                    </div>
                    <div class="alert alert-light mb-2" v-if="orderForm.client.have_birthday">
                        Если у вас День рождения, то при предъявлении <strong
                        class="fw-bold text-primary">паспорта</strong> вы сможете получить подарок от заведения:)
                    </div>
                    <div class="btn-group-vertical w-100">
                        <button
                            :disabled="spent_time_counter>0"
                            type="submit"
                            @click="orderForm.is_self = true"
                            class="btn btn-outline-primary p-3">
                         <span

                             v-if="spent_time_counter<=0"
                             class="color-primary">
                             <i class="fa-solid fa-person-circle-check"></i> Оплатить за себя <span
                             class="fw-bold small">({{ self_summary_price }}₽)</span>
                        </span>
                            <span
                                v-else
                                class="color-primary">Осталось ждать {{ spent_time_counter }} сек.</span>
                        </button>
                        <button
                            :disabled="spent_time_counter>0"
                            type="submit"
                            @click="orderForm.is_self = false"
                            class="btn btn-outline-primary p-3">
                        <span
                            v-if="spent_time_counter<=0"
                            class="color-primary">
                            <i class="fa-solid fa-people-line"></i>  Оплатить за столик <span
                            class="fw-bold small">({{ summary_price }}₽)</span>
                        </span>
                            <span
                                v-else
                                class="color-primary">Осталось ждать {{ spent_time_counter }} сек.</span>

                        </button>
                    </div>
                    <div class="alert alert-light my-2" style="text-align:left;">
                        Для оплаты наличным расчетом <span @click="callWaiter(true)" class="fw-bold text-primary">пригласите официанта</span>
                        (нажав сюда).
                    </div>
                </form>
            </template>
            <p class="alert alert-warning my-3" v-else>
                Данный столик не обслуживается!
            </p>

        </template>

        <template v-if="tab===1">
            <div class="alert alert-danger my-2 text-black" v-if="table.closed_at!=null">
                Внимание! Данный столик уже <strong class="fw-bold text-primary">закрыт</strong>! Операции со столиком
                недоступны!
            </div>
            <CartProductList
                v-if="loaded_settings"
                v-on:select-prize="selectPrize"
                v-on:change-tab="changeTab"
                :simple-mode="true"
                :form-data="orderForm"
                :settings="settings">
                <template #upper-text>
                    <h4>Ваш текущий заказ</h4>
                    <p v-if="cartTotalCount>0">
                        Данный заказ еще не передан в работу. Вам необходимо подтвердить его.
                        Подтвержденный заказ нельзя отменить.
                    </p>
                    <p v-else>
                        В корзине ничего нет:)
                    </p>
                </template>
            </CartProductList>
            <p v-else>
                Вы еще ничего не выбрали из меню
            </p>

            <p
                v-if="sent_to_waiter"
                class="alert alert-light">
                Ваш заказ передан официанту! Как только он подтвердит заказ страница обновится и ваш заказ будет
                отображаться в общем списке заказов столика!
            </p>
            <button
                @click="makeOrder"
                :disabled="cartTotalCount==0||table.officiant_id==null || spent_time_counter>0"
                class="btn btn-primary w-100 p-3 mb-2"><i class="fa-solid fa-clock-rotate-left"></i>
                <span
                    v-if="spent_time_counter<=0"
                    class="color-white">Оформить заказ</span>
                <span
                    v-else
                    class="color-white">Осталось ждать {{ spent_time_counter }} сек.</span>
            </button>

        </template>

        <template v-if="tab===2">
            <div class="alert alert-danger my-2 text-black" v-if="table.closed_at!=null">
                Внимание! Данный столик уже <strong class="fw-bold text-primary">закрыт</strong>! Операции со столиком
                недоступны!
            </div>
            <p class="divider my-3">Заказы клиентов</p>
            <template v-if="basket.length>0">
                <template v-for="item in basket">
                    <p class="my-3">Клиент <span class="fw-bold">{{ item.name || '-' }}</span>
                        <span v-if="self.id===item.id"><i class="fa-solid fa-star text-primary"></i></span>
                    </p>

                    <ul class="list-group">
                        <li v-for="basketItem in item.basket"
                            class="list-group-item d-flex justify-content-between align-items-center"
                        >
                            <span>
                                <i
                                    v-bind:class="{'text-secondary':basketItem.table_approved_at==null,
                                    'text-success':basketItem.table_approved_at!=null}"
                                    class="fa-solid fa-check-double"></i>
                                    {{ basketItem.product.title }}
                            </span>

                            <span class="fw-bold" style="font-size:10px;">{{
                                    basketItem.count
                                }}ед. x {{ basketItem.product.current_price }}₽
                            = {{ basketItem.count * basketItem.product.current_price }}₽</span>
                        </li>

                    </ul>

                    <p class="alert alert-light d-flex justify-content-between my-2">Итого по клиенту <span
                        class="fw-bold">{{ item.summary_price || 0 }}₽</span></p>
                </template>
            </template>

            <button
                type="button"
                :disabled="spent_time_counter>0"
                @click="sendOrderToMyChat"
                class="btn btn-outline-primary p-3 w-100">
                <i class="fa-solid fa-file-arrow-down"></i>

                <span
                    v-if="spent_time_counter<=0"
                    class="color-primary"> Сохранить заказ в чат </span>
                <span
                    v-else
                    class="color-primary">Осталось ждать {{ spent_time_counter }} сек.</span>
            </button>
        </template>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="creator-profile-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ваш профиль</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ProfileCard
                        :can-edit="false"
                        v-if="table.creator"
                        :data="table.creator"/>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="waiter-profile-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Профиль официанта</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ProfileCard
                        :can-edit="false"
                        v-if="table.officiant"
                        :data="table.officiant"/>
                </div>

            </div>
        </div>
    </div>


    <nav
        class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
        style="border-radius:10px 10px 0px 0px;">
        <button
            type="button"
            @click="goToCatalog"
            style="box-shadow: 1px 1px 6px 0px #0000004a;"
            class="btn btn-primary w-100 p-3 rounded-3 shadow-lg text-center"
        >
            <i class="fa-solid fa-cart-shopping"></i> Вернуться к меню
        </button>
    </nav>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            tab: 0,
            spent_time_counter: 0,
            loaded_settings: true,
            sent_to_waiter: false,
            settings: {},
            orderForm: {
                is_self: false,
                action_prize: null,
                use_cashback: false,
                client: {
                    name: null,
                    phone: null,
                    have_birthday: false,
                },
                promo: {
                    discount_in_percent: false,
                    discount: 0,
                    activate_price: 0,
                    code: null,
                },
            },
            table: {
                id: null,
                bot_id: null,
                number: null,
                creator: null,
                creator_id: null,
                officiant: null,
                officiant_id: null,
                closed_at: null,
                additional_services: null,
                config: null,
                clients: [],
            },
            clients: [],
            basket: [],

            self_summary_price: 0,
            self_summary_count: 0,
            summary_price: 0,
            summary_count: 0,
        }
    },
    computed: {
        ...mapGetters(['getSelf', 'cartProducts', 'cartTotalCount', 'cartTotalPrice']),
        self() {
            return window.self
        },
        bot() {
            return window.currentBot
        },
        servicePrice() {
            let serviceSum = 0

            this.table.additional_services?.forEach(item => {
                serviceSum += item.price || 0
            })

            return serviceSum
        },
        fullTablePrice() {
            return this.summary_price + this.servicePrice
        },
        cashbackLimit() {
            let maxUserCashback = this.getSelf.cashBack ? this.getSelf.cashBack.amount : 0
            let summaryPrice = this.cartTotalPrice || 0
            let botCashbackPercent = this.bot.max_cashback_use_percent || 0

            let cashBackAmount = (summaryPrice * (botCashbackPercent / 100));

            return Math.min(cashBackAmount, maxUserCashback)
        },
    },
    mounted() {
        this.loadCurrentTableData()
        this.loadBasketData()
        this.loadShopModuleData()

        if (localStorage.getItem("cashman_self_table_counter") != null) {
            this.startTimer(localStorage.getItem("cashman_self_table_counter"))
        }
    },
    methods: {
        startTablePay() {
            this.startTimer(10)

            this.$notify({
                title: "Корзина",
                text: "Формируем платежную ссылку!",
            })

            let data = new FormData();

            Object.keys(this.orderForm)
                .forEach(key => {
                    const item = this.orderForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            data.append("table_id", this.table.id)

            this.$store.dispatch("startTablePay", data).then(resp => {
                window.open(resp.url, '_blank');
                this.$notify({
                    title: "Корзина",
                    text: "Заказ успешно отправлен к вам чат!",
                    type: "success"
                })
            }).catch(() => {

                this.$notify({
                    title: "Корзина",
                    text: "Ошибка отправки сообщения!",
                    type: "error"
                })
            })
        },
        fillForm() {
            this.orderForm.client.name = this.getSelf.name || this.getSelf.fio_from_telegram || null
            this.orderForm.client.phone = this.getSelf.phone || null

        },
        sendOrderToMyChat() {
            this.startTimer(10)
            this.$store.dispatch("sendOrderToMyChat", {
                dataObject: {
                    table_id: this.table.id,
                }
            }).then(resp => {
                this.$notify({
                    title: "Корзина",
                    text: "Заказ успешно отправлен к вам чат!",
                    type: "success"
                })
            }).catch(() => {

                this.$notify({
                    title: "Корзина",
                    text: "Ошибка отправки сообщения!",
                    type: "error"
                })
            })
        },
        callWaiter(needPayment = false) {
            this.startTimer(10)
            this.$notify({
                title: "Корзина",
                text: "Отправляем запрос к официанту!",
            })
            this.$store.dispatch("requestWaiterComing", {
                dataObject: {
                    table_id: this.table.id,
                    need_payment: needPayment || false
                }
            }).then(resp => {
                this.$notify({
                    title: "Корзина",
                    text: "Официанта оповещен и сейчас подойдет к вам!",
                    type: "success"
                })
            }).catch(() => {

                this.$notify({
                    title: "Корзина",
                    text: "Ошибка отправки оповещения официанту!",
                    type: "error"
                })
            })
        },
        makeOrder() {
            this.sent_to_waiter = true
            this.startTimer(10)
            this.$store.dispatch("requestApproveTable", {
                dataObject: {
                    table_id: this.table.id,
                }
            }).then(resp => {
                this.$notify({
                    title: "Корзина",
                    text: "Ваш заказ передан официанту!",
                    type: "success"
                })
            }).catch(() => {
                this.sent_to_waiter = false
                this.$notify({
                    title: "Корзина",
                    text: "Ошибка отправки заказа официанту!",
                    type: "error"
                })
            })
        },
        selectPrize(item) {
            this.orderForm.action_prize = item
        },
        loadApprovedSelfTableBasket() {
            this.$store.dispatch("loadApprovedSelfTableBasket").then(resp => {
                console.log(resp)
            });
        },
        loadShopModuleData() {
            this.loaded_settings = false
            return this.$store.dispatch("loadShopModuleData").then((resp) => {
                this.$nextTick(() => {

                    if (resp)
                        Object.keys(resp).forEach(item => {
                            this.settings[item] = resp[item]
                        })

                    this.fillForm()
                    this.loaded_settings = true
                })
            })
        },
        loadBasketData() {
            return this.$store.dispatch("loadProductsInBasket")
        },
        changeTab(tab) {
            this.tab = tab
            if (this.tab === 1 || this.tab === 2)
                this.loadCurrentTableData()
        },
        goToCatalog() {
            this.$router.push({name: 'TableMenuV2'})
        },
        startTimer(time) {
            this.spent_time_counter = parseInt(time) != null ? Math.min(parseInt(time), 10) : 10;

            let counterId = setInterval(() => {
                    if (this.spent_time_counter > 0)
                        this.spent_time_counter--
                    else {
                        clearInterval(counterId)
                        this.spent_time_counter = null
                    }
                    localStorage.setItem("cashman_self_table_counter", this.spent_time_counter)
                }, 1000
            )
        },
        loadCurrentTableData() {
            this.$store.dispatch("loadCurrentTableData").then(resp => {

                let data = resp.table
                this.table.id = data.id || null
                this.table.bot_id = data.bot_id || null
                this.table.number = data.number || null
                this.table.creator = data.creator || null
                this.table.creator_id = data.creator_id || null
                this.table.officiant = data.officiant || null
                this.table.officiant_id = data.officiant_id || null
                this.table.closed_at = data.closed_at || null
                this.table.additional_services = data.additional_services || null
                this.table.config = data.config || null
                this.table.clients = data.clients || []

                this.summary_price = resp.summary_price || 0
                this.summary_count = resp.summary_count || 0
                this.clients = resp.clients || []
                this.basket = resp.basket || []

                this.self_summary_price = this.basket.find(item => item.id === this.self.id)?.summary_price || 0
                this.self_summary_count = this.basket.find(item => item.id === this.self.id)?.summary_count || 0

            })
        }
    }
}
</script>
