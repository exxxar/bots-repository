<script setup>
import Summary from "@/ClientTg/Components/V2/Shop/Cart/Summary.vue";
import PaymentTypes from "@/ClientTg/Components/V2/Shop/Cart/PaymentTypes.vue";
import {cashbackLimit} from "@/ClientTg/utils/commonMethods.js";
import OfferForm from "@/ClientTg/Components/V2/Shop/Cart/OfferForm.vue";
import DeliveryForm from "@/ClientTg/Components/V2/Shop/Cart/DeliveryForm.vue";
import DeliveryTypes from "@/ClientTg/Components/V2/Shop/Cart/DeliveryTypes.vue";
</script>
<template>

    <form
        id="basket"
        v-if="deliveryForm"
        class="container py-3"
        v-on:submit.prevent="startCheckout">

        <h6 class="opacity-75">Способы получения заказа</h6>
        <DeliveryTypes v-model="deliveryForm"></DeliveryTypes>


        <h6 class="opacity-75 mb-3">Информация</h6>
        <DeliveryForm
            v-model="deliveryForm"
            :mode="0"></DeliveryForm>

        <OfferForm v-model="offer_agreement"></OfferForm>

        <h6 class="opacity-75">Способы оплаты</h6>
        <PaymentTypes v-model="deliveryForm"></PaymentTypes>


        <Summary
            v-on:calc-delivery-price="requestDeliveryPrice"
            v-model="deliveryForm">
        </Summary>

        <p
            class="alert alert-danger fw-bold mb-2"
            v-if="error_delivery_price_message">{{ error_delivery_price_message }}</p>

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
                <template v-if="settings.need_automatic_delivery_request">
                    <!-- v-if="cartTotalPrice <= settings.free_shipping_starts_from"-->
                    <button
                        v-if="delivery_price_request_step===0"
                        @click="requestDeliveryPrice"
                        class="btn btn-primary text-white p-3 w-100 d-flex align-items-center justify-content-center"
                        :disabled="!canRequestDeliverPrice">
                        <i class="fa-solid fa-map-location-dot mr-2"></i>
                        <span class="px-2">Рассчитать цену доставки</span>
                        <div
                            v-if="!need_request_delivery_price"
                            class="spinner-border ml-2 spinner-border-sm"
                            role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>
                </template>


                <template v-if="delivery_price_request_step===1">

                    <button
                        v-if="settings.need_pay_after_call || deliveryForm.payment_type === 3"
                        :disabled="!canSubmitForm"
                        class="btn btn-primary p-3 w-100">
                        <i v-if="spent_time<=0" class="fa-solid fa-file-invoice mr-2"></i>
                        <i v-else class="fa-solid fa-hourglass  mr-2"></i>
                        Оформить

                    </button>

                    <button
                        v-if="deliveryForm.payment_type===4&&!settings.need_pay_after_call"
                        :disabled="!canSubmitForm"
                        class="btn btn-primary p-3 w-100 d-flex justify-content-center align-items-center">
                        Оплатить через
                        <img
                            style="width:80px; object-fit:cover;margin-left:10px;"
                            v-lazy="'/images/Т-Банк.png'" alt="">
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

    <div class="modal fade" id="delivery-price-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title">Детали расчета цены доставки</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <template v-if="!deliveryForm || loading_delivery" >
                        <div class="d-flex justify-content-center align-items-center" style="height:100px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Загрузка...</span>
                            </div>
                        </div>
                        <p class="text-primary text-center fw-bold">Рассчитываем стоимость доставки</p>
                    </template>


                    <template v-if="deliveryForm&&!loading_delivery">
                        <div class="container">
                            <h6 class="fw-bold mb-2">
                                <span v-if="(deliveryForm.delivery_details||[]).length>1">Цена доставки формируется из</span>
                                <span v-else>Цена доставки</span>
                            </h6>
                            <ul class="list-group mb-2 list-group-flush">
                                <template v-if="(deliveryForm.delivery_details||[]).length>0">
                                    <li class="list-group-item" v-for="item in Object.keys(deliveryForm.delivery_details)">
                                        <div class="d-flex justify-content-between w-100">
                                            <span class="fw-bold">{{ deliveryForm.delivery_details[item].title }}</span>
                                            <span>
                                             <span
                                                 class="badge bg-primary mx-2">{{
                                                     deliveryForm.delivery_details[item].distance
                                                 }} км</span>
                                             <span
                                                 class="badge bg-primary">{{
                                                     deliveryForm.delivery_details[item].price
                                                 }} руб.</span>
                                        </span>
                                        </div>
                                    </li>

                                </template>

                            </ul>
                            <h6 class="fw-bold d-flex justify-content-between">
                                Общее расстояние
                                <span class="badge bg-primary">{{ deliveryForm.distance }} км</span>
                            </h6>
                            <h6 class="fw-bold d-flex justify-content-between">
                                Общая сумма за доставку
                                <span class="badge bg-primary">{{ deliveryForm.delivery_price }} руб.</span>
                            </h6>
                        </div>
                    </template>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary p-3 w-100"
                            data-bs-dismiss="modal">Закрыть
                    </button>

                </div>
            </div>
        </div>
    </div>
