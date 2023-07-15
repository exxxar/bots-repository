<script setup>
import ProductItemSimple from "@/Components/Shop/Products/ProductItemSimple.vue";
</script>
<template>


    <div class="page-title page-title-small">
        <h2><a @click="$router.back()" data-back-button=""><i class="fa fa-arrow-left"></i></a>Корзина</h2>
        <a href="#" data-menu="menu-main" class="bg-fade-gray1-dark shadow-xl preload-img"
           data-src="/shop/images/avatars/5s.png" style="background-image: url(&quot;images/avatars/5s.png&quot;);"></a>
    </div>
    <div class="card header-card shape-rounded" data-card-height="150" style="height: 150px;">
        <div class="card-overlay bg-highlight opacity-95"></div>
        <div class="card-overlay dark-mode-tint"></div>
        <div class="card-bg preload-img" data-src="/shop/images/pictures/20s.jpg"
             style="background-image: url(&quot;images/pictures/20s.jpg&quot;);"></div>
    </div>

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

            <a href="#" class="btn btn-full btn-sm rounded-sm bg-highlight font-800 text-uppercase">Перейти к
                оформлению</a>
        </div>
    </div>


    <div class="card bg-20 mt-4 content rounded-m shadowl" v-else>
        <div class="card-body">
            <h4 class="color-white">Товар отсутствует</h4>
            <p class="color-white">
                На текущий момент товар на страницах сайта отсуствтует
            </p>
            <div class="card card-style ml-0 mr-0 mb-3 bg-white">
                <div class="content">
                    Вы можете перейти в раздел товаров и попробовать добавить что-то в корзину или избранное
                </div>
            </div>
        </div>
        <div class="card-overlay bg-gradient-blue1 opacity-95 rounded-m shadow-l"></div>
        <div class="card-overlay dark-mode-tint rounded-m shadow-l"></div>
    </div>

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
