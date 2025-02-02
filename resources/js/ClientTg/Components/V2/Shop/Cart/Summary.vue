<script setup>
import PromoCodeForm from "@/ClientTg/Components/V2/Shop/PromoCodeForm.vue";
</script>

<template>
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
                <li class="list-group-item" v-if="data.use_cashback">
                    <p class="mb-0 d-flex justify-content-between">Оплата бонусами
                        <strong v-if="data.use_cashback">{{ cashbackLimit }} ₽</strong>
                        <strong v-else>-</strong>
                    </p>
                </li>

                <li class="list-group-item" v-if="settings.need_promo_code || false">

                    <p
                        data-bs-toggle="modal" data-bs-target="#promocode-modal"
                        class="mb-0 d-flex justify-content-between">Промокод
                        <strong v-if="data.promo.discount>0" class="fw-bold">{{ data.promo.discount }}
                            <span v-if="data.promo.discount_in_percent">%</span>
                            <span v-else>₽</span>
                        </strong>
                        <a
                            v-else
                            class="text-primary border-dashed"
                            href="javascript:void(0)"><i class="fa-solid fa-terminal mr-2"></i>Ввести промокод</a>
                    </p>
                </li>


                <template v-if="settings.shop_display_type == 0">
                    <li class="list-group-item"
                        v-if="(settings.need_person_counter || false) && (settings.shop_display_type == 0)">
                        <p
                            data-bs-toggle="modal" data-bs-target="#person-modal"
                            class="mb-0 d-flex justify-content-between">Число гостей <strong
                            class="fw-bold text-primary"><i class="fa-solid fa-people-group mr-2"></i>{{ data.persons }}
                            чел.</strong>
                        </p>
                    </li>

                    <li class="list-group-item" v-if="!data.need_pickup">
                        <p class="mb-0 d-flex justify-content-between">Цена доставки
                            <template v-if="settings.need_automatic_delivery_request">
                                <span v-if="data.delivery_price>0">{{ data.delivery_price }}
                                    <sup>.00</sup>₽ <span class="text-primary underline fw-bold cursor-pointer" @click="recalcDeliveryPrice">(пересчитать)</span></span>
                                <span v-else>не рассчитана</span>
                            </template>
                            <span v-else>Рассчитывается курьером</span>
                        </p>
                    </li>

                    <div v-if="data.payment_type === 3&&settings.can_use_cash">
                        <p class="my-3 text-center">Мы можем подготовить для вас сдачу с:</p>
                        <div class="row row-cols-2 mb-0">
                            <div class="col" v-for="money in moneyVariants">
                                <button class="btn btn-outline-primary w-100 mb-2 rounded-5"
                                        type="button"
                                        @click="data.money=money"
                                        v-bind:class="{'btn-primary text-white':data.money===money}">{{
                                        money
                                    }}₽
                                </button>
                            </div>

                        </div>
                        <p class="mb-2"><em>или введите другую сумму...</em></p>

                        <div class="form-floating">
                            <input type="number"
                                   min="0"
                                   v-model="data.money"
                                   class="form-control" id="data-money" placeholder="С какой суммы нужна сдача">
                            <label for="data-money">С какой суммы нужна сдача</label>
                        </div>

                    </div>
                </template>

                <template v-if="settings.shop_display_type  == 1 && data.cdek.tariff">
                    <li class="list-group-item">
                        <p
                            class="mb-0 d-flex justify-content-between">Тариф <strong
                            class="fw-bold">{{ data.cdek.tariff.tariff_name }} </strong>
                        </p>
                    </li>
                    <li class="list-group-item">
                        <p
                            class="mb-0 d-flex justify-content-between">Время доставки займет от
                            <strong
                                class="fw-bold text-primary">{{ data.cdek.tariff.calendar_min }} </strong>
                            до
                            <strong
                                class="fw-bold text-primary">{{ data.cdek.tariff.calendar_max }} </strong>
                            дней
                        </p>
                    </li>
                    <li class="list-group-item">
                        <p
                            class="mb-0 d-flex justify-content-between">Стоимость доставки <strong
                            class="fw-bold">{{ data.cdek.tariff.delivery_sum }} ₽</strong>
                        </p>
                    </li>
                </template>


                <li class="list-group-item">
                    <p class="mb-0 d-flex justify-content-between">Итого, цена
                        <strong class="fw-bold">{{ finallyPrice }} ₽</strong>
                    </p>
                </li>
            </ul>


        </div>
    </div>

    <p v-if="settings.delivery_price_text" v-html="settings.delivery_price_text"></p>
    <p v-if="settings.min_price">Минимальная цена заказа {{ settings.min_price || 0 }} руб</p>

    <!-- Modal -->
    <div class="modal fade" id="promocode-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Активация промокода</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <PromoCodeForm
                        v-on:callback="activateDiscount"></PromoCodeForm>

                    <p class="fst-italic" v-if="data.promo.activate_price > 0">
                        <span class="fw-bold text-primary">Внимание!</span> Скидка за промокод доступна только если
                        сумма заказа больше чем
                        <span class="fw-bold text-primary">{{ data.promo.activate_price }}₽</span>, а также данная
                        скидка не распространяется на цену доставки!
                    </p>
                    <h6 v-if="data.promo.discount>0" class="text-center">Скидка за промокод <strong
                        class="fw-bold">{{ data.promo.discount }}
                        <span v-if="data.promo.discount_in_percent">%</span>
                        <span v-else>₽</span>
                    </strong>

                    </h6>

                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="person-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Число персон</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <h6 class="opacity-75 mb-3">Укажите на сколько человек добавить приборы?</h6>


                    <div class="row text-center">

                        <div class="col-4">
                            <button
                                @click="decPersons"
                                type="button" class="btn p-2 w-100 btn-light text-dark"><i
                                class="fa-solid fa-minus font-22"></i></button>
                        </div>

                        <div class="col-4 d-flex justify-content-center align-items-center">
                            <strong
                                class="fw-bold"
                                style="font-size:16px;">{{ data.persons }}</strong>
                        </div>

                        <div class="col-4">
                            <button type="button"
                                    @click="incPersons"
                                    class="btn p-2 w-100 btn-light text-dark"><i
                                class="fa-solid fa-plus font-22"></i></button>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>
