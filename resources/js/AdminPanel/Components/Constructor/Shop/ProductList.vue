<script setup>
import ProductCard from "@/AdminPanel/Components/Constructor/Shop/ProductCard.vue";
import Pagination from "@/AdminPanel/Components/Pagination.vue";
</script>
<template>
    <form class="input-group mb-3"
          v-on:submit.prevent="loadProducts(0)">
        <input type="text"
               class="form-control border-info"
               placeholder="Поиск товара"
               aria-label="Поиск товара"
               v-model="search"
               aria-describedby="button-addon2"
               required>
        <button class="btn btn-outline-info"
                type="submit"
                id="button-addon2">
           Найти
        </button>
    </form>
    <p>Всего товаров: <span v-if="paginate">{{paginate.meta.total || 0}}</span></p>
    <div class="row">
        <div class="col-4 mb-2" v-for="(product, index) in filteredProducts">
            <ProductCard
                @click="selectProduct(product)"
                :item="product"/>
        </div>
    </div>


    <Pagination
        v-on:pagination_page="nextProducts"
        v-if="paginate"
        :pagination="paginate"/>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["bot"],
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
        selectProduct(product) {
            this.$emit("select", product)
        },
        nextProducts(index) {
            this.loadProducts(index)
        },
        loadProducts(page = 0) {
            return this.$store.dispatch("loadProducts", {
                dataObject: {
                    bot_id: this.bot.id,
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

