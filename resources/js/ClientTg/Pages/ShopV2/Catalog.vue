<script setup>
import ProductCategories from "@/ClientTg/Components/ShopV2/ProductCategories.vue";
import ProductCard from "@/ClientTg/Components/ShopV2/ProductCard.vue";
import Pagination from "@/ClientTg/Components/Pagination.vue";
</script>
<template>


    <menu class="d-block position-sticky w-100 header-category-slider">
        <ProductCategories v-on:select="selectCategory"></ProductCategories>
    </menu>

    <div class="album py-2 bg-body-tertiary">
        <div class="container">


            <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-3" v-if="(products||[]).length>0">
                <div class="col"
                     v-for="(product, index) in filteredProducts">
                    <ProductCard
                        :item="product"
                    />
                </div>

            </div>
        </div>
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <div class="col">
                    <Pagination
                        :simple="true"
                        v-on:pagination_page="nextProducts"
                        v-if="paginate"
                        :pagination="paginate"/>
                </div>
            </div>
        </div>

    </div>
    <footer class="text-body-secondary py-5">
        <div class="container">
            <p class="float-end mb-1">
                <a href="#">Back to top</a>
            </p>
            <p class="mb-1">Album example is © Bootstrap, but please download and customize it for yourself!</p>
            <p class="mb-0">New to Bootstrap? <a href="/">Visit the homepage</a> or read our <a
                href="/docs/5.3/getting-started/introduction/">getting started guide</a>.</p>
        </div>
    </footer>

    <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
        <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button"
                aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (dark)">
            <svg class="bi my-1 theme-icon-active" width="1em" height="1em">
                <use href="#moon-stars-fill"></use>
            </svg>
            <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
            <li>
                <button type="button"
                        @click="changeTheme('light')"
                        class="dropdown-item d-flex align-items-center" data-bs-theme-value="light"
                        aria-pressed="false">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#sun-fill"></use>
                    </svg>
                    Light
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button type="button"
                        @click="changeTheme('dark')"
                        class="dropdown-item d-flex align-items-center active" data-bs-theme-value="dark"
                        aria-pressed="true">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#moon-stars-fill"></use>
                    </svg>
                    Dark
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
            <li>
                <button type="button"
                        @click="changeTheme('auto')"
                        class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="false">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em">
                        <use href="#circle-half"></use>
                    </svg>
                    Auto
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                        <use href="#check2"></use>
                    </svg>
                </button>
            </li>
        </ul>
    </div>


    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-bottom p-0" style="border-radius:10px 10px 0px 0px;">
        <button class="btn btn-primary w-100 p-3 rounded-0">Корзина <strong>{{cartTotalPrice || 0}}<sup class="font-10 opacity-50">.00</sup>₽</strong></button>
    </nav>
</template>
<script>

import {mapGetters} from "vuex";

