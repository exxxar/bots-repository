<script setup>
import ProductItemSimple from "@/ClientTg/Components/Shop/Products/ProductItemSimple.vue";
import Pagination from "@/ClientTg/Components/Shop/Helpers/Pagination.vue";
import CategoryList from "@/ClientTg/Components/Shop/Categories/CategoryList.vue";
</script>

<template>

    <div class="card card-style" v-if="filteredProducts.length>0">
        <div class="content">
            <h3>Наши товары</h3>


            <div class="collapse" id="collapse-8" style="">

                <div class="input-style input-style-2 has-icon input-required">
                    <i class="input-icon fa-solid fa-magnifying-glass" @click="loadProducts(0)"></i>
                    <input class="form-control" v-model="search" type="name" placeholder="Найди товар на странице">
                </div>
                <p class="mb-0 pb-1">
                   Цена:
                </p>
               <div class="row mb-0">
                   <div class="col-6">
                       <div class="input-style input-style-2 input-required">
                           <span class="color-highlight">От, ₽</span>
                           <input class="form-control" type="email" placeholder="0 ₽">
                       </div>
                   </div>
                   <div class="col-6">
                       <div class="input-style input-style-2 input-required">
                           <span class="color-highlight">До, ₽</span>
                           <input class="form-control" type="email" placeholder="100 ₽">
                       </div>
                   </div>
               </div>
            </div>
            <a data-toggle="collapse" href="#collapse-8"
               class="btn btn-m btn-full rounded-sm font-900 shadow-xl text-uppercase mb-3" aria-expanded="true">
                <i class="fa-solid fa-filter  mr-2"></i>
                <span class="font-14">Фильтры товара</span>
            </a>


                <ProductItemSimple :item="product" v-for="(product, index) in filteredProducts"/>

                <Pagination
                    :simple="true"
                    v-on:pagination_page="nextProducts"
                    v-if="paginate"
                    :pagination="paginate"/>



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
                    You can add more content in here as well. This is used for example on the homepage in the Built for You section.
                </div>
            </div>
        </div>
        <div class="card-overlay bg-gradient-green1 opacity-95 rounded-m shadow-l"></div>
        <div class="card-overlay dark-mode-tint rounded-m shadow-l"></div>
    </div>

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
            search: null,
            products: [],
            paginate: null,
        }
    },
    computed: {
        ...mapGetters(['getProducts', 'getProductsPaginateObject']),
        filteredProducts() {

            if (!this.search)
                return this.products

            return this.products.filter(product => product.title.toLowerCase().trim().indexOf(this.search.toLowerCase().trim()) >= 0)
        },
    },
    mounted() {
        this.loadProducts()

    },
    methods: {
        nextProducts(index) {
            this.loadProducts(index)
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
                baseJS.handler()
            })
        }
    }
}
</script>
