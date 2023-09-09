<script setup>
import ProductCard from "@/ClientTg/Components/Admin/Shop/ProductCard.vue";
import Pagination from "@/ClientTg/Components/Pagination.vue";
</script>
<template>



    <div class="card card-style">
        <div class="content">
            <form
                v-on:submit.prevent="loadProducts(0)">
                <input type="text"
                       class="form-control mb-2"
                       placeholder="Поиск товара"
                       aria-label="Поиск товара"
                       v-model="search"
                       aria-describedby="button-addon2">
                <button class="btn btn-m btn-full rounded-xs text-uppercase font-900 shadow-s bg-blue2-dark w-100 mb-0"
                        type="submit"
                        id="button-addon2">
                    Найти
                </button>
            </form>
            <p>Всего товаров: <span v-if="paginate">{{paginate.meta.total || 0}}</span></p>
            <ProductCard
                v-for="(product, index) in filteredProducts"
                v-on:select="selectProduct(product)"
                :item="product"/>

            <Pagination
                :simple="true"
                v-on:pagination_page="nextProducts"
                v-if="paginate"
                :pagination="paginate"/>

        </div>
    </div>






</template>
<script>
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
            return this.products

            //return this.products.filter(product => product.title.toLowerCase().trim().indexOf(this.search.toLowerCase().trim()) >= 0)
        },
    },
    mounted() {
        this.loadProducts()

    },
    methods: {
        selectProduct(product) {
            console.log("product", product)
            this.$emit("select", product)
        },
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
            })
        }
    }
}
</script>

