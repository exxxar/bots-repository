<script setup>
import ProductCategories from "@/ClientTg/Components/ShopV2/ProductCategories.vue";
import ProductCard from "@/ClientTg/Components/ShopV2/ProductCard.vue";
import Pagination from "@/ClientTg/Components/Pagination.vue";
import CategoryList from "@/ClientTg/Components/ShopV2/CategoryList.vue";
</script>
<template>

    <div v-touch:swipe.left="doSwipeLeft"
         v-touch:swipe.right="doSwipeRight" class="d-flex flex-column">
        <menu
            v-bind:style="colorTheme"
            class="d-block position-sticky w-100 header-category-slider">

            <ul class="nav nav-tabs justify-content-center catalog-tabs">
                <li class="nav-item">
                    <a class="nav-link"
                       @click="openTab(0)"
                       style="font-weight:bold;"
                       v-bind:class="{'active':tab===0}"
                       aria-current="page" href="javascript:void(0)"><i class="fa-solid fa-tag mr-2"></i>Категории</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       @click="openTab(1)"
                       style="font-weight:bold;"
                       v-bind:class="{'active':tab===1}"
                       href="javascript:void(0)"><i class="fa-solid fa-bag-shopping mr-2"></i>Товары</a>
                </li>

            </ul>
        </menu>

        <div
            v-show="tab===0"
            class="album" style="min-height:100vh;">
            <div class="container g-2">
                <div class="list-group" v-if="products">
                    <a
                        href="javascript:void(0)"
                        @click="selectCategory(null)"
                        style="font-weight:bold;"
                        class="list-group-item list-group-item-action d-flex justify-content-between p-3" aria-current="true">
                        Все категории товаров
                    </a>

                    <a
                        href="javascript:void(0)"
                        @click="selectCategory(item)"
                        v-for="item in products"
                        style="font-weight:bold;"
                        class="list-group-item list-group-item-action d-flex justify-content-between p-3 align-items-center" aria-current="true">
                        {{ item.title || 'Не указано' }}<span class="badge text-bg-primary">{{ item.count || 0 }}</span>
                    </a>

                </div>

<!--                <CategoryList
                    :selected="categories"
                    v-on:select="selectCategory"/>-->
            </div>
        </div>

        <div
            v-if="tab===1"
            style="min-height:100vh;"
            class="album">
            <div class="container g-2">

                <template v-for="cat in products">
                    <h5 class="my-4 divider" :id="'cat-'+cat.id">{{ cat.title || '-' }}</h5>

                    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-2">
                        <div class="col"
                             v-for="(product, index) in cat.products">

                            <ProductCard
                                :item="product"
                            />
                        </div>

                    </div>
                </template>

                <!--                <p class="mb-2 text-center" v-if="paginate"><small>Всего товаров найдено ({{
                                        paginate.meta.total
                                    }})</small></p>

                                <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-2"
                                     v-if="(products||[]).length>0">
                                    <div class="col"
                                         v-for="(product, index) in products">
                                        <ProductCard
                                            :item="product"
                                        />
                                    </div>

                                </div>-->
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


    </div>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-bottom p-0" style="border-radius:10px 10px 0px 0px;">
        <button
            @click="goToCart"
            class="btn btn-primary w-100 p-3 rounded-0">
            Корзина <strong>{{ cartTotalPrice || 0 }}<sup class="font-10 opacity-50">.00</sup>₽</strong>
        </button>
    </nav>

    <div class="catalog-preloader" v-if="load_content">
        <div class="d-flex flex-column align-items-center">
            <div class="spinner-grow bg-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="py-2">Загружаем...</p>
        </div>
    </div>


</template>
<script>

import {mapGetters} from "vuex";

