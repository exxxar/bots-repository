<script setup>
import Pagination from "@/ClientTg/Components/Pagination.vue";
</script>
<template>

    <div class="container g-2">
        <div class="row">
            <div class="col-12">
                <h5 class="my-3"><i class="fa-solid fa-list-check mr-1 text-primary"></i> Список заказов</h5>

                <div class="alert alert-light mb-3 fw-bold" role="alert">
                    <strong class="text-primary">Внимание!</strong> При повторном заказе автоматически формируется корзина из доступных к заказу товаров из вашего списка. Товары в стоп-листе заведения добавлены не будут.
                </div>
            </div>
            <div class="col-12">
                <div class="list-group" v-if="orders">

                    <a
                        href="javascript:void(0)"
                        @click="select(item)"
                        v-for="item in orders"
                        style="font-weight:bold;"
                        class="list-group-item list-group-item-action d-flex justify-content-between p-3
                       align-items-start flex-column" aria-current="true">
                        <p><i class="fa-solid fa-stopwatch mr-2 text-primary"></i> Время заказа {{ item.created_at }}
                        </p>

                        <ul>
                            <li v-for="product in  item.product_details[0].products">{{ product.title }}</li>
                        </ul>

                        <button type="button"
                                v-if="!item.disabled"
                                @click="repeatOrder(item)"
                                class="btn btn-primary w-100 p-3">
                            <i class="fa-solid fa-arrow-rotate-right mr-2"></i> Повторить заказ
                        </button>
                    </a>

                </div>
                <div v-else class="d-flex flex-column justify-content-center align-items-center" style="height:100vh;">
                    <div class="d-flex justify-content-center flex-column align-items-center">
                        <i class="fa-brands fa-shopify mb-3" style="font-size:36px;"></i>
                        <p>Заказов еще нет:(</p>
                    </div>
                </div>

                <Pagination
                    :simple="true"
                    v-on:pagination_page="nextOrders"
                    v-if="orders_paginate_object"
                    :pagination="orders_paginate_object"/>
            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["selected", "active"],
    data() {
        return {
            orders: null,
            orders_paginate_object: null,
        }
    },
    computed: {
        ...mapGetters(['getOrders', 'getOrdersPaginateObject', 'inCart']),

    },
    mounted() {
        this.loadOrders()
    },
    methods: {
        repeatOrder(item) {
            let products = item.product_details[0].products.map(o => o.title)

            this.$store.dispatch("repeatOrder", {
                products: products,
            }).then((resp) => {
                this.$store.dispatch("clearCart")

                let currentProducts = resp.data

                if (currentProducts.length===0)
                {
                    item.disabled = true

                    this.$notify({
                        title: "Корзина",
                        text: "Нет доступных к заказу товаров:(",
                    })

                    return;
                }

                currentProducts.forEach(item => {
                    if (this.inCart(item) === 0)
                        this.$store.dispatch("addProductToCart", item)
                    else
                        this.$store.dispatch("incQuantity", item.id)
                })

                this.$router.push({name: 'ShopCartV2'})
            }).catch(() => {
                this.$notify({
                    title: "Корзина",
                    text: "Ошибка формирования заказа",
                    type: "error"
                })
            })
        },
        nextOrders(index) {
            this.loadOrders(index)
        },
        select(item) {
            return this.$emit("select", item)
        },

        loadOrders(page = 0) {
            return this.$store.dispatch("loadOrders", {
                page: page,
                size: 20
            }).then(() => {
                this.orders = this.getOrders
                this.orders_paginate_object = this.getOrdersPaginateObject
            })
        },
    }
}
</script>
<style>

</style>
