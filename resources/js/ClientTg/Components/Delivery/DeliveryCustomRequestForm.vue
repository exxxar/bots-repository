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
        class="card card-style" v-if="cartProducts.length>0">
        <div class="content">

            <h4>Ваша корзина</h4>
            <ProductItemSimple :item="item.product" v-for="(item, index) in cartProducts"/>

            <div class="divider mt-3"></div>

            <h4>Итого</h4>
            <p>
                Ниже приведена итоговая цена заказа без учета стоимости доставки. Цена доставки рассчитывается отдельно
                и
                зависит от расстояния.
            </p>
            <div class="row mb-0" v-for="(item, index) in cartProducts">

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
                       placeholder="г.Краснодар, ул. Ленинина, 106"
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
    props: ["type"],
    data() {
        return {
            isCollapsed: true,
            search: null,
            products: [],
            paginate: null,
            categories: [],
            sending: false,
            dimensions: ["шт", "л", "кг", "гр"],
            deliveryForm: {
                name: null,
                phone: null,
                address: null,
                info: null,
                goods: [
                    {
                        title: null,
                        min_price: null,
                        max_price: null,
                        count: 0,
                        dimension: null,

                    }
                ]
            },
        }
    },
    computed: {
        ...mapGetters(['getProducts', 'getProductsPaginateObject', 'cartProducts', 'cartTotalCount', 'cartTotalPrice']),
        filteredProducts() {

            if (!this.search)
                return this.products

            return this.products.filter(product => product.title.toLowerCase().trim().indexOf(this.search.toLowerCase().trim()) >= 0)
        },
    },
    mounted() {

        this.clearCart();

        this.loadProducts()

        if (this.cartProducts.length > 0)
            this.loadActualProducts()
    },
    methods: {
        clearCart() {
            this.$store.dispatch("clearCart").then(() => {
                this.$botNotification.success("Корзина", "Корзина успешно очищена")
            })

        },
        selectCategory(item) {
            let index = this.categories.findIndex(category => category.id === item.id)
            if (index !== -1) {
                this.categories.splice(index, 1)
                return;
            }
            this.categories.push(item)
        },
        nextProducts(index) {
            this.loadProducts(index)
        },
        loadProducts(page = 0) {
            return this.$store.dispatch("loadProducts", {
                dataObject: {
                    search: this.search,
                    categories: this.categories.map(o => o['id']),
                    min_price: this.min_price,
                    max_price: this.max_price
                },
                page: page
            }).then(() => {
                this.products = this.getProducts
                this.paginate = this.getProductsPaginateObject
                baseJS.handler()
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
        loadActualProducts() {
            this.$store.dispatch("loadActualPriceInCart")
        },
        removeCategory(index) {
            this.categories.splice(index, 1)
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
