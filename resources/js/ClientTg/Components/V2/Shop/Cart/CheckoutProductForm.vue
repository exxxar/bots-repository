<script setup>
import Summary from "@/ClientTg/Components/V2/Shop/Cart/Summary.vue";
</script>
<template>

    <form
        id="basket"
        class="container py-3"
        v-on:submit.prevent="startCheckout">
        <h6 class="opacity-75">Способы получения заказа</h6>

        <div class="list-group my-3">
            <a href="javascript:void(0)"
               v-bind:class="{'active':!modelValue.need_pickup}"
               @click="modelValue.need_pickup = false"
               class="list-group-item list-group-item-action p-3" aria-current="true">
                <i class="fa-solid fa-truck mr-2"></i>Доставка
            </a>
            <a href="javascript:void(0)"
               @click="modelValue.need_pickup = true"
               v-bind:class="{'active':modelValue.need_pickup}"
               class="list-group-item list-group-item-action p-3">
                <i class="fa-brands fa-shopify mr-2"></i>Самовывоз</a>
            <a href="javascript:void(0)"
               v-if="modelValue.need_pickup"
               class="list-group-item list-group-item-action p-3 d-flex justify-content-between">
                <label class="form-check-label" for="in-restaurant">
                    <i class="fa-solid fa-utensils mr-2"></i>
                    В заведении
                </label>

                <input class="form-check-input"
                       name="pickup-format"
                       v-model="modelValue.pick_up_type"
                       type="radio" value="0" id="in-restaurant">
            </a>
            <a href="javascript:void(0)"
               v-if="modelValue.need_pickup"
               class="list-group-item list-group-item-action p-3 d-flex justify-content-between">
                <label class="form-check-label" for="pick-up-in-package">
                    <i class="fa-solid fa-person-walking-luggage mr-2"></i>
                    Забрать с собой
                </label>

                <input class="form-check-input"
                       type="radio" value="1"
                       v-model="modelValue.pick_up_type"
                       name="pickup-format"
                       id="pick-up-in-package">
            </a>
        </div>

        <template v-if="!settings.need_pay_after_call">
            <h6 class="opacity-75">Способы оплаты</h6>

            <div class="list-group my-3">
                <a href="javascript:void(0)"
                   @click="modelValue.payment_type = 0"
                   v-if="settings.can_use_card&&settings.payment_token!=null"
                   v-bind:class="{'active':modelValue.payment_type === 0}"
                   class="list-group-item list-group-item-action p-3" aria-current="true">
                    <i class="fa-solid fa-earth-americas mr-2"></i> Онлайн через бота
                </a>

                <a href="javascript:void(0)"
                   v-bind:class="{'active':modelValue.payment_type === 4}"
                   @click="modelValue.payment_type = 4"
                   v-if="settings.can_use_sbp"
                   class="list-group-item list-group-item-action p-3 d-flex"><i
                    class="fa-solid fa-file-invoice mr-2"></i>
                    <span class="d-inline-flex justify-content-between w-100">
                Оплата по СБП
                <img
                    style="width:45px;"
                    v-lazy="'/images/СБП_логотип.svg'" alt="">
                </span>
                </a>

                <a href="javascript:void(0)"
                   v-bind:class="{'active':modelValue.payment_type === 1}"
                   v-if="modelValue.pick_up_type==0&&settings.can_use_cash"
                   @click="modelValue.payment_type = 1"
                   class="list-group-item list-group-item-action p-3"><i
                    class="fa-regular fa-credit-card mr-2"></i>Картой в заведении</a>
                <a href="javascript:void(0)"
                   v-bind:class="{'active':modelValue.payment_type === 2}"
                   @click="modelValue.payment_type = 2"
                   v-if="settings.can_use_cash"
                   class="list-group-item list-group-item-action p-3"><i
                    class="fa-solid fa-file-invoice mr-2"></i>Переводом</a>
                <a href="javascript:void(0)"
                   v-bind:class="{'active':modelValue.payment_type === 3}"
                   @click="modelValue.payment_type = 3"
                   v-if="settings.can_use_cash"
                   class="list-group-item list-group-item-action p-3"><i
                    class="fa-regular fa-money-bill-1 mr-2"></i> Наличными</a>
            </div>
        </template>

        <template v-if="(settings.need_bonuses_section||false)&&cashbackLimit>0">
            <h6 class="opacity-75">Бонусы <small>(нажми для использования)</small></h6>

            <div class="card my-3"
                 v-bind:class="{'text-bg-primary':modelValue.use_cashback}"
                 @click="modelValue.use_cashback=!modelValue.use_cashback">
                <div
                    class="card-body">
                    <p class="d-flex justify-content-between mb-0">
                        <span> Списать баллы</span>
                        <strong>{{ cashbackLimit }}₽</strong>
                    </p>
                </div>
            </div>
        </template>

        <h6 class="opacity-75 mb-3">Информация</h6>

        <div class="form-floating mb-2">
            <input type="text"
                   v-model="modelValue.name"
                   class="form-control" id="modelValue-name"
                   placeholder="Иванов Иван Иванович" required>
            <label for="modelValue-name">Ф.И.О. <span class="fw-bold text-danger">*</span></label>
        </div>

        <div class="form-floating mb-2">
            <input type="text"
                   v-mask="'+7(###)###-##-##'"
                   v-model="modelValue.phone"
                   class="form-control" id="modelValue-phone"
                   placeholder="+7(000)000-00-00" required>
            <label for="modelValue-phone">Номер телефона <span class="fw-bold text-danger">*</span></label>
        </div>

        <template v-if="!modelValue.need_pickup">
            <div

                class="form-floating mb-2">
                <input type="text"
                       v-model="modelValue.city"
                       class="form-control" id="modelValue-city"
                       placeholder="Ваш город" required>
                <label for="modelValue-city">Ваш город <span class="fw-bold text-danger">*</span></label>
            </div>

            <div
                class="form-floating mb-2">
                <input type="text"
                       v-model="modelValue.street"
                       class="form-control" id="modelValue-street"
                       placeholder="Улица" required>
                <label for="modelValue-street">Улица <span class="fw-bold text-danger">*</span></label>
            </div>


            <div
                class="form-floating mb-2">
                <input type="text"
                       v-model="modelValue.building"
                       class="form-control" id="modelValue-building"
                       placeholder="Номер дома" required>
                <label for="modelValue-building">Номер дома <span class="fw-bold text-danger">*</span></label>
            </div>

            <div
                class="form-floating mb-2">
                <input type="text"
                       v-model="modelValue.flat_number"
                       class="form-control" id="modelValue-flat-number"
                       placeholder="Номер квартиры">
                <label for="modelValue-flat-number">Номер квартиры </label>
            </div>

            <div
                class="form-floating mb-2">
                <input type="text"
                       v-model="modelValue.entrance_number"
                       class="form-control" id="modelValue-entrance-number"
                       placeholder="Номер подъезда">
                <label for="modelValue-entrance-number">Номер подъезда</label>
            </div>

            <div
                class="form-floating mb-2">
                <input type="text"
                       v-model="modelValue.floor_number"
                       class="form-control" id="modelValue-floor-number"
                       placeholder="Номер этажа">
                <label for="modelValue-floor-number">Номер этажа</label>
            </div>

        </template>
        <template v-else>
            <template v-if="settings.need_table_list">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input"
                           type="checkbox"
                           v-model="need_select_table_by_number"
                           role="switch"
                           id="select-table-number-from-list">
                    <label class="form-check-label" for="select-table-number-from-list">Выбрать номер столика из
                        списка</label>
                </div>
                <div class="row row-cols-5" v-if="need_select_table_by_number">
                    <div class="col" v-for="num in parseInt(settings.max_tables)">
                        <a href="javascript:void(0)"
                           @click="modelValue.table_number=num"
                           v-bind:class="{'btn-primary':modelValue.table_number==num,'btn-outline-primary':modelValue.table_number!=num}"
                           class="btn w-100 mb-2">
                            {{ num }}
                        </a>
                    </div>
                </div>
            </template>
            <div
                class="form-floating mb-2">
                <input type="number"
                       min="1"
                       max="200"
                       v-model="modelValue.table_number"
                       class="form-control" id="modelValue-table-number"
                       placeholder="Номер столика">
                <label for="modelValue-table-number">Номер столика</label>
            </div>
        </template>

        <div class="form-floating">
            <textarea class="form-control"
                      v-model="modelValue.info"
                      style="height:200px;line-height:150%;"
                      placeholder="Информация" id="modelValue-info"></textarea>
            <label v-if="!modelValue.need_pickup" for="modelValue-info">Информация для доставщика</label>
            <label v-else for="modelValue-info">Информация для сотрудника</label>
        </div>

        <template v-if="bot.company.law_params?.offer_link">
          <div class="alert alert-light my-2">
              <div class="form-check form-switch">
                  <p class="mb-2">
                      Нажимая кнопку, вы соглашаетесь с условиями
                      <a :href="bot.company.law_params.offer_link" target="_blank">договора оферты</a>.
                  </p>
                  <input class="form-check-input"
                         v-model="offer_agreement"
                         type="checkbox" role="switch" id="offerSwitch" checked>
                  <label class="form-check-label" for="offerSwitch">Я соглашаюсь</label>
              </div>
          </div>
        </template>

        <template v-if="!modelValue.need_pickup">
            <h6 class="opacity-75 mt-3">К какому времени приготовить?</h6>

            <div class="list-group my-3">
                <a href="javascript:void(0)"
                   v-bind:class="{'active':modelValue.when_ready}"
                   @click="modelValue.when_ready = true"
                   class="list-group-item list-group-item-action p-3" aria-current="true">
                    <i class="fa-solid fa-stopwatch-20 mr-2"></i>По готовности
                </a>
                <a href="javascript:void(0)"
                   @click="modelValue.when_ready = false"
                   v-bind:class="{'active':!modelValue.when_ready}"
                   class="list-group-item list-group-item-action p-3">
                    <i class="fa-regular fa-clock mr-2"></i>К указанному времени</a>

            </div>

            <div
                v-if="!modelValue.when_ready"
                class="form-floating">
                <input type="datetime-local"
                       v-model="modelValue.time"
                       class="form-control" id="modelValue-time" placeholder="Время доставки" required>
                <label for="modelValue-time">Время доставки</label>
            </div>
        </template>

        <template v-if="settings.need_health_restrictions||false">
            <h6 class="opacity-75 mt-3" v-if="!modelValue.need_pickup">Ограничения по здоровью</h6>

            <div class="list-group my-3" v-if="!modelValue.need_pickup">

                <a href="javascript:void(0)"
                   @click="modelValue.has_disability = false"
                   v-bind:class="{'active':!modelValue.has_disability}"
                   class="list-group-item list-group-item-action p-3">
                    <i class="fa-regular fa-heart mr-2"></i>Нет ограничений по здоровью</a>
                <a href="javascript:void(0)"
                   v-bind:class="{'active':modelValue.has_disability}"
                   @click="modelValue.has_disability = true"
                   class="list-group-item list-group-item-action p-3" aria-current="true">
                    <i class="fa-solid fa-house-medical-flag mr-2"></i>Есть ограничения по здоровью
                </a>
            </div>

            <div class="list-group my-3" v-if="modelValue.has_disability&&!modelValue.need_pickup">
                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-1"> <i class="fa-solid fa-head-side-mask mr-2"></i> Болею</label>
                    <input type="checkbox"
                           value="болею"
                           class="form-check-input"
                           v-model="modelValue.disabilities" id="switch-1">
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-5"> <i class="fa-solid fa-ear-deaf mr-2"></i> Плохо слышит \ говорит</label>
                    <input type="checkbox"
                           value="плохо слышит или говорит"
                           class="form-check-input"
                           v-model="modelValue.disabilities" id="switch-5">
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-3"> <i class="fa-solid fa-glasses mr-2"></i> Слабовидящий</label>
                    <input type="checkbox"
                           value="слабовидящий"
                           class="form-check-input"
                           v-model="modelValue.disabilities" id="switch-3">
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-4"> <i class="fa-solid fa-wheelchair mr-2"></i> Ограничения мобильности</label>
                    <input type="checkbox"
                           class="form-check-input"
                           value="ограничения мобильности"
                           v-model="modelValue.disabilities" id="switch-4">
                </a>

                <a href="javascript:void(0)"
                   class="list-group-item list-group-item-action p-3 d-flex justify-content-between"
                   aria-current="true">
                    <label for="switch-5"> <i class="fa-solid fa-person-dots-from-line mr-2"></i> Пищевая
                        аллергия</label>
                    <input type="checkbox"
                           class="form-check-input"
                           value="пищевая аллергия"
                           v-model="modelValue.disabilities" id="switch-5">
                </a>

            </div>

            <div class="form-floating mb-2" v-if="modelValue.disabilities.indexOf('пищевая аллергия')!==-1">
                <input type="text" class="form-control"
                       v-model="modelValue.allergy"
                       id="floatingInput" placeholder="name@example.com" required>
                <label for="floatingInput">Укажите на что аллергия
                    <span class="fw-bold text-danger">*</span>
                </label>
            </div>
        </template>

        <Summary
            v-on:person-inc="incPersons"
            v-on:person-dec="decPersons"
            v-on:discount="activatePromo"
            v-on:calc-delivery-price="requestDeliveryPrice"
            :data="modelValue"
            :settings="settings">
        </Summary>

        <p
            class="alert alert-danger fw-bold mb-2"
            v-if="error_delivery_price_message">{{ error_delivery_price_message }}</p>

        <button type="button"
                @click="goToProductCart"
                class="btn btn-primary w-100 p-3">
            <i class="fa-solid fa-cart-shopping"></i> Корзина с товаром
        </button>

        <nav
            v-if="offer_agreement"
            class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
            style="border-radius:10px 10px 0px 0px;">

            <template v-if="spent_time_counter<=0">
                <template v-if="settings.need_automatic_delivery_request">
                    <!-- v-if="cartTotalPrice <= settings.free_shipping_starts_from"-->
                    <button
                        v-if="delivery_price_request_step===0"
                        @click="requestDeliveryPrice"
                        class="btn btn-primary text-white p-3 w-100 d-flex align-items-center justify-content-center"
                        :disabled="!canRequestDeliverPrice">
                        <i class="fa-solid fa-map-location-dot mr-2"></i> Рассчитать цену доставки
                        <div
                            v-if="!need_request_delivery_price"
                            class="spinner-border ml-2 spinner-border-sm"
                            role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>
                </template>


                <template v-if="delivery_price_request_step===1">

                    <button
                        v-if="settings.need_pay_after_call || modelValue.payment_type === 3"
                        :disabled="!canSubmitForm"
                        class="btn btn-primary p-3 w-100">
                        <i v-if="spent_time_counter<=0" class="fa-solid fa-file-invoice mr-2"></i>
                        <i v-else class="fa-solid fa-hourglass  mr-2"></i>
                        Оформить

                    </button>

                    <button
                        v-if="modelValue.payment_type===4&&!settings.need_pay_after_call"
                        :disabled="!canSubmitForm"
                        class="btn btn-primary p-3 w-100 d-flex justify-content-center align-items-center">
                        Оплатить через
                        <img
                            style="width:80px; object-fit:cover;margin-left:10px;"
                            v-lazy="'/images/Т-Банк.png'" alt="">
                    </button>

                    <button
                        v-if="modelValue.payment_type===2&&!settings.need_pay_after_call"
                        type="button"
                        @click="nextStep"
                        :disabled="!canSubmitForm"
                        class="btn btn-primary p-3 w-100">
                        <i class="fa-solid fa-receipt mr-2"></i> Оплатить переводом
                    </button>
                </template>

            </template>
            <template v-else>
                <button type="button"
                        class="btn btn-primary p-3 w-100 d-flex align-items-center justify-content-center">
                    Осталось ждать {{ spent_time_counter || 0 }} сек.
                    <div
                        v-if="!canSubmitForm"
                        class="spinner-border ml-2 spinner-border-sm"
                        role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>

                </button>
            </template>
        </nav>
    </form>
