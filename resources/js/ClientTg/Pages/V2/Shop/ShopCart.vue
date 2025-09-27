<script setup>
import CartProductList from "@/ClientTg/Components/V2/Shop/Cart/CartProductList.vue";
import CheckoutProductForm from "@/ClientTg/Components/V2/Shop/Cart/CheckoutProductForm.vue";
import CheckoutNonFoodGoodsForm from "@/ClientTg/Components/V2/Shop/Cart/CheckoutNonFoodGoodsForm.vue";
import ScreenPaymentForm from "@/ClientTg/Components/V2/Shop/Cart/ScreenPaymentForm.vue";
import PreloaderV1 from "@/ClientTg/Components/V2/Shop/Other/PreloaderV1.vue";
import {canBy} from "@/ClientTg/utils/commonMethods.js";

</script>
<template>

    <div
        v-touch:swipe.left="doSwipeLeft"
        v-touch:swipe.right="doSwipeRight"
        v-if="cartTotalCount>0">
        <template v-if="tab===0">
            <CartProductList
                v-on:select-prize="selectPrize"
                v-on:change-tab="changeTab"
                :form-data="deliveryForm">
                <template #upper-text>
                    <h4>Итого</h4>
                    <p>
                        Ниже приведена итоговая цена заказа без учета стоимости доставки. Цена доставки
                        рассчитывается отдельно
                        и
                        зависит от расстояния.
                    </p>
                </template>
            </CartProductList>
        </template>

        <template v-if="tab===1">
                <CheckoutProductForm
                    v-if="settings.shop_display_type === 0"
                    v-on:start-checkout="startCheckout"
                    v-on:change-tab="changeTab"
                    v-model="deliveryForm"></CheckoutProductForm>
                <CheckoutNonFoodGoodsForm
                    v-else
                    v-on:start-checkout="startCheckout"
                    v-on:change-tab="changeTab"
                    v-model="deliveryForm"></CheckoutNonFoodGoodsForm>
        </template>

        <template v-if="tab===3">
            <ScreenPaymentForm
                v-on:start-checkout="startCheckout"
                v-model="deliveryForm"></ScreenPaymentForm>
        </template>

    </div>

    <div v-else
         v-touch:swipe.left="doSwipeLeft"
         v-touch:swipe.right="doSwipeRight"
         class="d-flex flex-column justify-content-center align-items-center" style="height:100vh;">
        <div class="d-flex justify-content-center flex-column align-items-center">
            <i class="fa-brands fa-shopify mb-3" style="font-size:36px;"></i>
            <p>Корзина пустая:(</p>
        </div>
    </div>

    <nav
        v-if="canBy"
        class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
        style="border-radius:10px 10px 0px 0px;">
        <div v-if="cartTotalCount>0" class="w-100">
            <button type="button"
                    v-if="tab===0"
                    @click="tab=1"
                    style="box-shadow: 1px 1px 6px 0px #0000004a;"
                    class="btn btn-primary w-100 p-3 rounded-3 shadow-lg d-flex justify-content-center">
                Оформление заказа
            </button>
            <!--            <button type="button"
                                v-if="tab>=1"
                                @click="tab=0"
                                style="box-shadow: 1px 1px 6px 0px #0000004a;"
                                class="btn btn-primary w-100 p-3 rounded-3 shadow-lg d-flex justify-content-center">
                            Корзина с товаром
                        </button>-->
        </div>
        <div v-else class="w-100">
            <button type="button"
                    @click="goToCatalog"
                    style="box-shadow: 1px 1px 6px 0px #0000004a;"
                    class="btn btn-primary w-100 p-3 rounded-3 shadow-lg d-flex justify-content-center">
                Вернуться в магазин
            </button>
        </div>
    </nav>

    <nav
        class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
        style="border-radius:10px 10px 0px 0px;"
        v-else>
        <p
            style="box-shadow: 1px 1px 6px 0px #0000004a;"
            class="btn btn-secondary w-100 p-3 rounded-3 shadow-lg d-flex justify-content-between "
        >
            В данный момент покупки недоступны
        </p>
    </nav>
</template>
<script>

import {mapGetters} from "vuex";
import {startTimer} from "@/ClientTg/utils/commonMethods.js";


export default {
    props: ["type"],
    data() {
        return {
            tab: 0,
            deliveryForm: {
                name: null,
                phone: null,
                address: null,
                promo: {
                    discount_in_percent: false,
                    discount: 0,
                    activate_price: 0,
                    code: null,
                },
                cdek: {
                    tariff: null,
                    to: {
                        region: null,
                        city: null,
                        office: null,
                        address: null,
                    },
                },
                city: null,
                street: null,
                building: null,
                flat_number: null,
                entrance_number: null,
                floor_number: null,
                table_number: null,
                info: null,
                need_pickup: false,
                pick_up_type: 1,
                has_disability: false,
                use_cashback: false,
                disabilities: [],
                money: null,
                cash: true,
                payment_type: 2,
                persons: 1,
                time: null,
                when_ready: true,// по готовности
                image: null,
                image_info: null,
                delivery_price: 0,
                distance: 0,
                allergy: null,
                action_prize: null,
            },
        }
    },
    watch: {
        'tab': {
            handler: function (newValue) {
                window.scroll(0, 80);
            },
            deep: true
        },
        'deliveryForm.need_pickup': {
            handler: function (newValue) {
                if (this.deliveryForm.need_pickup) {
                    this.deliveryForm.delivery_price = 0
                    this.deliveryForm.distance = 0
                }
            },
            deep: true
        },
        'deliveryForm.cash': {
            handler: function (newValue) {
                if (!this.deliveryForm.cash)
                    this.deliveryForm.money = null
            },
            deep: true
        },
        'deliveryForm.has_disability': {
            handler: function (newValue) {
                this.deliveryForm.disabilities = []
                this.deliveryForm.allergy = null
            },
            deep: true
        },

    },
    computed: {
        ...mapGetters(['cartTotalCount', 'cartTotalPrice', 'getSelf']),

        settings() {
            return this.bot.settings
        },
        bot() {
            return window.currentBot
        },
        tg() {
            return window.Telegram.WebApp;
        },

    },

    mounted() {
        this.loadBasketData()

        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })

        window.addEventListener("scroll-to-basket", (event) => { // (1)
            this.tab = 2
            document.querySelector("#basket").scrollIntoView({
                behavior: 'smooth'
            });
        });

        document.addEventListener('switch-to-cart', () => {
            this.changeTab(0)

            document.querySelector("#basket").scrollIntoView({
                behavior: 'smooth'
            });
        });

    },
    methods: {
        doSwipeLeft() {
            this.$router.push({ name: 'CatalogV2' })

        },
        doSwipeRight() {
            this.$router.push({ name: 'MenuV2' })


        },
        changeTab(index) {
            this.tab = index
        },

        goToCatalog() {
            this.$router.push({name: 'CatalogV2'})
        },
        selectPrize(item) {
            this.deliveryForm.action_prize = item
        },

        loadBasketData() {
            return this.$store.dispatch("loadProductsInBasket")
        },
        startCheckout() {
            let data = new FormData();

            //data.append("need_payment_link", this.deliveryForm.payment_type === 0)
            data.append("display_type", this.settings.shop_display_type)

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


            data.append("need_automatic_delivery_request", this.settings.need_automatic_delivery_request)

            if (this.deliveryForm.payment_type === 0 && this.settings.can_use_card) {
                this.$store.dispatch("createCheckoutLink", {
                    deliveryForm: data

                }).then((resp) => {
                    this.tg.openInvoice(resp.result)
                })
                return;
            }

            if (typeof this.deliveryForm.image != "string") {
                data.append('photo', this.deliveryForm.image);
                data.delete("image")
            }

            this.sending = true
            this.$store.dispatch("startCheckout", {
                deliveryForm: data

            })
                .then((response) => {

                  /*  this.deliveryForm = {
                        message: null,
                        name: null,
                        phone: null,
                    }*/

                    this.$notify({
                        title: "Доставка",
                        text: "Дальнейшая инструкция отправлена вам в бот!",
                        type: "success"
                    })

                    this.tab = 1
                    this.$store.dispatch("clearCart");

                    if (response.url)
                        window.location.href = response.url


                    this.sending = false
                }).catch(err => {
                this.sending = false

                this.$notify({
                    title: "Доставка",
                    text: "Ошибка! Обратитесь к администратору",
                    type: "error"
                })
            })

            startTimer(10);
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


.photo-loader-bill {
    width: 100%;
    height: 300px;
    border-radius: 5px;
    border: 1px solid #dcdcdc;


    span {
        font-size: 20px;
        font-weight: normal !important;
    }
}

.img-preview-bill {
    width: 100%;
    height: 300px;
    border-radius: 5px;
    position: relative;

    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;

    img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 1;
        border-radius: 5px;
    }

    .remove {
        position: absolute;
        z-index: 2;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.38);
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        color: white;
        font-size: 20px;
    }

}
</style>
