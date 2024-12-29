<script setup>
import CdekCalcForm from "@/ClientTg/Components/V2/Shop/Cart/CdekCalcForm.vue";
</script>
<template>
    <form
        id="basket"
        class="container py-3"
        v-on:submit.prevent="startCheckout">

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
               v-bind:class="{'active':modelValue.payment_type === 1}"
               v-if="modelValue.pick_up_type==0&&settings.can_use_cash"
               @click="modelValue.payment_type = 1"

               class="list-group-item list-group-item-action p-3"><i
                class="fa-regular fa-credit-card mr-2"></i>Картой</a>
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

        <h6 class="opacity-75 my-3">Расчёт цены доставки CDEK</h6>
        <CdekCalcForm></CdekCalcForm>

        <h6 class="opacity-75 my-3">Общая информация</h6>

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


        <h6 class="opacity-75 my-3">Сводка</h6>

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
                            <strong v-else>не рассчитана</strong>
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
            @click="requestDeliveryPrice"
            class="btn btn-outline-light text-primary p-3 w-100 mb-2"
            :disabled="!canRequestDeliverPrice">
            <i class="fa-solid fa-map-location-dot mr-2"></i> Рассчитать тариф доставки
        </button>

        <button
            v-if="(modelValue.payment_type!==2||settings.need_pay_after_call)"
            type="submit"
            :disabled="spent_time_counter>0||(!modelValue.use_cashback?settings.min_price>cartTotalPrice:settings.min_price>cartTotalPrice-cashbackLimit) || modelValue.delivery_price==0"
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