</template>
<script>

import {mapGetters} from "vuex";

export default {
    props: ["settings", "modelValue"],
    data() {
        return {
            offer_agreement:true,
            delivery_price_request_step: 0,
            spent_time_counter: 0,
            need_select_table_by_number: false,
            need_request_delivery_price: true,
            error_delivery_price_message: null,
            moneyVariants: [
                500, 1000, 2000, 5000
            ],
        }
    },
    watch: {

        'modelValue': {
            handler: function (newValue) {

                this.$emit("update:modelValue", this.modelValue)
            },
            deep: true
        },
        'modelValue.need_pickup': {
            handler: function (newValue) {

                this.delivery_price_request_step = (this.modelValue.need_pickup === true ? 1 : 0) || (this.settings.need_automatic_delivery_request ? 0 : 1)

            },
            deep: true
        },

        'cartTotalPrice': {
            handler: function (newValue) {
                if (this.settings.free_shipping_starts_from <= this.cartTotalPrice) {
                    this.modelValue.delivery_price = 0
                    this.delivery_price_request_step = 1
                }
            },
            deep: true
        },


    },
    computed: {
        ...mapGetters(['cartProducts', 'cartProducts', 'cartTotalCount', 'cartTotalPrice', 'getSelf']),

        bot() {
            return window.currentBot
        },

        canRequestDeliverPrice() {
            return this.need_request_delivery_price && this.modelValue.city != null && this.modelValue.street != null && this.modelValue.building != null;
        },

        canSubmitForm() {
            let sumIsValid = !this.modelValue.use_cashback ?
                this.cartTotalPrice >= (this.settings.min_price||0) :
                this.cartTotalPrice - (this.cashbackLimit || 0) > (this.settings.min_price||0)

            console.log("this.cartTotalPrice ", this.cartTotalPrice )
            console.log("this.settings.min_price", this.settings.min_price)
            console.log("this.cashbackLimit ",this.cashbackLimit )
            console.log("settings", this.settings)
            console.log("sumIsValid", sumIsValid)
            console.log("canSubmitForm", sumIsValid && (this.spent_time_counter || 0) === 0)
            console.log("step 1",   this.cartTotalPrice >= (this.settings.min_price||0))
            console.log("step 2",     this.cartTotalPrice - (this.cashbackLimit || 0) > (this.settings.min_price||0))
            return sumIsValid && (this.spent_time_counter || 0) === 0
        },

        cashbackLimit() {
            let maxUserCashback = this.getSelf.cashBack ? this.getSelf.cashBack.amount : 0
            let summaryPrice = this.cartTotalPrice || 0
            let botCashbackPercent = this.bot.max_cashback_use_percent || 0

            let cashBackAmount = (summaryPrice * (botCashbackPercent / 100));

            return Math.min(cashBackAmount, maxUserCashback)
        },


    },

    mounted() {

        if (localStorage.getItem("cashman_self_product_delivery_counter") != null) {
            this.startTimer(localStorage.getItem("cashman_self_product_delivery_counter"))
        }

        this.modelValue.name = localStorage.getItem("cashman_self_product_delivery_form_name") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_name") : null

        this.modelValue.phone = localStorage.getItem("cashman_self_product_delivery_form_phone") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_phone") : null

        this.modelValue.address = localStorage.getItem("cashman_self_product_delivery_form_address") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_address") : null

        this.modelValue.city = localStorage.getItem("cashman_self_product_delivery_form_city") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_city") : null

        this.modelValue.street = localStorage.getItem("cashman_self_product_delivery_form_street") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_street") : null

        this.modelValue.building = localStorage.getItem("cashman_self_product_delivery_form_building") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_building") : null

        this.modelValue.flat_number = localStorage.getItem("cashman_self_product_delivery_form_flat_number") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_flat_number") : null

        this.modelValue.entrance_number = localStorage.getItem("cashman_self_product_delivery_form_entrance_number") != null ?
            localStorage.getItem("cashman_self_product_delivery_form_entrance_number") : null

        this.modelValue.disabilities = localStorage.getItem("cashman_self_product_delivery_form_entrance_disabilities") != null ?
            JSON.parse(localStorage.getItem("cashman_self_product_delivery_form_entrance_disabilities")) : []

        if (this.modelValue.disabilities.length > 0)
            this.modelValue.has_disability = true

        this.delivery_price_request_step = (this.modelValue.need_pickup === true ? 1 : 0) || (this.settings.need_automatic_delivery_request ? 0 : 1)
    },
    methods: {
        goToProductCart() {
            document.dispatchEvent(new Event('switch-to-cart'));
        },
        decPersons() {
            this.modelValue.persons = this.modelValue.persons > 1 ? this.modelValue.persons - 1 : this.modelValue.persons;
        },
        incPersons() {
            this.modelValue.persons = this.modelValue.persons < 100 ? this.modelValue.persons + 1 : this.modelValue.persons;
        },
        activatePromo(item) {
            this.modelValue.promo.discount_in_percent = item.discount_in_percent || false
            this.modelValue.promo.discount = item.discount || 0

            this.modelValue.promo.activate_price = item.activate_price || 0
            this.modelValue.promo.code = item.code || null
        },
        requestDeliveryPrice() {
            this.need_request_delivery_price = false
            this.error_delivery_price_message = null

            localStorage.setItem("cashman_self_product_delivery_form_address", this.modelValue.address || '')
            localStorage.setItem("cashman_self_product_delivery_form_city", this.modelValue.city || '')
            localStorage.setItem("cashman_self_product_delivery_form_street", this.modelValue.street || '')
            localStorage.setItem("cashman_self_product_delivery_form_building", this.modelValue.building || '')
            localStorage.setItem("cashman_self_product_delivery_form_flat_number", this.modelValue.flat_number || '')
            localStorage.setItem("cashman_self_product_delivery_form_entrance_number", this.modelValue.entrance_number || '')


            this.$notify({
                title: "Корзина",
                text: "Мы начали процесс расчета цены доставки",
            })

            this.$store.dispatch("requestDeliveryPrice", {
                city: this.modelValue.city,
                street: this.modelValue.street,
                building: this.modelValue.building,
            }).then(resp => {

                this.modelValue.delivery_price = resp.price || 0
                this.modelValue.distance = resp.distance || 0

                this.need_request_delivery_price = true
                this.delivery_price_request_step = 1
                this.$notify({
                    title: "Корзина",
                    text: "Цена доставки успешно просчитана",
                    type: "success"
                })
            }).catch(() => {
                this.modelValue.delivery_price = 0
                this.modelValue.distance = 0
                this.need_request_delivery_price = true
                this.delivery_price_request_step = 1
                this.error_delivery_price_message = "Цена будет рассчитана курьером в момент доставки!"
                this.$notify({
                    title: "Корзина",
                    text: "Ошибка расчёта цены доставки",
                    type: "error"
                })
            })
        },
        startCheckout() {
            if (this.spent_time_counter > 0)
                return;

            console.log("startCheckout")
            this.$emit("start-checkout")

            this.startTimer(10);
        },
        startTimer(time) {
            this.spent_time_counter = parseInt(time) != null ? Math.min(parseInt(time), 10) : 10;

            let counterId = setInterval(() => {
                    if (this.spent_time_counter > 0)
                        this.spent_time_counter--
                    else {
                        clearInterval(counterId)
                        this.spent_time_counter = null
                    }
                    localStorage.setItem("cashman_self_product_delivery_counter", this.spent_time_counter)
                }, 1000
            )
        },
        nextStep() {

            if (this.spent_time_counter > 0)
                return;

            const form = document.getElementById('basket');
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            const elementsArray = Array.from(requiredFields);

            // Развернуть массив
            const reversedArray = elementsArray.reverse();

            reversedArray.forEach(field => {
                if (!field.value.trim()) {
                    field.focus()
                    isValid = false;
                }
            });

            if (!isValid) {
                this.$notify({
                    title: "Корзина",
                    text: "Пожалуйста, заполните все обязательные поля.",
                    type: "error"
                })
                return;
            }

            this.$emit("change-tab", 3)

            this.startTimer(10);
        }
    }
}
</script>
