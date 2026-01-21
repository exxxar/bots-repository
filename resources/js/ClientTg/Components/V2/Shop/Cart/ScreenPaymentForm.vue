<template>
    <form
        class="container py-3"
        v-on:submit.prevent="startCheckout">
        <h5 class="my-3 text-left"><i class="fa-regular fa-image mr-2"></i>Фотография чека</h5>

        <div class="alert alert-light mb-2 fw-bold"
             v-if="settings?.payment_info"
             role="alert" v-html="settings.payment_info"></div>

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
                            ₽</strong>
                        </p>
                    </li>
                    <li class="list-group-item">
                        <p class="mb-0 d-flex justify-content-between">Оплата бонусами
                            <strong v-if="modelValue?.use_cashback">{{ cashbackLimit }} ₽</strong>
                            <strong v-else>-</strong>
                        </p>
                    </li>

                    <li class="list-group-item" v-if="modelValue.discount>0">
                        <p class="mb-2 text-justify">
                            <strong class="fw-bold">Внимание!</strong> Не распространяется на цену доставки!
                        </p>
                        <p class="mb-0 d-flex justify-content-between">Промокод
                            <strong>{{ modelValue.discount }} ₽</strong>

                        </p>
                    </li>


                    <li class="list-group-item" v-if="!modelValue.need_pickup">
                        <p class="mb-0 d-flex justify-content-between">Цена доставки
                            <strong v-if="modelValue.delivery_price>0">{{ modelValue.delivery_price }}₽</strong>
                            <strong v-else>от курьера</strong>
                        </p>
                    </li>
                    <li class="list-group-item">
                        <p
                            class="mb-0 d-flex justify-content-between">Итого, цена
                            <strong> {{ finallyPrice }} ₽</strong></p>

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

        <div class="d-flex justify-content-center flex-wrap w-100">
            <label
                v-if="modelValue.image==null"
                for="menu-photos"
                class="photo-loader-bill d-flex flex-column justify-content-center align-items-center mb-2">
                <i class="fa-regular fa-image my-2 text-primary" style="font-size:20px;"></i>
                <span
                    class="text-primary fw-bold text-center">Выбрать фотографию чека в формате: jpg, png, bmp</span>
                <input type="file"
                       required
                       id="menu-photos" accept="image/*"
                       @change="onChangePhotos"
                       style="display:none;"/>

            </label>
            <div class="mb-2 img-preview-bill"
                 v-if="modelValue.image!=null">
                <img v-lazy="getPhoto(modelValue.image).imageUrl">
                <div class="remove">
                    <a @click="removePhoto()"><i class="fa-solid fa-trash-can mr-2 text-primary"></i>Удалить</a>
                </div>
            </div>

        </div>

        <div class="form-floating mb-2">
            <textarea class="form-control"
                      v-model="modelValue.image_info"
                      style="height:100px;line-height:150%;"
                      placeholder="Информация" id="modelValue-image_info"></textarea>
            <label for="modelValue-image_info">Текст к оплате <small>(не обязательно)</small></label>
        </div>

        <!--            ||(!modelValue.use_cashback?settings.min_price>cartTotalPrice:settings.min_price>cartTotalPrice-cashbackLimit)||modelValue.image==null-->
        <button
            type="submit"
            :disabled="!canSubmitForm"
            class="btn btn-primary p-3 w-100">

            <i v-if="spent_time_counter<=0" class="fa-solid fa-file-invoice mr-2"></i>
            <i v-else class="fa-solid fa-hourglass  mr-2"></i>

            <span
                v-if="spent_time_counter<=0"
                class="color-white">Оформить</span>
            <span
                v-else
                class="color-white">Осталось ждать {{ spent_time_counter }} сек.</span>
        </button>
    </form>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["modelValue"],
    data() {
        return {
            spent_time_counter: 0,
        }
    },
    computed: {
        ...mapGetters(['cartProducts', 'cartProducts', 'cartTotalCount', 'cartTotalPrice', 'getSelf']),
        cashbackLimit() {
            let maxUserCashback = this.getSelf.cashBack ? this.getSelf.cashBack.amount : 0
            let summaryPrice = this.cartTotalPrice || 0
            let botCashbackPercent = this.bot.max_cashback_use_percent || 0

            let cashBackAmount = (summaryPrice * (botCashbackPercent / 100));

            return Math.min(cashBackAmount, maxUserCashback)
        },
        settings() {
            return window.currentBot.settings || null
        },
        finallyPrice() {

            let ActivatePromoPrice = (this.modelValue.promo?.activate_price||0)
            let Discount = this.modelValue.promo?.discount || 0

            return !this.modelValue?.use_cashback ?
                Math.max(1, (this.cartTotalPrice -
                    (this.cartTotalPrice >= ActivatePromoPrice ?
                        Discount : 0))) + this.modelValue.delivery_price :
                Math.max(1, (this.cartTotalPrice - this.cashbackLimit -
                    (this.cartTotalPrice >= ActivatePromoPrice ?
                        Discount : 0))) + this.modelValue.delivery_price
        },
    },
    watch: {

        'modelValue': {
            handler: function (newValue) {
                this.$emit("update:modelValue", this.modelValue)
            },
            deep: true
        },


    },
    mounted() {

    },
    methods: {
        startCheckout() {
            if (this.spent_time_counter > 0)
                return;

            this.startTimer(10);
            this.$emit("startCheckout")
        },
        onChangePhotos(e) {
            const file = e.target.files[0]

            if (!file) return;

            this.modelValue.image = file
        },
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto() {
            this.modelValue.image = null
        },
        canSubmitForm() {
            return this.modelValue.image != null
        },
        startTimer(time) {
            this.spent_time_counter = time != null ? Math.min(time, 10) : 10;

            let counterId = setInterval(() => {
                    if (this.spent_time_counter > 0)
                        this.spent_time_counter--
                    else {
                        clearInterval(counterId)
                        this.spent_time_counter = null
                    }
                    localStorage.setItem("cashman_self_product_delivery_counter", this.spent_time_counter)
                }, 1000
            )
        },
    }
}
</script>
