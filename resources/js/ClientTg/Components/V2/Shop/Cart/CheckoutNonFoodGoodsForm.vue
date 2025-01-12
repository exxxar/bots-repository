<script setup>
import CdekCalcForm from "@/ClientTg/Components/V2/Shop/Cart/CdekCalcForm.vue";
import Summary from "@/ClientTg/Components/V2/Shop/Cart/Summary.vue";
</script>
<template>
    <form
        id="basket"
        class="container py-3"
        v-on:submit.prevent="startCheckout">

        <h6 class="opacity-75">Способы оплаты</h6>


        <div class="list-group my-3">

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
               @click="modelValue.payment_type = 0"
               v-if="settings.can_use_card&&settings.payment_token!=null"
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
        <CdekCalcForm v-on:calc="calcTariff"></CdekCalcForm>

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

        <Summary :data="modelValue"
                 :settings="settings">
        </Summary>

        <template v-if="modelValue.cdek.tariff!=null">
            <button
                v-if="settings.need_pay_after_call || modelValue.payment_type === 3"
                type="button"
                @click="startCheckout"
                :disabled="!canSubmitForm"

                class="btn btn-primary p-3 w-100 mb-2">

                <i v-if="spent_time_counter<=0" class="fa-solid fa-file-invoice mr-2"></i>
                <i v-else class="fa-solid fa-hourglass  mr-2"></i>

                Оформить
            </button>

            <button
                v-if="modelValue.payment_type===4&&!settings.need_pay_after_call"
                type="button"
                @click="startCheckout"
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
        </template>
        <div v-else class="alert alert-info">
            Для оформления выберите офис доставки СДЭК
        </div>
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

    },
    methods: {

        calcTariff(item){
            this.modelValue.cdek.tariff = item.tariff || null
            this.modelValue.cdek.to.region = item.to?.region || null
            this.modelValue.cdek.to.city = item.to?.city || null
            this.modelValue.cdek.to.office = item.to?.office || null

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
