<script setup>
import PromoCodeForm from "@/ClientTg/Components/V2/Shop/PromoCodeForm.vue";
import {cashbackLimit} from "@/ClientTg/utils/commonMethods.js";
</script>

<template>

    <template v-if="deliveryForm!=null">


        <div class="card my-3 ">
            <div class="card-body p-2">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <p class="mb-0 d-flex justify-content-between">Суммарно, ед. <strong
                            class="text-decoration-underline text-primary"
                            @click="goToProductCart">{{ cartTotalCount }}
                            шт.</strong></p>
                    </li>
                    <li class="list-group-item">
                        <p class="mb-0 d-flex justify-content-between">Цена <strong>{{ cartTotalPrice }}
                            <sup>.00</sup>₽</strong>
                        </p>
                    </li>
                    <li class="list-group-item" v-if="deliveryForm?.use_cashback">
                        <p class="mb-0 d-flex justify-content-between">Оплата бонусами
                            <strong v-if="deliveryForm?.use_cashback">{{ cashbackLimit() }} ₽</strong>
                            <strong v-else>-</strong>
                        </p>
                    </li>

                    <li class="list-group-item" v-if="settings.need_promo_code || false">

                        <p
                            data-bs-toggle="modal" data-bs-target="#promocode-modal"
                            class="mb-0 d-flex justify-content-between">
                            Промокод

                            <strong v-if="deliveryForm?.discount>0"
                                    class="fw-bold">{{ deliveryForm.discount }} ₽
                            </strong>
                            <strong
                                v-else
                                class="text-primary">
                                <i class="fa-solid fa-percent px-2"></i>
                                <span class="text-decoration-underline">Ввести промокод</span>
                            </strong>
                        </p>
                    </li>


                    <template v-if="settings.shop_display_type == 0">
                        <li class="list-group-item"
                            v-if="(settings.need_person_counter || false) && (settings.shop_display_type == 0)">
                            <p
                                data-bs-toggle="modal" data-bs-target="#person-modal"
                                class="mb-0 d-flex justify-content-between">Число гостей <strong
                                class="fw-bold text-primary"><i
                                class="fa-solid fa-people-group mr-2"></i>{{ deliveryForm.persons }}
                                чел.</strong>
                            </p>
                        </li>

                        <li class="list-group-item" v-if="!deliveryForm.need_pickup">
                            <p class="mb-2 d-flex justify-content-between">
                                Цена доставки

                                <!-- Автоматический расчет -->
                                <template v-if="settings.need_automatic_delivery_request">
                                    <span
                                        class="d-flex justify-content-end text-decoration-underline"
                                        :data-bs-toggle="deliveryForm.delivery_price > 0 ? 'modal' : null"
                                        :data-bs-target="deliveryForm.delivery_price > 0 ? '#delivery-price-modal' : null"
                                    >
                                        <template v-if="deliveryForm.delivery_price > 0">
                                            {{ deliveryForm.delivery_price }}₽
                                        </template>
                                        <template v-else>
                                            не рассчитана
                                        </template>


                                    </span>
                                </template>



                                <!-- Ручной расчет курьером -->
                                <span
                                    class="d-flex justify-content-end text-decoration-underline"
                                    v-else
                                >
                                    Рассчитывается курьером
                                    <span
                                        class="text-primary underline fw-bold cursor-pointer"
                                        @click="recalcDeliveryPrice"
                                    >
                                        (повторить расчет)
                                    </span>
                                </span>
                            </p>

                            <button
                                type="button"
                                class="w-100 p-3 btn btn-primary"
                                @click="recalcDeliveryPrice"
                                v-if="deliveryForm.delivery_price===0">
                                <i class="fa-solid fa-money-bill-wave"></i> Рассчитать цену доставки
                            </button>
                        </li>


                        <div v-if="deliveryForm.payment_type === 3&&settings.can_use_cash">
                            <p class="my-3 text-center">Мы можем подготовить для вас сдачу с:</p>
                            <div class="row row-cols-2 mb-0">
                                <div class="col" v-for="money in moneyVariants">
                                    <button class="btn btn-outline-primary w-100 mb-2 rounded-5"
                                            type="button"
                                            @click="deliveryForm.money=money"
                                            v-bind:class="{'btn-primary text-white':deliveryForm.money===money}">{{
                                            money
                                        }}₽
                                    </button>
                                </div>

                            </div>
                            <p class="mb-2"><em>или введите другую сумму...</em></p>

                            <div class="form-floating">
                                <input type="number"
                                       min="0"
                                       v-model="deliveryForm.money"
                                       class="form-control" id="data-money" placeholder="С какой суммы нужна сдача">
                                <label for="data-money">С какой суммы нужна сдача</label>
                            </div>

                        </div>
                    </template>

                    <template
                        v-if="settings.shop_display_type  == 1 && deliveryForm.cdek.tariff && settings.need_automatic_delivery_request">
                        <li class="list-group-item">
                            <p
                                class="mb-0 d-flex justify-content-between">Тариф <strong
                                class="fw-bold">{{ deliveryForm.cdek.tariff.tariff_name }} </strong>
                            </p>
                        </li>
                        <li class="list-group-item" v-if="!settings.need_hide_delivery_period">
                            <p
                                class="mb-0 d-flex justify-content-between">Время доставки займет от
                                <strong
                                    class="fw-bold text-primary">{{ deliveryForm.cdek.tariff.calendar_min }} </strong>
                                до
                                <strong
                                    class="fw-bold text-primary">{{ deliveryForm.cdek.tariff.calendar_max }} </strong>
                                дней
                            </p>
                        </li>
                        <li class="list-group-item" v-if="settings.need_automatic_delivery_request">
                            <p
                                class="mb-0 d-flex justify-content-between">Стоимость доставки <strong
                                class="fw-bold">{{ deliveryForm.cdek.tariff.delivery_sum }} ₽</strong>
                            </p>
                        </li>
                    </template>

                    <li class="list-group-item" v-if="deliveryForm?.discount">
                        <p class="mb-0 d-flex justify-content-between">Величина скидки
                            <strong class="fw-bold">{{ deliveryForm?.discount }} ₽</strong>
                        </p>
                    </li>
                    <li class="list-group-item">
                        <p class="mb-0 d-flex justify-content-between">Итого, цена
                            <strong class="fw-bold">{{ finallyPrice }} ₽</strong>
                        </p>
                    </li>
                </ul>


            </div>
        </div>

        <p v-if="settings.delivery_price_text" v-html="settings.delivery_price_text"></p>
        <p v-if="(settings.min_price||0)>cartTotalPrice">Минимальная цена заказа <strong
            class="fw-bold">{{ settings.min_price || 0 }}
            руб</strong></p>

        <!-- Modal -->
        <div class="modal fade" id="promocode-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-body">

                        <PromoCodeForm
                            v-on:callback="activateDiscount"></PromoCodeForm>

                        <h6 v-if="deliveryForm.discount>0"
                            class="text-center py-3 border-primary border rounded-2">Скидка за промокод <strong
                            class="fw-bold">{{ deliveryForm.discount }} ₽</strong>
                        </h6>

                    </div>

                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="person-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content ">

                    <div class="modal-body ">


                        <h6 class="opacity-75 mb-3">Укажите на сколько человек добавить приборы?</h6>


                        <div class="row text-center">

                            <div class="col-4">
                                <button
                                    @click="decPersons"
                                    type="button" class="btn p-2 w-100 btn-primary "><i
                                    class="fa-solid fa-minus font-22"></i></button>
                            </div>

                            <div class="col-4 d-flex justify-content-center align-items-center">
                                <strong
                                    class="fw-bold"
                                    style="font-size:16px;">{{ deliveryForm.persons }}</strong>
                            </div>

                            <div class="col-4">
                                <button type="button"
                                        @click="incPersons"
                                        class="btn p-2 w-100 btn-primary "><i
                                    class="fa-solid fa-plus font-22"></i></button>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>

        <!-- Modal -->

    </template>
