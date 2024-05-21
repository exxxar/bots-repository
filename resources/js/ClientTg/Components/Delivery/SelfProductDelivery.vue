<script setup>
import ProductItemSimple from "@/ClientTg/Components/Shop/Products/ProductItemSimple.vue";
import Pagination from "@/ClientTg/Components/Pagination.vue";
import CategoryList from "@/ClientTg/Components/Shop/Categories/CategoryInlineList.vue";

import ReturnToBot from "@/ClientTg/Components/Shop/Helpers/ReturnToBot.vue";
</script>
<template>


    <div class="card card-style">

        <div class="content" v-if="products.length>0">
            <div v-if="paginate">
                <div class="d-flex justify-content-between">
                    <a href="javascript:void(0)"
                       style="border-radius: 10px !important;"
                       @click="isCollapsed = !isCollapsed"
                       class="btn btn-border btn-m bg-red2-dark btn-full mb-3  text-uppercase font-900 color-blue2-dark mr-2">
                        <i class="fa-solid fa-magnifying-glass" v-if="isCollapsed"></i>
                        <i class="fa-solid fa-chevron-up" v-else></i>
                        <span class="font-14" v-if="isCollapsed"></span>
                        <span class="font-14" v-else></span>
                    </a>
                    <div class="d-flex">
                        <a href="javascript:void(0)"
                           style="border-radius: 10px !important;"
                           @click="selectProductTypeDisplay(0)"
                           v-bind:class="{'bg-blue2-dark':product_type_display==0}"
                           class="btn btn-border btn-m btn-full mb-3  text-uppercase font-900 color-blue2-dark mr-2">
                            <i class="fa-solid fa-list"></i>
                        </a>
                        <a href="javascript:void(0)"
                           style="border-radius: 10px !important;"
                           @click="selectProductTypeDisplay(1)"
                           v-bind:class="{'bg-blue2-dark':product_type_display==1}"
                           class="btn btn-border btn-m btn-full mb-3  text-uppercase font-900  color-blue2-dark ">
                            <i class="fa-regular fa-address-card"></i>
                        </a>
                    </div>

                </div>


            </div>

            <div v-if="!isCollapsed">

                <div class="input-style input-style-2 has-icon input-required">
                    <i class="input-icon fa-solid fa-magnifying-glass" @click="loadProducts(0)"></i>
                    <input class="form-control" v-model="search" type="search"
                           placeholder="Название товара">
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

                <button
                    @click="loadProducts(0)"
                    class="btn btn-full btn-sm rounded-s bg-highlight font-800 text-uppercase w-100 mb-2">
                    <i class="fa-solid fa-file-invoice mr-2"></i><span class="color-white">Найти товар</span>
                </button>


            </div>

            <div class="row">
                <div class="col-12">
                    <p class="mb-0 d-flex justify-content-between">Категории товара <a
                        @click="resetCategories()"
                        v-if="categories.length>0"
                        href="javascript:void(0)">Сбросить</a></p>


                    <p class="mb-2 text-center"><small>Всего товаров найдено ({{ paginate.meta.total }})</small></p>

                    <CategoryList
                        :size="100"
                        :active="activeCategories"
                        :selected="categories"
                        v-on:select="selectCategory"/>
                </div>
            </div>

            <ProductItemSimple
                :display-type="product_type_display"
                :item="product"
                v-for="(product, index) in filteredProducts"/>

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
        id="basket"
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
                <div class="col-6 text-right">
                    <h4 v-if="!deliveryForm.use_cashback">{{ cartTotalPrice }}<sup>.00</sup>₽</h4>
                    <h4 v-if="deliveryForm.use_cashback">{{ cartTotalPrice - cashbackLimit }}<sup>.00</sup>₽</h4>

                </div>
                <div class="col-6 text-left"
                     v-if="getSelf.cashBack&&settings.min_price_for_cashback<cartTotalPrice"><h4>Использовать CashBack</h4></div>

                <div class="col-6 text-right" v-if="getSelf.cashBack&&settings.min_price_for_cashback<cartTotalPrice">
                    <div class="custom-control ios-switch ios-switch-icon my-3" v-if="!deliveryForm.need_pickup">
                        <input type="checkbox"
                               v-model="deliveryForm.use_cashback"
                               class="ios-input" id="toggle-use_cashback">
                        <label class="custom-control-label"
                               style="padding-left:30px;"
                               v-if="deliveryForm.use_cashback"
                               for="toggle-use_cashback"></label>
                        <label class="custom-control-label "
                               style="padding-left:30px;"
                               v-if="!deliveryForm.use_cashback"
                               for="toggle-use_cashback"></label>
                        <i class="fa-solid fa-hand-holding-heart font-11 color-white" style="left:8px;"></i>
                        <i class="fa-solid fa-hand-holding-heart font-11 color-white" style="margin-left: 24px;"></i>

                    </div>
                </div>
                <div class="col-6 text-left" v-if="deliveryForm.use_cashback"><h4>Доступно для списания</h4></div>
                <div class="col-6 text-right" v-if="deliveryForm.use_cashback"><h4>{{ cashbackLimit }}₽</h4></div>

            </div>

            <button
                @click="clearCart"
                class="btn btn-full btn-sm rounded-l bg-red1-dark font-800 text-uppercase w-100">
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
            <p class="mb-0 text-left">Как хотите получить заказ?</p>
            <div class="custom-control ios-switch ios-switch-icon my-3">
                <input type="checkbox"
                       v-model="deliveryForm.need_pickup"
                       class="ios-input" id="toggle-need-pickup">
                <label class="custom-control-label pl-5" for="toggle-need-pickup" v-if="!deliveryForm.need_pickup">Доставка</label>
                <label class="custom-control-label pl-5" for="toggle-need-pickup" v-if="deliveryForm.need_pickup">Самовывоз</label>
                <i class="fa-solid fa-person-walking-luggage font-11 color-white" style="left:8px;"></i>
                <i class="fa-solid fa-truck font-11 color-white" style="margin-left: 24px;"></i>
            </div>

            <div
                v-if="!deliveryForm.need_pickup"
                class="input-style input-style-2 has-icon">
                <i class="input-icon fa-solid fa-city"></i>

                <input class="form-control"
                       type="text"
                       v-model="deliveryForm.city"
                       placeholder="Ваш город"
                       required>
            </div>

            <div
                v-if="!deliveryForm.need_pickup"
                class="input-style input-style-2 has-icon">
                <i class="input-icon fa-solid fa-road"></i>

                <input class="form-control"
                       type="text"
                       v-model="deliveryForm.street"
                       placeholder="Улица"
                       required>
            </div>

            <div
                v-if="!deliveryForm.need_pickup"
                class="input-style input-style-2 has-icon">
                <i class="input-icon fa-solid fa-house-user"></i>
                <input class="form-control"
                       type="text"
                       v-model="deliveryForm.building"
                       placeholder="Номер дома"
                       required>
            </div>

            <div
                v-if="!deliveryForm.need_pickup"
                class="input-style input-style-2 has-icon">
                <i class="input-icon fa-solid fa-door-open"></i>

                <input class="form-control"
                       type="text"
                       v-model="deliveryForm.flat_number"
                       placeholder="Номер квартиры">
            </div>

            <div
                v-if="!deliveryForm.need_pickup"
                class="input-style input-style-2 has-icon">
                <i class="input-icon fa-solid fa-house-flag"></i>

                <input class="form-control"
                       type="text"
                       v-model="deliveryForm.entrance_number"
                       placeholder="Номер подъезда">
            </div>

            <div
                v-if="!deliveryForm.need_pickup"
                class="input-style input-style-2 has-icon">
                <i class="input-icon fa-solid fa-layer-group"></i>
                <input class="form-control"
                       type="text"
                       v-model="deliveryForm.floor_number"
                       placeholder="Номер этажа">
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

            <p class="mb-0 text-left">К какому времени заказ должен быть готов?</p>
            <div class="custom-control ios-switch ios-switch-icon my-3">
                <input type="checkbox"
                       v-model="deliveryForm.when_ready"
                       class="ios-input" id="toggle-when-ready">
                <label class="custom-control-label pl-5" for="toggle-when-ready" v-if="!deliveryForm.when_ready">Ко
                    времени</label>
                <label class="custom-control-label pl-5" for="toggle-when-ready" v-if="deliveryForm.when_ready">По
                    готовности</label>
                <i class="fa-solid fa-clock font-11 color-white" style="left:8px;"></i>
                <i class="fa-solid fa-mug-hot font-11 color-white" style="margin-left: 24px;"></i>
            </div>


            <div
                v-if="!deliveryForm.when_ready"
                class="input-style input-style-2 has-icon">
                <i class="input-icon fa-regular fa-clock"></i>
                <input class="form-control"
                       type="datetime-local"
                       v-model="deliveryForm.time"
                       placeholder="Время доставки">
            </div>

            <p class="mb-0 text-left " v-if="!deliveryForm.need_pickup">Ограничения по здоровью</p>
            <div class="custom-control ios-switch ios-switch-icon my-3" v-if="!deliveryForm.need_pickup">
                <input type="checkbox"
                       v-model="deliveryForm.has_disability"
                       class="ios-input" id="toggle-has-disability">
                <label class="custom-control-label pl-5"
                       v-if="deliveryForm.has_disability"
                       for="toggle-has-disability">Есть</label>
                <label class="custom-control-label pl-5"
                       v-if="!deliveryForm.has_disability"
                       for="toggle-has-disability">Нет</label>
                <i class="fa-solid fa-hand-holding-heart font-11 color-white" style="left:8px;"></i>
                <i class="fa-solid fa-hand-holding-heart font-11 color-white" style="margin-left: 24px;"></i>

            </div>

            <div class="list-group list-custom-small" v-if="deliveryForm.has_disability">
                <a class="border-0" href="javascript:void(0)">
                    <i class="fa-solid rounded-sm font-14 fa-head-side-mask shadow-s bg-red1-dark"></i>
                    <span>Болею</span>
                    <div class="custom-control scale-switch ios-switch">
                        <input type="checkbox"
                               value="болею"
                               v-model="deliveryForm.disabilities" class="ios-input" id="switch-1">
                        <label class="custom-control-label" for="switch-1"></label>
                    </div>
                    <i class="fa fa-angle-right"></i>
                </a>

                <a class="border-0" href="javascript:void(0)">
                    <i class="fa-solid rounded-sm font-14 fa-eye shadow-s bg-red1-dark"></i>
                    <span>Слабовидящий</span>
                    <div class="custom-control scale-switch ios-switch">
                        <input type="checkbox"
                               value="слабовидящий"
                               v-model="deliveryForm.disabilities" class="ios-input" id="switch-2">
                        <label class="custom-control-label" for="switch-2"></label>
                    </div>
                    <i class="fa fa-angle-right"></i>
                </a>

                <a class="border-0" href="javascript:void(0)">
                    <i class="fa-solid rounded-sm font-14 fa-wheelchair shadow-s bg-red1-dark"></i>
                    <span>Ограничения мобильности</span>
                    <div class="custom-control scale-switch ios-switch">
                        <input type="checkbox"
                               value="ограничения мобильности"
                               v-model="deliveryForm.disabilities" class="ios-input" id="switch-3">
                        <label class="custom-control-label" for="switch-3"></label>
                    </div>
                    <i class="fa fa-angle-right"></i>
                </a>

                <a class="border-0" href="javascript:void(0)">
                    <i class="fa-solid rounded-sm font-14 fa-ear-deaf shadow-s bg-red1-dark"></i>
                    <span>Плохо слышит \ говорит</span>
                    <div class="custom-control scale-switch ios-switch">
                        <input type="checkbox"
                               value="проблемы со слухом \ голосом"
                               v-model="deliveryForm.disabilities" class="ios-input" id="switch-4">
                        <label class="custom-control-label" for="switch-4"></label>
                    </div>
                    <i class="fa fa-angle-right"></i>
                </a>
            </div>

            <p class="mb-0 text-left" v-if="settings.can_use_cash">Способы оплаты</p>
            <div class="custom-control ios-switch ios-switch-icon my-3" v-if="settings.can_use_cash">
                <input type="checkbox"
                       v-model="deliveryForm.cash"
                       class="ios-input" id="toggle-payment-cash">
                <label class="custom-control-label pl-5" for="toggle-payment-cash"
                       v-if="!deliveryForm.cash">Переводом</label>
                <label class="custom-control-label pl-5" for="toggle-payment-cash"
                       v-if="deliveryForm.cash">Наличные</label>

                <i class="fa-solid fa-money-bill-1-wave font-11 color-white" style="left:8px;"></i>
                <i class="fa-solid fa-credit-card  font-11 color-white" style="margin-left: 24px;"></i>
            </div>

            <div v-if="deliveryForm.cash&&settings.can_use_cash">
                <h6>Мы можем подготовить для вас сдачу с:</h6>
                <div class="row row-cols-2 mb-0">
                    <div class="col" v-for="money in moneyVariants">
                        <button class="btn btn-outline-success w-100 mb-2 rounded-xl"
                                type="button"
                                @click="deliveryForm.money=money"
                                v-bind:class="{'btn-success text-white':deliveryForm.money===money}"
                        >{{ money }}₽
                        </button>
                    </div>

                </div>
                <p class="mb-2"><em>или введите другую сумму...</em></p>

                <div
                    class="input-style input-style-2 has-icon">
                    <i class="input-icon fa-solid fa-door-open"></i>

                    <input class="form-control"
                           type="number"
                           min="0"
                           v-model="deliveryForm.money"
                           placeholder="С какой суммы нужна сдача">
                </div>
            </div>
            <p class="mb-0 text-left">Число персон</p>
            <div class="row text-center mr-2 ml-2 mb-3">

                <div class="col-4 mb-n2">
                    <button
                        @click="decPersons"
                        type="button" class="btn p-2 w-100 bg-green1-dark  rounded-l m-0"><i
                        class="fa-solid fa-minus font-22"></i></button>
                </div>

                <div class="col-4 mb-n2 d-flex justify-content-center align-items-center">
                    <strong class="font-22">{{ deliveryForm.persons }}</strong>
                </div>

                <div class="col-4 mb-n2">
                    <button type="button"
                            @click="incPersons"
                            class="btn p-2 w-100 bg-green2-dark rounded-l m-0"><i
                        class="fa-solid fa-plus font-22"></i></button>
                </div>

            </div>

            <p v-if="settings.delivery_price_text" v-html="settings.delivery_price_text"></p>
            <p v-if="settings.min_price">Минимальная цена заказа {{ settings.min_price || 0 }} руб</p>
            <button
                type="submit"
                :disabled="spent_time_counter>0||(!deliveryForm.use_cashback?settings.min_price>cartTotalPrice:settings.min_price>cartTotalPrice-cashbackLimit)"
                class="btn btn-full btn-sm rounded-l bg-highlight font-800 text-uppercase w-100 mb-2">

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
            settings: {
                can_use_cash: true,
                delivery_price_text: null,
                min_price: 0,
                min_price_for_cashback: 0,
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
                city:null,
                street:null,
                building:null,
                flat_number:null,
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
    watch: {
        'deliveryForm.cash': {
            handler: function (newValue) {
                if (!this.deliveryForm.cash)
                    this.deliveryForm.money = null
            },
            deep: true
        }
    },
    computed: {
        ...mapGetters(['getProducts', 'getProductsPaginateObject', 'cartProducts', 'cartTotalCount', 'cartTotalPrice', 'getSelf']),
        getCurrentBot() {
            return window.currentBot
        },
        cashbackLimit() {
            let maxUserCashback = this.getSelf.cashBack.amount || 0
            let summaryPrice = this.cartTotalPrice || 0
            let botCashbackPercent = this.getCurrentBot.max_cashback_use_percent || 0

            let cashBackAmount = (summaryPrice * (botCashbackPercent / 100));

            return Math.min(cashBackAmount, maxUserCashback)
        },
        filteredProducts() {

            if (!this.search)
                return this.products

            return this.products.filter(product => product.title.toLowerCase().trim().indexOf(this.search.toLowerCase().trim()) >= 0)
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


        if (localStorage.getItem("cashman_self_product_delivery_counter") != null) {
            this.is_requested = true;
            this.startTimer(localStorage.getItem("cashman_self_product_delivery_counter"))
        }

        if (localStorage.getItem("cashman_self_product_type_display") != null) {
            this.product_type_display = parseInt(localStorage.getItem("cashman_self_product_type_display") || 0)
        }


        this.deliveryForm.name = localStorage.getItem("cashman_self_product_delivery_form_name") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_name") : null

        this.deliveryForm.phone = localStorage.getItem("cashman_self_product_delivery_form_phone") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_phone") : null

        this.deliveryForm.address = localStorage.getItem("cashman_self_product_delivery_form_address") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_address") : null

        this.deliveryForm.city = localStorage.getItem("cashman_self_product_delivery_form_city") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_city") : null

        this.deliveryForm.street = localStorage.getItem("cashman_self_product_delivery_form_street") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_street") : null

        this.deliveryForm.building = localStorage.getItem("cashman_self_product_delivery_form_building") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_building") : null

        this.deliveryForm.flat_number = localStorage.getItem("cashman_self_product_delivery_form_flat_number") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_flat_number") : null

        this.deliveryForm.entrance_number = localStorage.getItem("cashman_self_product_delivery_form_entrance_number") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_entrance_number") : null

        this.deliveryForm.disabilities = localStorage.getItem("cashman_self_product_delivery_form_entrance_disabilities") != null ?
            JSON.parse(localStorage.getItem("cashman_self_product_delivery_form_entrance_disabilities")) : []

        if (this.deliveryForm.disabilities.length > 0)
            this.deliveryForm.has_disability = true

        this.clearCart();

        this.loadProducts()
        this.loadShopModuleData()

        if (this.cartProducts.length > 0)
            this.loadActualProducts()


        this.$nextTick(() => {
            console.log("self=>", this.getSelf)
        })
    },
    methods: {
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
            this.$store.dispatch("clearCart").then(() => {
                this.$botNotification.success("Корзина", "Корзина успешно очищена")
            })

        },
        resetCategories() {
            this.categories = []
            this.loadProducts(0)
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
        loadShopModuleData() {
            return this.$store.dispatch("loadShopModuleData").then((resp) => {
                this.$nextTick(() => {
                    Object.keys(resp).forEach(item => {
                        this.settings[item] = resp[item]
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

    }
}
</script>
<style lang="scss">
.scrolled-list {
    width: 100%;
    overflow-x: auto;
}

.card-style {
    margin: 0px 5px 15px 5px !important;
}

.content {
    margin: 10px 10px 10px 10px !important;
}

.go-to-cart {
    position: fixed;
    bottom: 0px;
    width: 100%;
    z-index: 100;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    box-sizing: border-box;
}
</style>
