<script setup>
import ShopProductCatalog from "@/ClientTg/Components/V2/Shop/ShopProductCatalog.vue";

</script>
<template>

    <ShopProductCatalog
        :settings="settings"/>


    <nav

        class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
        style="border-radius:10px 10px 0px 0px;">
        <button
            v-if="canBy"

            style="box-shadow: 1px 1px 6px 0px #0000004a;"
            class="btn btn-primary w-100 p-2 px-3 rounded-3 shadow-lg d-flex justify-content-between align-items-center">

            <div class="btn-group dropup">
                <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <i class="fa-solid fa-cart-shopping mr-2"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item"
                           @click="clearCart"
                           href="javascript:void(0)">Очистить корзину</a></li>
                </ul>
            </div>

            <div
                class="d-flex justify-content-between align-items-center w-100 px-2"
                @click="goToCart">
  <span class="d-block" style="position:relative;">


                <sup class="bg-white text-primary sup-badge" v-if="cartTotalCount>0">{{ cartTotalCount }}</sup>Ваш заказ </span>
                <strong>{{ cartTotalPrice || 0 }}<sup class="font-10 opacity-50">.00</sup>₽</strong>
            </div>

        </button>
        <p
            v-else
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
    data() {
        return {
            settings: null
        }
    },
    computed: {
        ...mapGetters([
            'inCart',
            'cartProducts',
            'cartTotalCount',
            'cartTotalPrice',
            'getSelf']),

        bot() {
            return window.currentBot
        },
        canBy() {
            if (!window.isCorrectSchedule(this.bot.company.schedule))
                return true


            return (this.bot.company || {is_work: true}).is_work || (this.settings ? this.settings.can_buy_after_closing : true)
        },
    },
    mounted() {
        this.loadShopModuleData()
        this.loadBasketData()
    },
    methods: {
        clearCart() {
            this.$store.dispatch("clearCart").then(() => {
                this.$notify({
                    title: "Корзина",
                    text: "Корзина успешно очищена!",
                    type: "success"
                })
            })
        },
        goToCart() {
            this.$router.push({name: 'TableCartV2'});
        },
        loadBasketData() {
            return this.$store.dispatch("loadProductsInBasket")
        },
        loadShopModuleData() {
            return this.$store.dispatch("loadShopModuleData").then((resp) => {
                this.$nextTick(() => {
                    if (!this.settings)
                        this.settings = {}

                    if (resp)
                        Object.keys(resp).forEach(item => {
                            if (item)
                                this.settings[item] = resp[item]
                        })

                    this.settings_loaded = true
                })
            })
        },

    }
}
</script>
