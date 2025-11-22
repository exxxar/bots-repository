<script setup>
import TableBookingPlanner from "@/ClientTg/Components/V2/Shop/Booking/TableBookingPlanner.vue";
</script>
<template>

    <TableBookingPlanner></TableBookingPlanner>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            tab: 0,

        }
    },
    computed: {
        ...mapGetters(['getOrders', 'getOrdersPaginateObject', 'inCart', 'getReviews', 'getReviewsPaginateObject']),
        tg() {
            return window.Telegram.WebApp;
        },
    },
    mounted() {
       // this.loadOrders()

        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })
    },
    methods: {
        loadProductInOrders(page = 0) {
            this.tab = 1

            return this.$store.dispatch("loadReviews", {
                page: page || 0,
                size: 20
            }).then(() => {
                this.reviews = this.getReviews
                this.reviews_paginate_object = this.getReviewsPaginateObject
            })
        },
        repeatOrder(item) {
            let products = item.product_details[0].products.map(o => o.title)

            this.$store.dispatch("repeatOrder", {
                products: products,
            }).then((resp) => {
                this.$store.dispatch("clearCart")

                let currentProducts = resp.data

                if (currentProducts.length === 0) {
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
        nextReviews(index) {
            this.loadProductInOrders(index)
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
