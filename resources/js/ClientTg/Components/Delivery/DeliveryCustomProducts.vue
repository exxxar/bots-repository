<script setup>
import ProductItemSimple from "@/ClientTg/Components/Shop/Products/ProductItemSimple.vue";
import Pagination from "@/ClientTg/Components/Pagination.vue";
import CategoryList from "@/ClientTg/Components/Shop/Categories/CategoryList.vue";

import ReturnToBot from "@/ClientTg/Components/Shop/Helpers/ReturnToBot.vue";
</script>
<template>

    <div class="card card-style">
        <div class="content">

        </div>
    </div>


    <form
        v-on:submit.prevent="startCheckout"
        class="card card-style">
        <div class="content">

            <h4>Ваша корзина</h4>

            <div class="divider mt-3"></div>

            <h4>Итого</h4>
            <p>
                Ниже приведена итоговая цена заказа без учета стоимости доставки. Цена доставки рассчитывается отдельно
                и
                зависит от расстояния.
            </p>
            <div class="row mb-0" v-for="(item, index) in cart">

                <div class="col-6 text-left" v-if="item.product"><h6 class="font-600">
                    {{ item.product.title || 'Не указано' }}</h6></div>
                <div class="col-2 text-center"><h6 class="font-600">x{{ item.quantity || 1 }}</h6></div>
                <div class="col-4 text-right" v-if="item.product"><h6 class="font-600">
                    {{ item.product.current_price || 0 }}
                    <sup>.00</sup>₽</h6>
                </div>


            </div>
            <div class="divider mt-4"></div>
            <div class="row mb-0">
                <div class="col-6 text-left"><h4>Суммарно, ед.</h4></div>
                <div class="col-6 text-right"><h4>{{ cartTotalCount }} шт.</h4></div>
                <div class="col-6 text-left"><h4>Суммарно, цена</h4></div>
                <div class="col-6 text-right"><h4>{{ cartTotalPrice }}<sup>.00</sup>₽</h4></div>

            </div>

            <button
                @click="clearCart"
                class="btn btn-full btn-sm rounded-s bg-red1-dark font-800 text-uppercase w-100">
                <i class="fa-solid  fa-trash-can mr-2"></i><span class="color-white">Очистить корзину</span>
            </button>

            <div class="divider mt-4"></div>

            <h4>Оформление заказа</h4>

            <div class="input-style input-style-2 has-icon">
                <i class="input-icon fa fa-user"></i>

                <input class="form-control"
                       v-model="deliveryForm.name"
                       type="text" placeholder="Иванов Иван Иванович" required>
            </div>

            <div class="input-style input-style-2 has-icon">
                <i class="input-icon fa-solid fa-phone"></i>

                <input class="form-control"
                       type="text"
                       v-mask="'+7(###)###-##-##'"
                       v-model="deliveryForm.phone"
                       placeholder="+7(000)000-00-00"
                       required>
            </div>

            <div class="input-style input-style-2 has-icon">
                <i class="input-icon fa-solid fa-phone"></i>

                <input class="form-control"
                       type="text"
                       v-model="deliveryForm.address"
                       placeholder="г.Краснодар, ул. Героя Яцкова, 9к1, кв 35"
                       required>
            </div>

            <div class="input-style input-style-2 has-icon">
                <span class="input-style-1-active input-style-1-inactive">Информация для доставщика</span>
                <i class="input-icon fa-solid fa-envelope-open-text"></i>
                <textarea class="form-control"
                          style="height:200px;line-height:150%;padding:35px;"
                          v-model="deliveryForm.info"
                          type="text" placeholder=""></textarea>
            </div>

            <button
                type="submit"
                class="btn btn-full btn-sm rounded-s bg-highlight font-800 text-uppercase w-100 mb-2">
                <i class="fa-solid fa-file-invoice mr-2"></i><span class="color-white">Оформить</span>
            </button>


        </div>
    </form>

</template>
<script>


import baseJS from "@/ClientTg/modules/custom";
import {mapGetters} from "vuex";

export default {
    props: ["type", "isSimple"],
    data() {
        return {
            search: null,
            categories: [],
            sending: false,
            dimensions: ["шт", "л", "кг", "гр"],
            deliveryForm: {
                name: null,
                phone: null,
                address: null,
                info: null,
                goods: []
            },
        }
    },

    mounted() {

    },
    methods: {
        addGoods() {

            this.deliveryForm.goods.push(this.isSimple ? {
                title: null,
                count: 0
            } : {
                title: null,
                min_price: null,
                max_price: null,
                count: 0,
                dimension: null,

            })

        },
        startCheckout() {
            let data = new FormData();

            this.sending = true
            Object.keys(this.deliveryForm)
                .forEach(key => {
                    const item = this.deliveryForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            if (this.type)
                data.append("type", this.type)

            this.$store.dispatch("startCheckoutCustom", {
                deliveryForm: data

            }).then((response) => {

                this.deliveryForm = {
                    message: null,
                    name: null,
                    phone: null,
                }

                this.$botNotification.success("Доставка", "Дальнейшая инструкция отправлена вам в бот!")

                this.clearCart();

                this.sending = false
            }).catch(err => {
                this.sending = false
            })
        },

    }
}
</script>
<style>
.scrolled-list {
    width: 100%;
    overflow-x: auto;
}
</style>