export default {

    data() {
        return {
            tab: 0,
            settings: {
                can_use_cash: true,
                delivery_price_text: null,
                min_price: 0,
                min_price_for_cashback: 0,
                menu_list_type: 0,
                need_category_by_page: false,
            },
            product_type_display: 1,
            spent_time_counter: 0,
            is_requested: false,
            isCollapsed: true,
            search: null,
            products: [],
            paginate: null,
            categories: [],
            sending: false,
            min_price: null,
            max_price: null,
            moneyVariants: [
                500, 1000, 2000, 5000
            ],
            deliveryForm: {
                name: null,
                phone: null,
                address: null,
                city: null,
                street: null,
                building: null,
                flat_number: null,
                entrance_number: null,
                floor_number: null,
                info: null,
                need_pickup: false,
                has_disability: false,
                use_cashback: false,
                disabilities: [],
                money: null,
                cash: true,
                persons: 1,
                time: null,
                when_ready: true,// по готовности
            },
        }
    },
    computed: {
        ...mapGetters(['getProducts', 'getProductsPaginateObject', 'cartProducts', 'cartTotalCount', 'cartTotalPrice', 'getSelf']),
        getCurrentBot() {
            return window.currentBot
        },
        cashbackLimit() {
            let maxUserCashback = this.getSelf.cashBack ? this.getSelf.cashBack.amount : 0
            let summaryPrice = this.cartTotalPrice || 0
            let botCashbackPercent = this.getCurrentBot.max_cashback_use_percent || 0

            let cashBackAmount = (summaryPrice * (botCashbackPercent / 100));

            return Math.min(cashBackAmount, maxUserCashback)
        },
        filteredProducts() {

            if (this.categories.length===0)
                return this.products


            return this.products.filter(product => product.categories.findIndex(cat=>this.categories.indexOf(cat.id)!==-1)!==-1)
        },
        tg() {
            return window.Telegram.WebApp;
        },
        activeCategories() {
            let tmp = [];
            this.filteredProducts.forEach(item => {
                tmp.push(item.categories.map(o => o['id']))
            })
            return [...new Set(tmp.map(item => item[0])), ...new Set(tmp.map(item => item[1]))];
        }
    },
    mounted() {
        this.loadProducts()
        this.loadShopModuleData()

        if (this.cartProducts.length > 0)
            this.loadActualProducts()
    },
    methods: {
        changeTheme(name) {
            let themes = document.querySelectorAll("[data-bs-theme]")

            themes.forEach(item => {
                console.log("item", item)
                item.setAttribute("data-bs-theme", name)
            })
        },
        decPersons() {
            this.deliveryForm.persons = this.deliveryForm.persons > 1 ? this.deliveryForm.persons - 1 : this.deliveryForm.persons;
        },
        incPersons() {
            this.deliveryForm.persons = this.deliveryForm.persons < 100 ? this.deliveryForm.persons + 1 : this.deliveryForm.persons;
        },
        selectProductTypeDisplay(type) {
            this.product_type_display = type
            localStorage.setItem("cashman_self_product_type_display", this.product_type_display)
        },
        startTimer(time) {
            this.spent_time_counter = time != null ? Math.min(time, 10) : 10;

            let counterId = setInterval(() => {
                    if (this.spent_time_counter > 0)
                        this.spent_time_counter--
                    else {
                        clearInterval(counterId)
                        this.is_requested = false
                        this.spent_time_counter = null
                    }
                    localStorage.setItem("cashman_self_product_delivery_counter", this.spent_time_counter)
                }, 1000
            )
        },
        clearCart() {
            this.tab = 1
            this.$store.dispatch("clearCart").then(() => {
                this.$botNotification.success("Корзина", "Корзина успешно очищена")
            })

        },
        resetCategories() {
            this.categories = []
            this.loadProducts(0)
        },
        selectCategory(item) {
            this.categories = [item]

           // this.loadProducts(0)
        },
        nextProducts(index) {
            this.loadProducts(index)
        },
        loadShopModuleData() {
            return this.$store.dispatch("loadShopModuleData").then((resp) => {
                this.$nextTick(() => {
                    Object.keys(resp).forEach(item => {
                        this.settings[item] = resp[item]
                        console.log("settings", this.settings[item], item)
                    })
                })
            })
        },
        loadProducts(page = 0) {
            return this.$store.dispatch("loadProducts", {
                dataObject: {
                    search: this.search,
                    categories: this.categories.length > 0 ? this.categories.map(o => o['id']) : null,
                    min_price: this.min_price || null,
                    max_price: this.max_price || null
                },
                page: page,
                size:1000
            }).then(() => {
                this.products = this.getProducts
                this.paginate = this.getProductsPaginateObject

            })
        },
        startCheckout() {

            if (this.is_requested) {
                this.$botNotification.warning("Упс!", `Сделать повторный заказ можно через <strong>${this.spent_time_counter} сек.</strong>`)
                return;
            }


            localStorage.setItem("cashman_self_product_delivery_form_name", this.deliveryForm.name || '')
            localStorage.setItem("cashman_self_product_delivery_form_phone", this.deliveryForm.phone || '')
            localStorage.setItem("cashman_self_product_delivery_form_address", this.deliveryForm.address || '')
            localStorage.setItem("cashman_self_product_delivery_form_city", this.deliveryForm.city || '')
            localStorage.setItem("cashman_self_product_delivery_form_street", this.deliveryForm.street || '')
            localStorage.setItem("cashman_self_product_delivery_form_building", this.deliveryForm.building || '')
            localStorage.setItem("cashman_self_product_delivery_form_flat_number", this.deliveryForm.flat_number || '')

            localStorage.setItem("cashman_self_product_delivery_form_entrance_number", this.deliveryForm.entrance_number || '')
            localStorage.setItem("cashman_self_product_delivery_form_entrance_disabilities", JSON.stringify(this.deliveryForm.disabilities || []))

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

            this.$store.dispatch("startCheckout", {
                deliveryForm: data

            })
                .then((response) => {

                    this.deliveryForm = {
                        message: null,
                        name: null,
                        phone: null,
                    }

                    this.$botNotification.success("Доставка", "Дальнейшая инструкция отправлена вам в бот!")

                    this.tab = 1
                    this.$store.dispatch("clearCart");
                    //this.clearCart();

                    this.tg.close();

                    this.sending = false
                }).catch(err => {
                this.sending = false
            })

            this.startTimer();
            this.is_requested = true
        },
        loadActualProducts() {
            this.$store.dispatch("loadActualPriceInCart")
        },
        removeCategory(index) {
            this.categories.splice(index, 1)
        },
        addToCart(item) {
            this.$cart.add(item.product)
        },
    }
}
</script>
<style>
.header-category-slider {
    top: 0px;
    background: #212529;
    padding: 11px 0px;
    font-size: 14px;
    z-index: 100;
}

.selected-category-item {
    padding: 5px;
    margin: 0;
    position: relative;
}

.carousel__prev,
.carousel__next {
    display: none;
}
</style>