</template>
<script>

import {mapGetters} from "vuex";

export default {
    props: ["settings", "data"],
    data() {
        return {
            moneyVariants: [
                500, 1000, 2000, 5000
            ],
        }
    },
    watch: {
        'cartTotalPrice': {
            handler: function (newValue) {
                if (this.settings.free_shipping_starts_from <= this.cartTotalPrice) {
                    this.data.delivery_price = 0
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


        finallyPrice() {
            let isPercentDiscount = this.data.promo.discount_in_percent || false
            let discountValue = this.data.promo.discount || 0
            let activationDiscountPrice = this.data.promo.activate_price || 1

            let price = !this.data.use_cashback ?
                Math.max(activationDiscountPrice, this.cartTotalPrice) :
                Math.max(activationDiscountPrice, this.cartTotalPrice - this.cashbackLimit)

            let computedPriceWithDiscount = isPercentDiscount ? price * ((100 - discountValue) / 100) : price - discountValue;

            return (computedPriceWithDiscount >= activationDiscountPrice ?
                computedPriceWithDiscount : price) + (this.data.cdek.tariff?.delivery_sum || 0) + (this.data.delivery_price || 0)
        },
        canSubmitForm() {
            return (this.spent_time_counter || 0) === 0
                && (!this.data.use_cashback ?
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


    },

    methods: {
        recalcDeliveryPrice(){
            this.$emit("calc-delivery-price")
        },
        activateDiscount(item) {
            this.$emit("discount", item)
        },
        decPersons() {
            this.$emit("person-dec")
        },
        incPersons() {
            this.$emit("person-inc")
        },
    }
}
</script>
