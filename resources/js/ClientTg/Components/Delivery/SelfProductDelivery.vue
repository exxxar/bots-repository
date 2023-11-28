<script setup>
import ProductItemSimple from "@/ClientTg/Components/Shop/Products/ProductItemSimple.vue";
import Pagination from "@/ClientTg/Components/Pagination.vue";
import CategoryList from "@/ClientTg/Components/Shop/Categories/CategoryInlineList.vue";

import ReturnToBot from "@/ClientTg/Components/Shop/Helpers/ReturnToBot.vue";
</script>
<template>

    <div class="card card-style">
        <div class="content">


            <div v-if="!isCollapsed">

                <div class="input-style input-style-2 has-icon input-required">
                    <i class="input-icon fa-solid fa-magnifying-glass" @click="loadProducts(0)"></i>
                    <input class="form-control" v-model="search" type="search" placeholder="Найди товар на странице">
                </div>
                <p class="mb-0">Цена товара</p>
                <div class="row mb-0">
                    <div class="col-6">
                        <div class="input-style input-style-2 input-required">
                            <input
                                v-model="min_price"
                                class="form-control"
                                type="number"
                                min="0"
                                placeholder="От, руб">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-style input-style-2 input-required">
                            <input class="form-control"
                                   min="0"
                                   v-model="max_price"
                                   type="number" placeholder="До, руб">
                        </div>
                    </div>
                </div>

                <!--                <div class="d-flex scrolled-list" v-if="categories.length>0">
                                       <span
                                             class="badge badge-info mr-2 mb-2 mt-0"
                                             v-for="(item, index) in categories">{{item.title || 'не указано'}} <i class="ml-1 fa-solid fa-xmark text-white" @click="removeCategory(index)"></i></span>

                                </div>-->


                <button
                    @click="loadProducts(0)"
                    class="btn btn-full btn-sm rounded-s bg-highlight font-800 text-uppercase w-100 mb-2">
                    <i class="fa-solid fa-file-invoice mr-2"></i><span class="color-white">Найти товар</span>
                </button>


            </div>


            <a href="javascript:void(0)" @click="isCollapsed = !isCollapsed"
               class="btn btn-m btn-full rounded-sm font-900  text-uppercase mb-0">
                <i class="fa-solid fa-chevron-down mr-2" v-if="isCollapsed"></i>
                <i class="fa-solid fa-chevron-up  mr-2" v-else></i>
                <span class="font-14" v-if="isCollapsed">Найти товар</span>
                <span class="font-14" v-else>Скрыть фильтры</span>
            </a>


        </div>
    </div>

    <div class="card card-style">
        <div class="content">
            <CategoryList
                :size="100"
                :active="activeCategories"
                :selected="categories"
                v-on:select="selectCategory"/>
        </div>
    </div>

    <div class="card card-style">
        <div class="content" v-if="paginate">
            <p class="mb-0 text-center"><small>Всего товаров найдено ({{ paginate.meta.total }})</small></p>

        </div>
        <div class="content" v-if="products.length>0">
            <ProductItemSimple :item="product" v-for="(product, index) in filteredProducts"/>

            <Pagination
                :simple="true"
                v-on:pagination_page="nextProducts"
                v-if="paginate"
                :pagination="paginate"/>
        </div>
        <div class="content" v-else>
            <p>К сожалению, никаких товаров еще нет в магазине:(</p>
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

            <div class="custom-control ios-switch ios-switch-icon my-3">
                <input type="checkbox"
                       v-model="deliveryForm.need_pickup"
                       class="ios-input" id="toggle-need-pickup">
                <label class="custom-control-label pl-5" for="toggle-need-pickup" v-if="!deliveryForm.need_pickup">Нужна
                    доставка</label>
                <label class="custom-control-label pl-5" for="toggle-need-pickup" v-if="deliveryForm.need_pickup">Самовывоз</label>
                <i class="fa-solid fa-person-walking-luggage font-11 color-white" style="left:8px;"></i>
                <i class="fa-solid fa-truck font-11 color-white" style="margin-left: 24px;"></i>
            </div>

            <div
                v-if="!deliveryForm.need_pickup"
                class="input-style input-style-2 has-icon">
                <i class="input-icon fa-solid fa-map-location-dot"></i>

                <input class="form-control"
                       type="text"
                       v-model="deliveryForm.address"
                       placeholder="г.Краснодар, ул. Ленинина, 106"
                       required>
            </div>

            <div
                v-if="!deliveryForm.need_pickup"
                class="input-style input-style-2 has-icon">
                <i class="input-icon fa-solid fa-door-open"></i>

                <input class="form-control"
                       type="text"
                       v-model="deliveryForm.entrance_number"
                       placeholder="Номер подъезда">
            </div>

            <div
                v-if="!deliveryForm.need_pickup"
                class="input-style input-style-2 has-icon">
                <span class="input-style-1-active input-style-1-inactive">Информация для доставщика</span>
                <i class="input-icon fa-solid fa-envelope-open-text"></i>
                <textarea class="form-control"
                          style="height:200px;line-height:150%;padding:35px;"
                          v-model="deliveryForm.info"
                          type="text" placeholder=""></textarea>
            </div>

            <div
                v-if="!deliveryForm.need_pickup"
               >

                <div class="custom-control ios-switch ios-switch-icon my-3">
                    <input type="checkbox"
                           v-model="deliveryForm.cash"
                           class="ios-input" id="toggle-payment-cash">
                    <label class="custom-control-label pl-5" for="toggle-payment-cash" v-if="!deliveryForm.cash">Карта</label>
                    <label class="custom-control-label pl-5" for="toggle-payment-cash" v-if="deliveryForm.cash">Наличные</label>

                    <i class="fa-solid fa-money-bill-1-wave font-11 color-white" tyle="left:8px;"></i>
                    <i class="fa-solid fa-credit-card  font-11 color-white" style="margin-left: 24px;"></i>
                </div>

                <div v-if="deliveryForm.cash">
                    <h6>Мы можем подготовить для вас сдачу с:</h6>
                    <div  class="d-flex justify-content-around flex-wrap py-2 mb-2">
                        <button class="btn btn-outline-success"
                                type="button"
                                @click="deliveryForm.money=money"
                                v-bind:class="{'btn-success text-white':deliveryForm.money===money}"
                                v-for="money in moneyVariants">{{money}}₽</button>
                    </div>
                </div>

            </div>

            <button
                type="submit"
                :disabled="spent_time_counter>0"
                class="btn btn-full btn-sm rounded-s bg-highlight font-800 text-uppercase w-100 mb-2">

                <i v-if="spent_time_counter<=0" class="fa-solid fa-file-invoice mr-2"></i>
                <i v-else class="fa-solid fa-hourglass  mr-2"></i>

                <span
                    v-if="spent_time_counter<=0"
                    class="color-white">Оформить</span>
                <span
                    v-else
                    class="color-white">Осталось ждать {{ spent_time_counter }} сек.</span>
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
            moneyVariants:[
              500, 1000, 2000, 5000
            ],
            deliveryForm: {
                name: null,
                phone: null,
                address: null,
                entrance_number: null,
                info: null,
                need_pickup: false,
                money:null,
                cash:true,
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
        activeCategories() {
            let tmp = [];
            this.filteredProducts.forEach(item => {
                tmp.push(item.categories.map(o => o['id']))
            })
            return [...new Set(tmp.map(item => item[0])), ...new Set(tmp.map(item => item[1]))];
        }
    },
    mounted() {

        if (localStorage.getItem("cashman_self_product_delivery_counter") != null) {
            this.is_requested = true;
            this.startTimer(localStorage.getItem("cashman_self_product_delivery_counter"))
        }


        this.clearCart();

        this.loadProducts()

        if (this.cartProducts.length > 0)
            this.loadActualProducts()
    },
    methods: {
        startTimer(time) {
            this.spent_time_counter = time != null ? Math.min(time, 30) : 30;

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
            this.$store.dispatch("clearCart").then(() => {
                this.$botNotification.success("Корзина", "Корзина успешно очищена")
            })

        },
        selectCategory(item) {
            let index = this.categories.findIndex(category => category.id === item.id)
            if (index !== -1) {
                this.categories.splice(index, 1)
            } else
                this.categories.push(item)

            this.loadProducts(0)
        },
        nextProducts(index) {
            this.loadProducts(index)
        },
        loadProducts(page = 0) {
            return this.$store.dispatch("loadProducts", {
                dataObject: {
                    search: this.search,
                    categories: this.categories.length > 0 ? this.categories.map(o => o['id']) : null,
                    min_price: this.min_price || null,
                    max_price: this.max_price || null
                },
                page: page
            }).then(() => {
                this.products = this.getProducts
                this.paginate = this.getProductsPaginateObject
                baseJS.handler()
            })
        },
        startCheckout() {

            if (this.is_requested) {
                this.$botNotification.warning("Упс!", `Сделать повторный заказ можно через <strong>${this.spent_time_counter} сек.</strong>`)
                return;
            }

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

                    this.clearCart();

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

    }
}
</script>
<style>
.scrolled-list {
    width: 100%;
    overflow-x: auto;
}
</style>
