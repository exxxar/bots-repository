<script setup>
import ProductCategories from "@/ClientTg/Components/V2/Shop/ProductCategories.vue";
import ProductCard from "@/ClientTg/Components/V2/Shop/ProductCard.vue";
import ProductListItem from "@/ClientTg/Components/V2/Shop/ProductListItem.vue";
import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
import CategoryList from "@/ClientTg/Components/V2/Shop/CategoryList.vue";
import CollectionCard from "@/ClientTg/Components/V2/Shop/CollectionCard.vue";
import PreloaderV1 from "@/ClientTg/Components/V2/Shop/Other/PreloaderV1.vue";
import CategorySlider from "@/ClientTg/Components/V2/Shop/CategorySlider.vue";
</script>
<template>

    <menu
        id="category-slider"
        class="d-block w-100 p-0 m-0">
        <CategorySlider
            :settings="settings"
            :categories="filteredCategories"
            :collections="collections"
            @select="selectCategory"
            @search="findProducts"
        />

    </menu>

    <div
         class="d-flex flex-column">

        <template v-if="settings">
            <div class="p-2" v-if="settings.is_disabled">
                <div class="alert alert-danger mb-0">
                    <p class="mb-0" v-html="settings.disabled_text"></p>
                </div>
            </div>
        </template>



        <!--        <menu
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
                </menu>-->

        <div
            v-show="tab===0"
            class="album" style="min-height:100vh;">
            <template v-if="settings">
                <div class="container g-2">

                    <!--        <div class="p-2">
            <div class="input-group">
                <div class="form-floating">
                    <input type="search"
                           v-model="search"
                           class="form-control border-light" id="search-product" placeholder="name@example.com">
                    <label for="search-product">Поиск по товарам</label>
                </div>
                <button class="btn btn-outline-light " type="button" id="button-addon2"><i
                    class="fa-solid fa-magnifying-glass-arrow-right"></i></button>
            </div>
        </div>-->
                    <div class="list-group" v-if="filteredCategories.length>0">
                        <a
                            href="javascript:void(0)"
                            @click="selectCategory(null)"
                            style="font-weight:bold;"
                            class="list-group-item list-group-item-action d-flex justify-content-between p-3"
                            aria-current="true">
                            Все категории товаров
                        </a>
                        <a
                            v-if="collections.length>0"
                            href="javascript:void(0)"
                            @click="selectCategory({id:'combo'})"
                            style="font-weight:bold;"
                            class="list-group-item list-group-item-action d-flex justify-content-between p-3"
                            aria-current="true">
                            Комбо-меню<span class="badge text-bg-primary">{{ collections.length || 0 }}</span>
                        </a>

                        <a
                            href="javascript:void(0)"
                            @click="selectCategory(item)"
                            v-for="item in filteredCategories"
                            style="font-weight:bold;"
                            class="list-group-item list-group-item-action d-flex justify-content-between p-3 align-items-center"
                            aria-current="true">
                            {{ item.title || 'Не указано' }}<span class="badge text-bg-primary">{{
                                item.count || 0
                            }}</span>
                        </a>

                    </div>

                    <!--                <CategoryList
                                        :selected="categories"
                                        v-on:select="selectCategory"/>-->
                </div>
            </template>
            <PreloaderV1 v-else/>
        </div>

        <div
            v-if="tab===1"
            style="min-height:100vh;"
            class="album">
            <template v-if="settings">
                <div class="container g-2">


                    <template
                        v-if="collections.length>0">
                        <h5 class="my-4 divider" id="cat-combo">Комбо меню</h5>
                        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-2">
                            <div class="col"
                                 v-for="(collection, index) in collections">

                                <CollectionCard
                                    :item="collection"
                                />


                            </div>
                        </div>
                        <div
                            v-if="collections_paginate?.meta.last_page > 1"
                            class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                            <div class="col">
                                <Pagination
                                    :simple="true"
                                    v-on:pagination_page="nextCollections"
                                    v-if="collections_paginate"
                                    :pagination="collections_paginate"/>
                            </div>
                        </div>
                    </template>

                    <template
                        v-if="filteredCategories.length>0"
                        v-for="cat in filteredCategories">
                        <h5 class="my-4 divider" :id="'cat-'+cat.id">{{ cat.title || '-' }}</h5>

                        <div
                            v-if="!settings.is_product_list"
                            class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-2">
                            <div class="col"
                                 v-for="(product, index) in cat.products">

                                <ProductCard

                                    :item="product"
                                />
                            </div>

                        </div>

                        <ol
                            v-else
                            class="list-group list-group-numbered">
                            <template v-for="(product, index) in cat.products">
                                <ProductListItem
                                    :item="product"
                                />
                            </template>

                        </ol>


                    </template>
                    <PreloaderV1 v-else/>
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
            </template>
            <PreloaderV1 v-else/>
        </div>
    </div>