</template>
<script>

import {mapGetters} from "vuex";
import {cashbackLimit, startTimer, checkTimer, getSpentTimeCounter} from "@/ClientTg/utils/commonMethods.js";

export default {
    props: ["modelValue"],
    data() {
        return {
            spent_time: 0,
            deliveryForm: null,
            offer_agreement: true,
            delivery_price_request_step: 0,
            loading_delivery:false,
            delivery_message:'',
            need_select_table_by_number: false,
            need_request_delivery_price: true,
            error_delivery_price_message: null,
            moneyVariants: [
                500, 1000, 2000, 5000
            ],
        }
    },
    watch: {

        'deliveryForm': {
            handler: function (newValue) {

                this.$emit("update:modelValue", this.deliveryForm)
            },
            deep: true
        },
        'deliveryForm.need_pickup': {
            handler: function (newValue) {

                this.delivery_price_request_step = (this.deliveryForm.need_pickup === true ? 1 : 0) || (this.settings.need_automatic_delivery_request ? 0 : 1)

            },
            deep: true
        },
        'modelValue': {
            handler: function (newValue) {
                this.deliveryForm = newValue
            },
            deep: true
        },
        'cartTotalPrice': {
            handler: function (newValue) {
                if (this.settings.free_shipping_starts_from <= this.cartTotalPrice) {
                    this.deliveryForm.delivery_price = 0
                    this.delivery_price_request_step = 1
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
            return this.need_request_delivery_price && this.deliveryForm.city != null && this.deliveryForm.street != null && this.deliveryForm.building != null;
        },

        canSubmitForm() {
            let sumIsValid = !this.deliveryForm?.use_cashback ?
                this.cartTotalPrice >= (this.settings.min_price || 0) :
                this.cartTotalPrice - cashbackLimit() > (this.settings.min_price || 0)

            return sumIsValid && !(this.spent_time > 0)
        },


    },

    mounted() {

        this.deliveryForm = this.modelValue

        checkTimer()

        window.addEventListener("trigger-spent-timer", (event) => { // (1)
            this.spent_time = event.detail
        });

        this.delivery_price_request_step = (this.deliveryForm.need_pickup === true ? 1 : 0) || (this.settings.need_automatic_delivery_request ? 0 : 1)
    },
    methods: {
        goToProductCart() {
            document.dispatchEvent(new Event('switch-to-cart'));
        },

        requestDeliveryPrice() {
            this.need_request_delivery_price = false
            this.error_delivery_price_message = null

            this.loading_delivery = true
            this.$notify({
                title: "Корзина",
                text: "Мы начали процесс расчета цены доставки",
            })

            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('delivery-price-modal'))

            if (modal)
                modal.show()

            this.$store.dispatch("requestDeliveryPrice", {
                city: this.deliveryForm.city,
                street: this.deliveryForm.street,
                building: this.deliveryForm.building,
            }).then(resp => {

                this.deliveryForm.delivery_price = resp.price || 0
                this.deliveryForm.distance = resp.distance || 0
                this.deliveryForm.delivery_details = resp.config || []

                this.need_request_delivery_price = true
                this.delivery_price_request_step = 1
                this.$notify({
                    title: "Корзина",
                    text: "Цена доставки успешно просчитана",
                    type: "success"
                })

                this.loading_delivery = false

            }).catch(() => {
                this.deliveryForm.delivery_price = 0
                this.deliveryForm.distance = 0
                this.need_request_delivery_price = true
                this.delivery_price_request_step = 1
                this.error_delivery_price_message = "Цена будет рассчитана курьером в момент доставки!"
                this.$notify({
                    title: "Корзина",
                    text: "Ошибка расчёта цены доставки",
                    type: "error"
                })

                this.loading_delivery = false
            })
        },
        startCheckout() {
            if (this.spent_time > 0)
                return;

            console.log("startCheckout")
            this.$emit("start-checkout")

            startTimer(10);
        },

        nextStep() {

            if (this.spent_time > 0)
                return;

            const form = document.getElementById('basket');
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            const elementsArray = Array.from(requiredFields);

            // Развернуть массив
            const reversedArray = elementsArray.reverse();

            reversedArray.forEach(field => {
                if (!field.value.trim()) {
                    field.focus()
                    isValid = false;
                }
            });

            if (!isValid) {
                this.$notify({
                    title: "Корзина",
                    text: "Пожалуйста, заполните все обязательные поля.",
                    type: "error"
                })
                return;
            }

            this.$emit("change-tab", 3)

            startTimer(10);
        }
    }
}
</script>
