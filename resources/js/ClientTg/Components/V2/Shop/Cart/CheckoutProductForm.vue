<template>
    <form
        id="basket"
        class="container py-3"
        v-on:submit.prevent="startCheckout">
        <h6 class="opacity-75">Способы получения заказа</h6>

        <div class="list-group my-3">
            <a href="javascript:void(0)"
               v-bind:class="{'active':!modelValue.need_pickup}"
               @click="modelValue.need_pickup = false"
               class="list-group-item list-group-item-action p-3" aria-current="true">
                <i class="fa-solid fa-truck mr-2"></i>Доставка
            </a>
            <a href="javascript:void(0)"
               @click="modelValue.need_pickup = true"
               v-bind:class="{'active':modelValue.need_pickup}"
               class="list-group-item list-group-item-action p-3">
                <i class="fa-brands fa-shopify mr-2"></i>Самовывоз</a>
            <a href="javascript:void(0)"
               v-if="modelValue.need_pickup"
               class="list-group-item list-group-item-action p-3 d-flex justify-content-between">
                <label class="form-check-label" for="in-restaurant">
                    <i class="fa-solid fa-utensils mr-2"></i>
                    В заведении
                </label>

                <input class="form-check-input"
                       name="pickup-format"
                       v-model="modelValue.pick_up_type"
                       type="radio" value="0" id="in-restaurant">
            </a>
            <a href="javascript:void(0)"
               v-if="modelValue.need_pickup"
               class="list-group-item list-group-item-action p-3 d-flex justify-content-between">
                <label class="form-check-label" for="pick-up-in-package">
                    <i class="fa-solid fa-person-walking-luggage mr-2"></i>
                    Забрать с собой
                </label>

                <input class="form-check-input"
                       type="radio" value="1"
                       v-model="modelValue.pick_up_type"
                       name="pickup-format"
                       id="pick-up-in-package">
            </a>
        </div>

        <template v-if="!settings.need_pay_after_call">
            <h6 class="opacity-75">Способы оплаты</h6>

            <div class="list-group my-3">
                <a href="javascript:void(0)"
                   @click="modelValue.payment_type = 0"
                   v-if="settings.can_use_card"
                   v-bind:class="{'active':modelValue.payment_type === 0}"
                   class="list-group-item list-group-item-action p-3" aria-current="true">
                    <i class="fa-solid fa-earth-americas mr-2"></i> Онлайн через бота
                </a>

                <a href="javascript:void(0)"
                   v-bind:class="{'active':modelValue.payment_type === 4}"
                   @click="modelValue.payment_type = 4"
                   v-if="settings.can_use_sbp"
                   class="list-group-item list-group-item-action p-3 d-flex"><i
                    class="fa-solid fa-file-invoice mr-2"></i>
                    <span class="d-inline-flex justify-content-between w-100">
                Оплата по СБП
                <img
                    style="width:45px;"
                    v-lazy="'/images/СБП_логотип.svg'" alt="">
                </span>
                </a>

                <a href="javascript:void(0)"
                   v-bind:class="{'active':modelValue.payment_type === 1}"
                   v-if="modelValue.pick_up_type==0&&settings.can_use_cash"
                   @click="modelValue.payment_type = 1"
                   class="list-group-item list-group-item-action p-3"><i
                    class="fa-regular fa-credit-card mr-2"></i>Картой в заведении</a>
                <a href="javascript:void(0)"
                   v-bind:class="{'active':modelValue.payment_type === 2}"
                   @click="modelValue.payment_type = 2"
                   class="list-group-item list-group-item-action p-3"><i
                    class="fa-solid fa-file-invoice mr-2"></i>Переводом</a>
                <a href="javascript:void(0)"
                   v-bind:class="{'active':modelValue.payment_type === 3}"
                   @click="modelValue.payment_type = 3"
                   v-if="settings.can_use_cash"
                   class="list-group-item list-group-item-action p-3"><i
                    class="fa-regular fa-money-bill-1 mr-2"></i> Наличными</a>
            </div>
        </template>

        <template v-if="settings.need_bonuses_section||false">
            <h6 class="opacity-75">Бонусы <small>(нажми для использования)</small></h6>

            <div class="card my-3"
                 v-bind:class="{'text-bg-primary':modelValue.use_cashback}"
                 @click="modelValue.use_cashback=!modelValue.use_cashback">
                <div
                    class="card-body">
                    <p class="d-flex justify-content-between mb-0">
                        <span> Списать баллы</span>
                        <strong>{{ cashbackLimit }}₽</strong>
                    </p>
                </div>
            </div>
        </template>


        <h6 class="opacity-75 mb-3">Информация</h6>

        <div class="form-floating mb-3">
            <input type="text"
                   v-model="modelValue.name"
                   class="form-control" id="modelValue-name"
                   placeholder="Иванов Иван Иванович" required>
            <label for="modelValue-name">Ф.И.О. <span class="fw-bold text-danger">*</span></label>
        </div>

        <div class="form-floating mb-3">
            <input type="text"
                   v-mask="'+7(###)###-##-##'"
                   v-model="modelValue.phone"
                   class="form-control" id="modelValue-phone"
                   placeholder="+7(000)000-00-00" required>
            <label for="modelValue-phone">Номер телефона <span class="fw-bold text-danger">*</span></label>
        </div>


        <template v-if="!modelValue.need_pickup">
            <div

                class="form-floating mb-3">
                <input type="text"
                       v-model="modelValue.city"
                       class="form-control" id="modelValue-city"
                       placeholder="Ваш город" required>
                <label for="modelValue-city">Ваш город <span class="fw-bold text-danger">*</span></label>
            </div>

            <div
                class="form-floating mb-3">
                <input type="text"
                       v-model="modelValue.street"
                       class="form-control" id="modelValue-street"
                       placeholder="Улица" required>
                <label for="modelValue-street">Улица <span class="fw-bold text-danger">*</span></label>
            </div>


            <div
                class="form-floating mb-3">
                <input type="text"
                       v-model="modelValue.building"
                       class="form-control" id="modelValue-building"
                       placeholder="Номер дома" required>
                <label for="modelValue-building">Номер дома <span class="fw-bold text-danger">*</span></label>
            </div>

            <div
                class="form-floating mb-3">
                <input type="text"
                       v-model="modelValue.flat_number"
                       class="form-control" id="modelValue-flat-number"
                       placeholder="Номер квартиры">
                <label for="modelValue-flat-number">Номер квартиры </label>
            </div>

            <div
                class="form-floating mb-3">
                <input type="text"
                       v-model="modelValue.entrance_number"
                       class="form-control" id="modelValue-entrance-number"
                       placeholder="Номер подъезда">
                <label for="modelValue-entrance-number">Номер подъезда</label>
            </div>

            <div
                class="form-floating mb-3">
                <input type="text"
                       v-model="modelValue.floor_number"
                       class="form-control" id="modelValue-floor-number"
                       placeholder="Номер этажа">
                <label for="modelValue-floor-number">Номер этажа</label>
            </div>

        </template>
        <template v-else>
            <template v-if="settings.need_table_list">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input"
                           type="checkbox"
                           v-model="need_select_table_by_number"
                           role="switch"
                           id="select-table-number-from-list">
                    <label class="form-check-label" for="select-table-number-from-list">Выбрать номер столика из списка</label>
                </div>
                <div class="row row-cols-5" v-if="need_select_table_by_number">
                    <div class="col" v-for="num in parseInt(settings.max_tables)">
                        <a href="javascript:void(0)"
                            @click="modelValue.table_number=num"
                           v-bind:class="{'btn-primary':modelValue.table_number==num,'btn-outline-primary':modelValue.table_number!=num}"
                           class="btn w-100 mb-2">
                            {{num}}
                        </a>
                    </div>
                </div>
            </template>
            <div
                class="form-floating mb-2">
                <input type="number"
                       min="1"
                       max="200"
                       v-model="modelValue.table_number"
                       class="form-control" id="modelValue-table-number"
                       placeholder="Номер столика">
                <label for="modelValue-table-number">Номер столика</label>
            </div>
        </template>


        <div class="form-floating">
            <textarea class="form-control"
                      v-model="modelValue.info"
                      style="height:200px;line-height:150%;"
                      placeholder="Информация" id="modelValue-info"></textarea>
            <label v-if="!modelValue.need_pickup" for="modelValue-info">Информация для доставщика</label>
            <label v-else for="modelValue-info">Информация для сотрудника</label>
        </div>

        <template v-if="!modelValue.need_pickup">
            <h6 class="opacity-75 mt-3">К какому времени приготовить?</h6>

            <div class="list-group my-3">
                <a href="javascript:void(0)"
                   v-bind:class="{'active':modelValue.when_ready}"
                   @click="modelValue.when_ready = true"
                   class="list-group-item list-group-item-action p-3" aria-current="true">
                    <i class="fa-solid fa-stopwatch-20 mr-2"></i>По готовности
                </a>
                <a href="javascript:void(0)"
                   @click="modelValue.when_ready = false"
                   v-bind:class="{'active':!modelValue.when_ready}"
                   class="list-group-item list-group-item-action p-3">
                    <i class="fa-regular fa-clock mr-2"></i>К указанному времени</a>

            </div>

            <div
                v-if="!modelValue.when_ready"
                class="form-floating">
                <input type="datetime-local"
                       v-model="modelValue.time"
                       class="form-control" id="modelValue-time" placeholder="Время доставки" required>
                <label for="modelValue-time">Время доставки</label>
            </div>
        </template>

        <template v-if="settings.need_health_restrictions||false">
            <h6 class="opacity-75 mt-3" v-if="!modelValue.need_pickup">Ограничения по здоровью</h6>

            <div class="list-group my-3" v-if="!modelValue.need_pickup">

                <a href="javascript:void(0)"
                   @click="modelValue.has_disability = false"
                   v-bind:class="{'active':!modelValue.has_disability}"
                   class="list-group-item list-group-item-action p-3">
                    <i class="fa-regular fa-heart mr-2"></i>Нет ограничений по здоровью</a>
                <a href="javascript:void(0)"
                   v-bind:class="{'active':modelValue.has_disability}"
                   @click="modelValue.has_disability = true"
                   class="list-group-item list-group-item-action p-3" aria-current="true">
                    <i class="fa-solid fa-house-medical-flag mr-2"></i>Есть ограничения по здоровью
                </a>
            </div>

            <div class="list-group my-3" v-if="modelValue.has_disability&&!modelValue.need_pickup">
                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-1"> <i class="fa-solid fa-head-side-mask mr-2"></i> Болею</label>
                    <input type="checkbox"
                           value="болею"
                           class="form-check-input"
                           v-model="modelValue.disabilities" id="switch-1">
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-5"> <i class="fa-solid fa-ear-deaf mr-2"></i> Плохо слышит \ говорит</label>
                    <input type="checkbox"
                           value="плохо слышит или говорит"
                           class="form-check-input"
                           v-model="modelValue.disabilities" id="switch-5">
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-3"> <i class="fa-solid fa-glasses mr-2"></i> Слабовидящий</label>
                    <input type="checkbox"
                           value="слабовидящий"
                           class="form-check-input"
                           v-model="modelValue.disabilities" id="switch-3">
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-4"> <i class="fa-solid fa-wheelchair mr-2"></i> Ограничения мобильности</label>
                    <input type="checkbox"
                           class="form-check-input"
                           value="ограничения мобильности"
                           v-model="modelValue.disabilities" id="switch-4">
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-5"> <i class="fa-solid fa-person-dots-from-line mr-2"></i> Пищевая
                        аллергия</label>
                    <input type="checkbox"
                           class="form-check-input"
                           value="пищевая аллергия"
                           v-model="modelValue.disabilities" id="switch-5">
                </a>

            </div>

            <div class="form-floating mb-2" v-if="modelValue.disabilities.indexOf('пищевая аллергия')!==-1">
                <input type="text" class="form-control"
                       v-model="modelValue.allergy"
                       id="floatingInput" placeholder="name@example.com" required>
                <label for="floatingInput">Укажите на что аллергия
                    <span class="fw-bold text-danger">*</span>
                </label>
            </div>
        </template>

        <h6 class="opacity-75 mt-3">Сводка</h6>

        <div class="card my-3 ">
            <div class="card-body p-2">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <p class="mb-0 d-flex justify-content-between">Суммарно, ед. <strong>{{ cartTotalCount }}
                            шт.</strong></p>
                    </li>
                    <li class="list-group-item">
                        <p class="mb-0 d-flex justify-content-between">Цена <strong>{{ cartTotalPrice }}
                            <sup>.00</sup>₽</strong>
                        </p>
                    </li>
                    <li class="list-group-item">
                        <p class="mb-0 d-flex justify-content-between">Оплата бонусами
                            <strong v-if="modelValue.use_cashback">{{ cashbackLimit }} ₽</strong>
                            <strong v-else>-</strong>
                        </p>
                    </li>

                    <li class="list-group-item" v-if="modelValue.promo.discount>0">
                        <p class="mb-2 text-justify"><strong class="fw-bold">Внимание!</strong> Не распространяется
                            на цену доставки!</p>
                        <p class="mb-0 d-flex justify-content-between">Промокод
                            <strong>{{ modelValue.promo.discount }} ₽</strong>
                        </p>
                    </li>

                    <li class="list-group-item" v-if="!modelValue.need_pickup">
                        <p class="mb-0 d-flex justify-content-between">Цена доставки
                            <strong v-if="modelValue.delivery_price>0">{{ modelValue.delivery_price }}
                                <sup>.00</sup>₽</strong>
                            <strong v-else>от курьера</strong>
                        </p>
                    </li>


                    <li class="list-group-item">
                        <p class="mb-0 d-flex justify-content-between">Итого, цена
                            <strong>{{ finallyPrice }} ₽</strong>
                        </p>
                    </li>
                </ul>

                <div v-if="modelValue.payment_type === 3&&settings.can_use_cash">
                    <p class="my-3 text-center">Мы можем подготовить для вас сдачу с:</p>
                    <div class="row row-cols-2 mb-0">
                        <div class="col" v-for="money in moneyVariants">
                            <button class="btn btn-outline-primary w-100 mb-2 rounded-5"
                                    type="button"
                                    @click="modelValue.money=money"
                                    v-bind:class="{'btn-primary text-white':modelValue.money===money}">{{
                                    money
                                }}₽
                            </button>
                        </div>

                    </div>
                    <p class="mb-2"><em>или введите другую сумму...</em></p>

                    <div class="form-floating">
                        <input type="number"
                               min="0"
                               v-model="modelValue.money"
                               class="form-control" id="modelValue-money" placeholder="С какой суммы нужна сдача">
                        <label for="modelValue-money">С какой суммы нужна сдача</label>
                    </div>

                </div>
            </div>
        </div>

        <p v-if="settings.delivery_price_text" v-html="settings.delivery_price_text"></p>
        <p v-if="settings.min_price">Минимальная цена заказа {{ settings.min_price || 0 }} руб</p>

        <button
            v-if="cartTotalPrice <= settings.free_shipping_starts_from"
            @click="requestDeliveryPrice"
            class="btn btn-outline-light text-primary p-3 w-100 mb-2"
            :disabled="!canRequestDeliverPrice">
            <i class="fa-solid fa-map-location-dot mr-2"></i> Узнать цену доставки
        </button>

        <button
            v-if="settings.need_pay_after_call"
            type="submit"
            :disabled="spent_time_counter>0||(!modelValue.use_cashback?settings.min_price>cartTotalPrice:settings.min_price>cartTotalPrice-cashbackLimit)"
            class="btn btn-primary p-3 w-100 mb-2">

            <i v-if="spent_time_counter<=0" class="fa-solid fa-file-invoice mr-2"></i>
            <i v-else class="fa-solid fa-hourglass  mr-2"></i>

            <span
                v-if="spent_time_counter<=0"
                class="color-white">Оформить</span>
            <span
                v-else
                class="color-white">Осталось ждать {{ spent_time_counter }} сек.</span>
        </button>

        <button
            v-if="modelValue.payment_type===4&&!settings.need_pay_after_call"
            type="submit"
            :disabled="!canSubmitForm"
            class="btn btn-primary p-3 w-100">
            <i class="fa-solid fa-receipt mr-2"></i> Оформить и оплатить через СБП
        </button>

        <button
            v-if="modelValue.payment_type===2&&!settings.need_pay_after_call"
            type="button"
            @click="nextStep"
            :disabled="!canSubmitForm"
            class="btn btn-primary p-3 w-100">
            <i class="fa-solid fa-receipt mr-2"></i> Оплатить переводом
        </button>
    </form>