</template>
<script>

import {mapGetters} from "vuex";
import {cashbackLimit} from "@/ClientTg/utils/commonMethods.js";

export default {
    props: ["modelValue"],
    data() {
        return {
            deliveryForm: null,
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

        finallyPrice() {
            let price = !this.deliveryForm?.use_cashback ?
                this.cartTotalPrice :
                this.cartTotalPrice - cashbackLimit()

            let deliveryPrice = this.settings.need_automatic_delivery_request ?
                (this.deliveryForm.cdek.tariff?.delivery_sum || 0) + (this.deliveryForm.delivery_price || 0) : 0;

            return price + deliveryPrice
        },


    },
    mounted() {
        this.deliveryForm = this.modelValue
    },
    methods: {
        goToProductCart() {
            document.dispatchEvent(new Event('switch-to-cart'));
        },
        recalcDeliveryPrice() {
            this.$emit("calc-delivery-price")
        },
        activateDiscount(item) {
            this.deliveryForm.discount = item.discount || 0
        },
        decPersons() {
            this.deliveryForm.persons = this.deliveryForm.persons > 1 ? this.deliveryForm.persons - 1 : this.deliveryForm.persons;
        },
        incPersons() {
            this.deliveryForm.persons = this.deliveryForm.persons < 100 ? this.deliveryForm.persons + 1 : this.deliveryForm.persons;
        },
    }
}
</script>
