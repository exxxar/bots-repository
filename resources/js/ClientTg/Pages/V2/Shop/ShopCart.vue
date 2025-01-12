<script setup>
import CartProductList from "@/ClientTg/Components/V2/Shop/Cart/CartProductList.vue";
import CheckoutProductForm from "@/ClientTg/Components/V2/Shop/Cart/CheckoutProductForm.vue";
import CheckoutNonFoodGoodsForm from "@/ClientTg/Components/V2/Shop/Cart/CheckoutNonFoodGoodsForm.vue";
import ScreenPaymentForm from "@/ClientTg/Components/V2/Shop/Cart/ScreenPaymentForm.vue";
import PreloaderV1 from "@/ClientTg/Components/V2/Shop/Other/PreloaderV1.vue";
</script>
<template>

    <div v-if="cartTotalCount>0">
        <template v-if="tab===0">
            <CartProductList
                v-if="loaded_settings"
                v-on:select-prize="selectPrize"
                v-on:change-tab="changeTab"

                :form-data="deliveryForm"
                :settings="settings">
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
            <PreloaderV1 v-else/>
        </template>

        <template v-if="tab===1">
            <template v-if="loaded_settings">
                <CheckoutProductForm
                    v-if="settings.shop_display_type === 0"
                    v-on:submit="startCheckout"
                    v-on:change-tab="changeTab"
                    v-model="deliveryForm"
                    :settings="settings"></CheckoutProductForm>
                <CheckoutNonFoodGoodsForm
                    v-else
                    v-on:submit="startCheckout"
                    v-on:change-tab="changeTab"
                    v-model="deliveryForm"
                    :settings="settings"></CheckoutNonFoodGoodsForm>
            </template>
            <PreloaderV1 v-else/>
        </template>

        <template v-if="tab===3">
            <ScreenPaymentForm
                v-if="loaded_settings"
                v-on:submit="startCheckout"
                v-model="deliveryForm"
                :settings="settings"></ScreenPaymentForm>
            <PreloaderV1 v-else/>
        </template>

    </div>

    <div v-else class="d-flex flex-column justify-content-center align-items-center" style="height:100vh;">
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
            <button type="button"
                    v-if="tab>=1"
                    @click="tab=0"
                    style="box-shadow: 1px 1px 6px 0px #0000004a;"
                    class="btn btn-primary w-100 p-3 rounded-3 shadow-lg d-flex justify-content-center">
                Корзина с товаром
            </button>
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

export default {
    props: ["type"],
    data() {
        return {
            tab: 0,
            loaded_settings:true,
            settings: {
                can_use_cash: true,
                can_use_card: true,
                delivery_price_text: null,
                min_price: 0,
                min_price_for_cashback: 0,
                menu_list_type: 0,
                payment_info: 0,
                need_category_by_page: false,
                need_pay_after_call: false,
                can_buy_after_closing: false,
                free_shipping_starts_from: 0,

                max_tables:0,
                need_table_list: false,

                need_use_sbp: false,
                sbp: {
                    selected_sbp_bank:'tinkoff',
                    tinkoff: {
                        terminal_key: null,
                        terminal_password: null,
                    },
                    sber: {}
                },

                shop_display_type: 0,
                is_product_list: false,

                need_automatic_delivery_request: true,
                need_promo_code: true,
                need_person_counter: true,
                need_bonuses_section: true,
                need_health_restrictions: true,
                need_prizes_from_wheel_of_fortune: true,
                selected_script_id: null,
            },


            deliveryForm: {
                name: null,
                phone: null,
                address: null,
                promo: {
                    discount_in_percent:false,
                    discount: 0,
                    activate_price: 0,
                    code: null,
                },
                cdek:{
                    tariff:null,
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
                payment_type: 4,
                persons: 1,
                time: null,
                when_ready: true,// по готовности
                image: null,
                image_info: null,
                delivery_price: 0,
                distance: 0,
                allergy: null,
                action_prize:null,
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
        canBy() {
            // return false
            if (!window.isCorrectSchedule(this.bot.company.schedule))
                return true

            return (this.bot.company || {is_work: true}).is_work || this.settings.can_buy_after_closing
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

        this.loadShopModuleData()

        window.addEventListener("scroll-to-basket", (event) => { // (1)
            this.tab = 2
            document.querySelector("#basket").scrollIntoView({
                behavior: 'smooth'
            });
        });



    },
    methods: {
        changeTab(index) {
            this.tab = index
        },

        goToCatalog() {
            this.$router.push({name: 'CatalogV2'})
        },
        selectPrize(item){
          this.deliveryForm.action_prize = item
        },
        loadShopModuleData() {
            this.loaded_settings = false
            return this.$store.dispatch("loadShopModuleData").then((resp) => {
                this.$nextTick(() => {
                    let data = resp.data
                    Object.keys(data).forEach(item => {
                        this.settings[item] = data[item]
                    })

                    this.loaded_settings = true
                })
            })
        },
        loadBasketData() {
            return this.$store.dispatch("loadProductsInBasket")
        },
        startCheckout() {

            localStorage.setItem("cashman_self_product_delivery_form_name", this.deliveryForm.name || '')
            localStorage.setItem("cashman_self_product_delivery_form_phone", this.deliveryForm.phone || '')
            localStorage.setItem("cashman_self_product_delivery_form_address", this.deliveryForm.address || '')
            localStorage.setItem("cashman_self_product_delivery_form_city", this.deliveryForm.city || '')
            localStorage.setItem("cashman_self_product_delivery_form_street", this.deliveryForm.street || '')
            localStorage.setItem("cashman_self_product_delivery_form_building", this.deliveryForm.building || '')
            localStorage.setItem("cashman_self_product_delivery_form_flat_number", this.deliveryForm.flat_number || '')

            localStorage.setItem("cashman_self_product_delivery_form_entrance_number", this.deliveryForm.entrance_number || '')
            if ((this.deliveryForm.disabilities || []).length > 0)
                localStorage.setItem("cashman_self_product_delivery_form_entrance_disabilities", JSON.stringify(this.deliveryForm.disabilities || []))
            else
                localStorage.removeItem("cashman_self_product_delivery_form_entrance_disabilities");


         /*   if (this.spent_time_counter>0) {
                this.$notify({
                    title: 'Упс!',
                    text: "Сделать повторный заказ можно через "+this.spent_time_counter+" сек.",
                    type: 'error'
                })

                return;
            }
*/
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

                this.$notify({
                    title: "Доставка",
                    text: "Ошибка! Обратитесь к администратору",
                    type: "error"
                })
            })

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
