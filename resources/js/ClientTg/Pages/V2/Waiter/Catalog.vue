<script setup>
import ShopProductCatalog from "@/ClientTg/Components/V2/Shop/ShopProductCatalog.vue";
import TableList from "@/ClientTg/Components/V2/Waiter/TableList.vue";
</script>
<template>

    <template v-if="step===0">
        <TableList/>
    </template>

    <template v-if="step===2">
        <ShopProductCatalog/>
        <nav

            class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
            style="border-radius:10px 10px 0px 0px;">
            <button
                v-if="canBy"
                @click="goToCart"
                style="box-shadow: 1px 1px 6px 0px #0000004a;"
                class="btn btn-primary w-100 p-3 rounded-3 shadow-lg d-flex justify-content-between ">

            <span class="d-block" style="position:relative;"><i class="fa-solid fa-cart-shopping mr-2">
            </i><sup class="bg-white text-primary sup-badge" v-if="cartTotalCount>0">{{ cartTotalCount }}</sup>Корзина </span>
                <strong>{{ cartTotalPrice || 0 }}<sup class="font-10 opacity-50">.00</sup>₽</strong>
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

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            step: 0,
            table_number: null,
            selected_table: null,
            tables: []
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

            return (this.bot.company || {is_work: true}).is_work || this.settings.can_buy_after_closing
        },
    },
    methods: {
        goToCart() {
            this.$router.push({name: 'ShopCartV2'})
        },

    }
}
</script>
