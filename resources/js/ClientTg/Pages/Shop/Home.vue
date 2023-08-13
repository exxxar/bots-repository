<script setup>
import ProductItemMini from "ClientTg@/Components/Shop/Products/ProductItemMini.vue";
import ProductItemLarge from "ClientTg@/Components/Shop/Products/ProductItemLarge.vue";
import SearchBox from "@/ClientTg/Components/Shop/SearchBox.vue";
import CategoryList from "@/ClientTg/Components/Shop/Categories/CategoryList.vue";
</script>

<template>

    <SearchBox/>

    <div v-if="randomProducts" class="single-slider owl-no-dots owl-carousel mt-n4 owl-loaded owl-drag">
        <ProductItemLarge :item="item" v-for="(item, index) in randomProducts"/>
    </div>

    <div class="content mb-3">
        <h5 class="float-left font-16 font-500">Случайные 20 товаров</h5>
        <a class="float-right font-12 color-highlight mt-n1" href="#/products">Посмотреть все</a>
        <div class="clearfix"></div>
    </div>

    <div v-if="randomProducts" class="double-slider owl-carousel owl-no-dots">
        <ProductItemMini :item="item" v-for="(item, index) in randomProducts"/>
    </div>

    <div class="divider divider-margins mt-4"></div>


    <div class="content mb-3" v-if="watches">
        <h5 class="float-left font-16 font-500">Ранее просмотрено</h5>
        <a class="float-right font-12 color-highlight mt-n1" href="#">Смотреть всё</a>
        <div class="clearfix"></div>
    </div>

    <div class="double-slider owl-carousel owl-no-dots" v-if="watches">
        <ProductItemMini :item="item" v-for="(item, index) in watches"/>
    </div>


    <div class="divider divider-margins mt-4"></div>


    <div class="card mt-4 preload-img" data-src="/shop/images/pictures/20s.jpg">
        <div class="card-body">
            <h3 class="color-white font-600">Best Priced Pack</h3>
            <p class="color-white opacity-80">
                The best value pack you can purchase for your needs created especially to suit you.
            </p>

            <div class="card rounded-m shadow-xl mb-0">
                <div class="content">
                    <div class="d-flex pb-3">
                        <div class="pr-3">
                            <h5 class="font-14 font-600 opacity-80 pb-2">Appkit Mobile Website Template and PWA. </h5>
                            <h1 class="font-24 font-700 ">$21<sup class="font-15 opacity-50">.99</sup></h1>
                        </div>
                        <div class="ml-auto">
                            <img src="/shop/images/pictures/2s.jpg" class="rounded-m shadow-xl" width="90">
                        </div>
                    </div>

                    <div class="divider mb-4"></div>

                    <div class="d-flex pb-2">
                        <div class="pr-3">
                            <h5 class="font-14 font-600 opacity-80 pb-2">6 Months Hands on Support Included in
                                Pack. </h5>
                            <h1 class="font-24 font-700 color-green1-dark">$0<sup class="font-15 opacity-50">.00</sup>
                            </h1>
                        </div>
                        <div class="ml-auto">
                            <img src="/shop/images/pictures/3s.jpg" class="rounded-m shadow-xl" width="90">
                        </div>
                    </div>

                    <div class="divider mb-4"></div>

                    <a href="#" class="btn btn-full btn-m bg-highlight font-700 text-uppercase rounded-m shadow-xl"> Add
                        to Cart</a>

                </div>
            </div>
        </div>
        <div class="card-overlay bg-highlight opacity-95"></div>
        <div class="card-overlay dark-mode-tint"></div>
    </div>

    <div class="divider divider-margins"></div>

    <div class="content mb-3">
        <h5 class="float-left font-16 font-500">Категории товаров</h5>
        <a class="float-right font-12 color-highlight mt-n1" href="#/categories">Посмотреть все</a>
        <div class="clearfix"></div>
    </div>

    <CategoryList />


</template>
<script>

import baseJS from 'ClientTg@/modules/custom.js'
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            botUser: null,
            watches: null,
            randomProducts: null
        }
    },
    watch: {
        'getSelf': function () {
            this.botUser = this.getSelf
            baseJS.handler()
        },
        'getWatches': function () {
            this.watches = this.getWatches.slice(0, 10)
            baseJS.handler()
        },
        $route (to, from){
            this.loadRandomProducts()
            this.watches = this.getWatches.slice(0, 10)
        }
    },
    computed: {
        ...mapGetters(['getProducts', 'getSelf', 'getWatches']),
        currentBot() {
            return window.currentBot;
        },
        logo() {
            return `/images-by-bot-id/${this.currentBot.id}/${this.currentBot.image}`
        },

    },
    mounted() {
        // this.watchStore()
        this.loadRandomProducts()
        this.watches = this.getWatches.slice(0, 10)
    },
    methods: {
        watchStore() {
            this.$store.watch(
                () => this.$store.getters.getSelf,
                data => {
                    this.botUser = this.$store.getters.getSelf
                    baseJS.handler()
                }
            )
        },

        loadRandomProducts() {
            return this.$store.dispatch("loadRandomProducts").then(() => {
                this.randomProducts = this.getProducts
                baseJS.handler()

            })
        }
    }
}
</script>