</template>
<script>

import {mapGetters} from "vuex";

export default {
    props: ["settings", "modelValue"],
    data() {
        return {
            spent_time_counter: 0,
            need_select_table_by_number: false,
            need_request_delivery_price: true,
            moneyVariants: [
                500, 1000, 2000, 5000
            ],
        }
    },
    watch: {

        'modelValue': {
            handler: function (newValue) {
                this.$emit("update:modelValue", this.modelValue)
            },
            deep: true
        },

        'cartTotalPrice': {
            handler: function (newValue) {
                if (this.settings.free_shipping_starts_from <= this.cartTotalPrice) {
                    this.modelValue.delivery_price = 0
                }
            },
            deep: true
        },


    },
    computed: {
        ...mapGetters(['cartProducts', 'cartProducts', 'cartTotalCount', 'cartTotalPrice', 'getSelf']),

        bot() {
            return window.currentBot
        },

        canRequestDeliverPrice() {
            return this.need_request_delivery_price && this.modelValue.city != null && this.modelValue.street != null && this.modelValue.building != null;
        },

        finallyPrice() {
            return !this.modelValue.use_cashback ?
                Math.max(1, (this.cartTotalPrice -
                    (this.cartTotalPrice >= this.modelValue.promo.activate_price ?
                        this.modelValue.promo.discount : 0))) + this.modelValue.delivery_price :
                Math.max(1, (this.cartTotalPrice - this.cashbackLimit -
                    (this.cartTotalPrice >= this.modelValue.promo.activate_price ?
                        this.modelValue.promo.discount : 0))) + this.modelValue.delivery_price
        },
        canSubmitForm() {
            return (this.spent_time_counter || 0) === 0
                && (!this.modelValue.use_cashback ?
                    this.cartTotalPrice >= this.settings.min_price :
                    this.cartTotalPrice - this.cashbackLimit > this.settings.min_price)

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

        if (localStorage.getItem("cashman_self_product_delivery_counter") != null) {
            this.is_requested = true;
            this.startTimer(localStorage.getItem("cashman_self_product_delivery_counter"))
        }

        this.modelValue.name = localStorage.getItem("cashman_self_product_delivery_form_name") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_name") : null

        this.modelValue.phone = localStorage.getItem("cashman_self_product_delivery_form_phone") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_phone") : null

        this.modelValue.address = localStorage.getItem("cashman_self_product_delivery_form_address") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_address") : null

        this.modelValue.city = localStorage.getItem("cashman_self_product_delivery_form_city") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_city") : null

        this.modelValue.street = localStorage.getItem("cashman_self_product_delivery_form_street") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_street") : null

        this.modelValue.building = localStorage.getItem("cashman_self_product_delivery_form_building") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_building") : null

        this.modelValue.flat_number = localStorage.getItem("cashman_self_product_delivery_form_flat_number") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_flat_number") : null

        this.modelValue.entrance_number = localStorage.getItem("cashman_self_product_delivery_form_entrance_number") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_entrance_number") : null

        this.modelValue.disabilities = localStorage.getItem("cashman_self_product_delivery_form_entrance_disabilities") != null ?
            JSON.parse(localStorage.getItem("cashman_self_product_delivery_form_entrance_disabilities")) : []

        if (this.modelValue.disabilities.length > 0)
            this.modelValue.has_disability = true

    },
    methods: {
        requestDeliveryPrice() {
            this.need_request_delivery_price = false

            this.$notify({
                title: "Корзина",
                text: "Мы начали процесс расчета цены доставки",
            })

            this.$store.dispatch("requestDeliveryPrice", {
                city: this.modelValue.city,
                street: this.modelValue.street,
                building: this.modelValue.building,
            }).then(resp => {

                this.modelValue.delivery_price = resp.price || 0
                this.modelValue.distance = resp.distance || 0

                this.need_request_delivery_price = true

                this.$notify({
                    title: "Корзина",
                    text: "Цена доставки успешно просчитана",
                    type: "success"
                })
            }).catch(() => {
                this.modelValue.delivery_price = 0
                this.modelValue.distance = 0
                this.need_request_delivery_price = true

                this.$notify({
                    title: "Корзина",
                    text: "Ошибка расчёта цены доставки",
                    type: "error"
                })
            })
        },
        startCheckout() {
            this.$emit("submit")
        },
        startTimer(time) {
            this.spent_time_counter = parseInt(time) != null ? Math.min(parseInt(time), 10) : 10;

            let counterId = setInterval(() => {
                    if (this.spent_time_counter > 0)
                        this.spent_time_counter--
                    else {
                        clearInterval(counterId)
                        this.is_requested = false
                        this.spent_time_counter = null
                    }
                    localStorage.setItem("cashman_self_product_delivery_counter", this.spent_time_counter)
                }, 1000
            )
        },
        nextStep() {
            this.$emit("change-tab", 2)
        }
    }
}
</script>
