<script setup>
import ProductItemSimple from "@/Components/Shop/Products/ProductItemSimple.vue";
import EmptyCard from "@/Components/Shop/Helpers/EmptyCard.vue";
</script>
<template>


    <div class="card card-style" v-if="cartProducts.length>0">
        <div class="content">


            <ProductItemSimple :item="item.product" v-for="(item, index) in cartProducts"/>

            <div class="divider mt-3"></div>

            <h4>Итого</h4>
            <p>
                Ниже приведена итоговая цена заказа без учета стоимости доставки. Цена доставки расчитывается отдельно и
                зависит от расстояния.
            </p>
            <div class="row mb-0" v-for="(item, index) in cartProducts">

                <div class="col-6 text-left"><h6 class="font-600">{{ item.product.title }}</h6></div>
                <div class="col-2 text-center"><h6 class="font-600">x{{ item.quantity || 1 }}</h6></div>
                <div class="col-4 text-right"><h6 class="font-600">{{ item.product.current_price }} <sup>.00</sup>₽</h6>
                </div>


            </div>
            <div class="divider mt-4"></div>
            <div class="row mb-0">
                <div class="col-6 text-left"><h4>Суммарно, ед.</h4></div>
                <div class="col-6 text-right"><h4>{{ cartTotalCount }} шт.</h4></div>
                <div class="col-6 text-left"><h4>Суммарно, цена</h4></div>
                <div class="col-6 text-right"><h4>{{ cartTotalPrice }}<sup>.00</sup>₽</h4></div>

            </div>


            <div class="divider mt-4"></div>

            <router-link
                class="btn btn-full btn-sm rounded-sm bg-highlight font-800 text-uppercase"
                :tag="'a'" :to="'/checkout'">
                <i class="fa-solid fa-file-invoice mr-2"></i><span class="color-white">Перейти к
                оформлению</span>
            </router-link>

        </div>
    </div>


    <EmptyCard v-else>
        <template v-slot:title>
            Товар отсутствует
        </template>
        <template v-slot:head>
            На текущий момент товар на страницах сайта отсуствтует
        </template>
        <template v-slot:body>
            Вы можете перейти в раздел товаров и попробовать добавить что-то в корзину или избранное
        </template>
    </EmptyCard>



</template>
<script>


import {mapGetters} from "vuex";

export default {
    computed: {
        ...mapGetters(['cartProducts', 'cartTotalCount', 'cartTotalPrice']),
    },
    mounted() {
        if (this.cartProducts.length > 0)
            this.loadActualProducts()
    },
    methods: {
        loadActualProducts() {
            this.$store.dispatch("loadActualPriceInCart")
        }
    }
}
</script>
