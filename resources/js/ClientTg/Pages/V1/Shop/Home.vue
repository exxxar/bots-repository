<script setup>
import ProductItemMini from "@/ClientTg/Components/V1/Shop/Products/ProductItemMini.vue";
import ProductItemLarge from "@/ClientTg/Components/V1/Shop/Products/ProductItemLarge.vue";
import SearchBox from "@/ClientTg/Components/V1/Shop/SearchBox.vue";
import CategoryList from "@/ClientTg/Components/V1/Shop/Categories/CategoryList.vue";
import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
</script>

<template>

    <div
        v-if="currentBot"
        class="card card-style preload-img"
         :data-src="logo" style="height: 500px;">
        <div class="card-bottom px-3">
            <h1 class="font-40 line-height-xl">{{currentBot.company.title||'Без названия'}}</h1>
            <p class="pb-0 mb-0 font-12"><i class="fa fa-map-marker mr-2"></i>{{currentBot.company.address || 'Без адреса'}}</p>
            <p>
                {{currentBot.description || 'Без описания'}}
            </p>

            <a href="#/contact-us"
               class="btn btn-m btn-border w-100 btn-full rounded-s mb-3 border-highlight color-highlight text-uppercase font-900">
                <i class="fa-regular fa-envelope mr-2"></i> Написать нам
            </a>

        </div>
        <div class="card-overlay bg-gradient-fade"></div>
    </div>

    <div class="card card-style p-3">
        <h5>Галлерея продуктов</h5>

        <div class="row text-center row-cols-3 mb-n4">
            <a class="col mb-4 default-link"
               v-for="product in products"
               :href="'#/products/'+product.id"
               :title="product.title">
                <img v-lazy="product.images[0]"
                     class="img-fluid rounded-xs preload-img" alt="img">
            </a>


        </div>

        <Pagination
            :simple="true"
            v-on:pagination_page="nextProducts"
            v-if="paginate"
            :pagination="paginate"/>
    </div>

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

import baseJS from '@/ClientTg/modules/custom.js'
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            botUser: null,
            watches: null,
            products:null,
            paginate:null,
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
        ...mapGetters(['getProducts', 'getSelf', 'getWatches','getProductsPaginateObject']),
        currentBot() {
            return window.currentBot;
        },
        logo() {
            return `/images-by-bot-id/${this.currentBot.id}/${this.currentBot.image}`
        },

    },
    mounted() {
        // this.watchStore()
        this.loadProducts()
        this.loadRandomProducts()
        this.watches = this.getWatches.slice(0, 10)
    },
    methods: {
        nextProducts(index) {
            this.loadProducts(index)
        },
        watchStore() {
            this.$store.watch(
                () => this.$store.getters.getSelf,
                data => {
                    this.botUser = this.$store.getters.getSelf
                    baseJS.handler()
                }
            )
        },
        loadProducts(page = 0) {
            return this.$store.dispatch("loadProducts", {
                dataObject: {
                    search: this.search
                },
                page: page
            }).then(() => {
                this.products = this.getProducts
                this.paginate = this.getProductsPaginateObject
            })
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
