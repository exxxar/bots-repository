<script setup>
import ProductCard from "@/ClientTg/Components/V2/Admin/Shop/ProductCard.vue";
import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
</script>
<template>
    <form
        v-on:submit.prevent="loadProducts(0)">

        <div class="input-group mb-3">

            <div class="form-floating">
                <input type="text"
                       class="form-control"
                       placeholder="Поиск товара"
                       aria-label="Поиск товара"
                       v-model="search"
                       aria-describedby="button-addon2">
                <label for="floatingInput">Критерии поиска</label>
            </div>


            <button class="btn btn-outline-secondary"
                    type="submit"
                    id="button-addon2">
                Найти
            </button>
        </div>



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