</template>
<script>

import {mapGetters} from "vuex";
import {v4 as uuidv4} from "uuid";

export default {
    props: ["settings"],
    data() {
        return {
            tab: 1,
            load_content: false,
            load_collection: false,

            product_type_display: 1,
            spent_time_counter: 0,
            is_requested: false,
            isCollapsed: true,
            search: null,
            products: [],
            collections: [],
            collections_paginate: [],
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
        ...mapGetters([
            'inCart',
            'inCollectionCart',

            'getCollections',
            'getCollectionsPaginateObject',
            'getProducts',
            'getCategories', 'getProductsPaginateObject', 'cartProducts', 'cartTotalCount', 'cartTotalPrice', 'getSelf']),

        uuid() {
            const data = uuidv4();
            return data
        },

        bot() {
            return window.currentBot
        },
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
        filteredCategories() {
            if (!this.products || this.products.length === 0) {
                return []
            }

            if (this.search instanceof Event)
                return this.products

            if (!this.search)
                return this.products

            console.log("search", this.search)

            const query = this.search.toLowerCase()

            return this.products
                .map(category => {
                    const filteredProducts = category.products.filter(product =>
                        product.title?.toLowerCase().includes(query)
                    )

                    return {
                        ...category,
                        products: filteredProducts
                    }
                })
                .filter(category => category.products.length > 0)

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

        const slider = document.getElementById('category-slider')

        const handleScroll = () => {
            if (!slider) return

            if (window.scrollY > 70) {
                slider.style.position = 'fixed'
                slider.style.top = '0'
                slider.style.left = '0'
                slider.style.right = '0'
                slider.style.zIndex = '1000'
               // slider.style.backgroundColor = 'white'
              //  slider.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)'
            } else {
                slider.style.position = 'relative'
                slider.style.top = ''
                slider.style.left = ''
                slider.style.right = ''
                slider.style.zIndex = ''
                slider.style.backgroundColor = ''
                slider.style.boxShadow = ''
            }
        }

        window.addEventListener('scroll', handleScroll)


        //this.clearCart();
        this.loadProducts()
        this.loadCollections()

        /*  if (this.cartProducts.length > 0)
              this.loadActualProducts()*/


        this.tg.BackButton.show()


        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })
    },
    methods: {
        findProducts(text){
          this.search = text
        },
        /*   checkCollectionInCart(item){
               return this.inCollectionCart(item.id, null) > 0
           },*/
        scroll(id) {
            // document.getElementById(id).scrollIntoView();
            var element = document.getElementById(id);
            var headerOffset = 70;
            var elementPosition = element?.getBoundingClientRect().top||70;
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
                this.$notify({
                    title: 'Корзина',
                    text: "Корзина успешно очищена!",
                    type: 'success'
                })

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
        nextCollections(index) {
            this.loadCollections(index)
        },

        loadCollections(page = 0) {
            this.load_content = true
            this.load_collection = true
            return this.$store.dispatch("loadGlobalCollections", {
                page: page
            }).then((resp) => {
                this.collections = this.getCollections
                this.collections_paginate = this.getCollectionsPaginateObject

                this.load_content = false
                this.load_collection = false
                window.scroll(0, 80);
            }).catch(() => {
                this.load_content = false
                this.load_collection = false

                this.$store.dispatch("clearCart").then(() => {
                    this.loadCollections()
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

                this.$store.dispatch("clearCart").then(() => {
                    this.loadProducts()
                })
            })
        },
        startCheckout() {

            if (this.is_requested) {

                this.$notify({
                    title: 'Упс!',
                    text: "Сделать повторный заказ можно через ${this.spent_time_counter} сек.",
                    type: "error",
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
    padding: 10px 0px;
    font-size: 14px;
    z-index: 100;
    margin: 0;
}

.catalog-tabs {
    .nav-item {
        //  position: relative;
        //  bottom: -1px;
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

.sup-badge {
    position: absolute;
    top: -7px;
    right: -17px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 17px;
    height: 17px;
    border-radius: 50%;
    font-size: 10px;
    font-weight: bold;
    box-shadow: 1px 1px 7px 0px #00000045;
}


</style>
