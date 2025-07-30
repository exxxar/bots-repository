<script setup>
import CdekCalcForm from "@/ClientTg/Components/V2/Shop/Cart/CdekCalcForm.vue";
import Summary from "@/ClientTg/Components/V2/Shop/Cart/Summary.vue";
import PaymentTypes from "@/ClientTg/Components/V2/Shop/Cart/PaymentTypes.vue";
import DeliveryForm from "@/ClientTg/Components/V2/Shop/Cart/DeliveryForm.vue";
import OfferForm from "@/ClientTg/Components/V2/Shop/Cart/OfferForm.vue";

</script>

<template>
    <form
        id="basket"
        v-if="deliveryForm"
        class="container py-3"
        v-on:submit.prevent="startCheckout">

        <h6 class="opacity-75">Способы оплаты</h6>
        <PaymentTypes v-model="deliveryForm"></PaymentTypes>

        <h6
            v-if="settings.need_automatic_delivery_request"
            class="opacity-75 my-3">Расчёт цены доставки CDEK</h6>

        <CdekCalcForm
            :need-delivery-price="settings.need_automatic_delivery_request"
            v-on:calc="calcTariff"></CdekCalcForm>


        <h6 class="opacity-75 my-3">Общая информация</h6>

        <DeliveryForm
            v-model="deliveryForm"
            :mode="1"></DeliveryForm>

        <OfferForm v-model="offer_agreement"></OfferForm>

        <h6 class="opacity-75 my-3">Сводка</h6>

        <Summary v-model="deliveryForm"></Summary>

        <button type="button"
                @click="goToProductCart"
                class="btn btn-primary w-100 p-3">
            <i class="fa-solid fa-cart-shopping"></i> Корзина с товаром
        </button>

        <nav
            v-if="offer_agreement"
            class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
            style="border-radius:10px 10px 0px 0px;">

            <template v-if="spent_time<=0">
                <template v-if="deliveryForm.cdek.tariff!=null||!settings.need_automatic_delivery_request">
                    <button
                        v-if="settings.need_pay_after_call || deliveryForm.payment_type === 3"
                        type="button"
                        @click="startCheckout"
                        :disabled="!canSubmitForm"
                        class="btn btn-primary p-3 w-100 mb-2">
                        <i v-if="spent_time<=0" class="fa-solid fa-file-invoice mr-2"></i>
                        <i v-else class="fa-solid fa-hourglass  mr-2"></i>
                        Оформить
                    </button>

                    <button
                        v-if="deliveryForm.payment_type===4&&!settings.need_pay_after_call"
                        type="button"
                        @click="startCheckout"
                        :disabled="!canSubmitForm"
                        class="btn btn-primary p-3 w-100">
                        <i class="fa-solid fa-receipt mr-2"></i> Оформить и оплатить через СБП
                    </button>

                    <button
                        v-if="deliveryForm.payment_type===2&&!settings.need_pay_after_call"
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
            </template>
            <template v-else>
                <button type="button"
                        class="btn btn-primary p-3 w-100 d-flex align-items-center justify-content-center">
                    Осталось ждать {{ spent_time || 0 }} сек.
                    <div
                        v-if="!canSubmitForm"
                        class="spinner-border ml-2 spinner-border-sm"
                        role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </button>
            </template>
        </nav>
    </form>
</template>
<script>

import {mapGetters} from "vuex";
import {cashbackLimit, startTimer, checkTimer} from "@/ClientTg/utils/commonMethods.js";

export default {
    props: ["modelValue"],
    data() {
        return {
            spent_time: 0,
            deliveryForm: null,
            can_start_payment: false,
            offer_agreement: true,
            need_request_delivery_price: true,
            moneyVariants: [
                500, 1000, 2000, 5000
            ],
        }
    },
    watch: {

        'modelValue': {
            handler: function (newValue) {
                this.deliveryForm = newValue
            },
            deep: true
        },
        'deliveryForm': {
            handler: function (newValue) {

                this.$emit("update:modelValue", this.deliveryForm)
            },
            deep: true
        },
        'cartTotalPrice': {
            handler: function (newValue) {
                if (this.settings.free_shipping_starts_from <= this.cartTotalPrice) {
                    this.deliveryForm.delivery_price = 0
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
        settings() {
            return this.bot.settings
        },
        canRequestDeliverPrice() {
            if (!this.need_request_delivery_price)
                return true

            return this.can_start_payment
        },

        canSubmitForm() {

            let sumIsValid = !this.deliveryForm.use_cashback ?
                this.cartTotalPrice >= (this.settings.min_price || 0) :
                this.cartTotalPrice - cashbackLimit() > (this.settings.min_price || 0)

            return this.can_start_payment && sumIsValid && !(this.spent_time > 0)
        },


    },

    mounted() {
        this.deliveryForm = this.modelValue

        this.is_requested = checkTimer()

        window.addEventListener("trigger-spent-timer", (event) => { // (1)
            this.spent_time = event.detail
        });


    },
    methods: {
        goToProductCart() {
            document.dispatchEvent(new Event('switch-to-cart'));
        },
        calcTariff(item) {
            this.deliveryForm.cdek.tariff = item.tariff || null
            this.deliveryForm.cdek.to.region = item.to?.region || null
            this.deliveryForm.cdek.to.city = item.to?.city || null
            this.deliveryForm.cdek.to.office = item.to?.office || null

            if (this.deliveryForm.cdek.tariff?.errors) {
                this.can_start_payment = false
                return
            }

            if (this.deliveryForm.cdek.tariff)
                this.can_start_payment = true

        },
        startCheckout() {
            this.$emit("start-checkout")
        },

        nextStep() {
            this.$emit("change-tab", 3)
        }
    }
}
</script>
