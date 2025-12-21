<script setup>
import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
import ReviewCard from "@/ClientTg/Components/V2/Shop/ReviewCard.vue";
import OrderItem from "@/ClientTg/Components/V2/Admin/Orders/OrderItem.vue";
</script>
<template>
    <div class="row">
        <div class="col-12">
            <h5 class="my-3"><i class="fa-solid fa-list-check mr-1 text-primary"></i> Список заказов</h5>

            <div class="form-floating mb-2">
                <input type="search"
                       v-model="search"
                       class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Поиск по заказам</label>
            </div>

        </div>

        <div class="col-12">
            <ul class="nav nav-tabs justify-content-center">
                <li class="nav-item">
                    <a class="nav-link"
                       v-bind:class="{'active':tab===0}"
                       @click="tab=0"
                       aria-current="page"
                       href="javascript:void(0)">Заказы</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       v-bind:class="{'active':tab===1}"
                       @click="loadProductInOrders(0)"
                       href="javascript:void(0)">Товары</a>
                </li>
            </ul>
        </div>
        <div class="col-12" v-if="tab===0">
            <div class="form-floating my-2">
                <select
                    @change="loadOrders(0)"
                    v-model="sort.param"
                    class="form-select" id="floatingSelect" aria-label="Floating label select example">
                    <option value="id">По номеру заказа</option>
                    <option value="is_cashback_crediting">По начислению CashBack</option>
                    >
                    <option value="summary_price">По цене заказа</option>
                    <option value="product_count">По числу товара в заказе</option>
                    <option value="updated_at">По дате добавления</option>
                </select>
                <label for="floatingSelect">Сортировать заказы по</label>
            </div>
            <p v-if="sort.param!=null">Направление сортировки:
                <span
                    class="fw-bold"
                    @click="changeDirection('desc')"
                    v-if="sort.direction==='asc'">по возрастанию <i class="fa-solid fa-caret-up"></i></span>
                <span
                    class="fw-bold"
                    @click="changeDirection('asc')"
                    v-if="sort.direction==='desc'">по убыванию <i class="fa-solid fa-caret-down"></i></span>
            </p>

            <div class="list-group my-2" v-if="(orders||[]).length>0">
                <OrderItem :item="item" v-for="item in orders"></OrderItem>
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
        <div class="col-12" v-if="tab===1">
            <template v-for="(review, index) in reviews">
                <ReviewCard
                    :is-admin="true"
                    v-model="reviews[index]" :need-product="true"></ReviewCard>
                <hr>
            </template>

            <Pagination
                :simple="true"
                v-on:pagination_page="nextReviews"
                v-if="reviews_paginate_object"
                :pagination="reviews_paginate_object"/>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["botUser"],
    data() {
        return {
            search: null,
            tab: 0,

            sort: {
                param: 'id',
                direction: 'desc'
            },
            orders: null,
            orders_paginate_object: null,
            reviews: [],
            reviews_paginate_object: null,
        }
    },
    computed: {
        ...mapGetters(['getOrders', 'getOrdersPaginateObject', 'inCart', 'getReviews', 'getReviewsPaginateObject']),
        tg() {
            return window.Telegram.WebApp;
        },
    },
    mounted() {
        this.loadOrders()


        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.push({name: 'MenuV2'})
        })
    },
    methods: {
        changeDirection(direction) {
            this.sort.direction = direction
            this.loadOrders(0)
        },
        loadProductInOrders(page = 0) {
            this.tab = 1

            return this.$store.dispatch("loadReviews", {
                dataObject:{
                    bot_user_id: this.botUser ? this.botUser.id : null,
                },
                page: page || 0,
                size: 20
            }).then(() => {
                this.reviews = this.getReviews
                this.reviews_paginate_object = this.getReviewsPaginateObject
            })
        },

        nextOrders(index) {
            this.loadOrders(index)
        },
        nextReviews(index) {
            this.loadProductInOrders(index)
        },

        loadOrders(page = 0) {
            return this.$store.dispatch("loadAllOrders", {
                dataObject: {
                    search: this.search || null,
                    order_by: this.sort.param || null,
                    direction: this.sort.direction || 'asc',
                    bot_user_id: this.botUser ? this.botUser.id : null,
                },
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