export default {

    data() {
        return {
            tab: 1,
            load_content: false,
            settings: {
                can_use_cash: true,
                delivery_price_text: null,
                min_price: 0,
                min_price_for_cashback: 0,
                menu_list_type: 0,
                need_category_by_page: false,
                need_pay_after_call: false,
            },
            product_type_display: 1,
            spent_time_counter: 0,
            is_requested: false,
            isCollapsed: true,
            search: null,
            products: [],
            products_with_categories: [],
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
        ...mapGetters(['getProducts', 'getCategories', 'getProductsPaginateObject', 'cartProducts', 'cartTotalCount', 'cartTotalPrice', 'getSelf']),
        colorTheme() {
            const theme = document.querySelector("[data-bs-theme]").getAttribute('data-bs-theme')

            return "background-color:" + (theme === "light" ? "white" : "#212529");
        },
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

            if (this.categories.length === 0)
                return this.products


            return this.products.filter(product => product.categories.findIndex(cat => this.categories.indexOf(cat.id) !== -1) !== -1)
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
        //this.clearCart();

        this.loadProducts()
        this.loadShopModuleData()

        if (this.cartProducts.length > 0)
            this.loadActualProducts()


        this.tg.BackButton.hide()
    },
    methods: {
        scroll(id) {
            // document.getElementById(id).scrollIntoView();
            var element = document.getElementById(id);
            var headerOffset = 70;
            var elementPosition = element.getBoundingClientRect().top;
            var offsetPosition = elementPosition + window.pageYOffset - headerOffset;
            window.scroll({
                top: offsetPosition,
                behavior: "smooth"
            });
        },
        openTab(tab) {
            this.tab = tab
            window.scroll(0, 80);
        },
        closeModalOnSwipe() {
            let modals = document.querySelectorAll('.modal.show')

            if (modals.length > 0) {
                document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())
                return true;
            }
            return false;
        },
        doSwipeLeft() {
            if (this.closeModalOnSwipe())
                return;

            let limit = 1
            if (this.tab === 0)
                this.tab = limit
            else
                this.tab--;

            window.scroll(0, 80);
        },
        doSwipeRight() {
            if (this.closeModalOnSwipe())
                return;

            let limit = 1
            if (this.tab === limit)
                this.tab = 0
            else
                this.tab++;

            window.scroll(0, 80);

        },
        goToCart() {

            this.$router.push({name: 'ShopCartV2'})

        },

        decPersons() {
            this.deliveryForm.persons = this.deliveryForm.persons > 1 ? this.deliveryForm.persons - 1 : this.deliveryForm.persons;
        },
        incPersons() {
            this.deliveryForm.persons = this.deliveryForm.persons < 100 ? this.deliveryForm.persons + 1 : this.deliveryForm.persons;
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

        selectCategory(item) {
            //this.categories = item ? [item] : []
            this.tab = 1

            this.$nextTick(() => {
                if (item)
                    this.scroll("cat-" + item.id)
                else
                    window.scroll(0, 80);
            })

            //this.loadProducts(0)
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
            this.tab = 1
            this.load_content = true


            return this.$store.dispatch("loadProductsByCategory"/*, {
                dataObject: {
                    search: this.search,
                    categories: this.categories.length > 0 ? this.categories.map(o => o['id']) : null,
                    min_price: this.min_price || null,
                    max_price: this.max_price || null
                },
                page: page,
                size: 100
            }*/).then((resp) => {
                this.products = resp.data
                /*   this.products = this.getProducts
                   this.paginate = this.getProductsPaginateObject*/
                this.load_content = false
                window.scroll(0, 80);
            }).catch(() => {
                this.load_content = false
            })
        },
        startCheckout() {

            if (this.is_requested) {

                this.$notify( {
                    title:'Упс!',
                    text:"Сделать повторный заказ можно через ${this.spent_time_counter} сек.",
                    type:"error",
                });
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

                    this.$notify({
                        title: "Доставка",
                        text: "Дальнейшая инструкция отправлена вам в бот!",
                        type: "success"
                    })

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
<style lang="scss">
.header-category-slider {
    top: -1px;
    padding: 11px 0px;
    font-size: 14px;
    z-index: 100;
}

.catalog-tabs {
    .nav-item {
        position: relative;
        bottom: -1px;
    }
}

.catalog-preloader {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1033;
    width: 100%;
    height: 100vh;
    background-color: #000000d9;

    display: flex;
    justify-content: center;
    align-items: center;
}

.divider {
    display: flex;
    align-items: center;
}

.divider::before, .divider::after {
    flex: 1;
    content: "";
    padding: 1px;
    background-color: var(--bs-primary);
    margin: 5px;
}
</style>
