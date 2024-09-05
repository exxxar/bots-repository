<script setup>
import ProductCard from "@/ClientTg/Components/V2/Admin/Shop/ProductCard.vue";
import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
</script>
<template>
    <template
        v-if="filteredCategories.length>0"
        v-for="cat in filteredCategories">
        <h5 class="my-4 divider" :id="'cat-'+cat.id">{{ cat.title || '-' }}</h5>

        <ul class="list-group">
            <li
                class="list-group-item d-flex justify-content-between align-items-center"
                @click="selectProduct(product)"
                v-bind:class="{'bg-success text-white fw-bold':(selected||[]).indexOf(product.id)!=-1}"
                v-for="(product, index) in cat.products">
                {{product.title}}
            </li>
        </ul>

    </template>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props:["selected"],
    data() {
        return {
            search: null,
            products: [],
            paginate: null,
            sort: {
                param: null,
                direction: 'asc'
            },
        }
    },
    computed: {
        ...mapGetters(['getProducts', 'getProductsPaginateObject']),
        filteredCategories() {
            if (this.products.length === 0)
                return []

            if ((this.search || '').length === 0)
                return this.products

            return this.products.filter(item => item.products.filter(sub => sub.title.toLowerCase().indexOf(this.search.toLowerCase()) != -1).length > 0)

        },

        filteredProducts() {

            if (this.categories.length === 0)
                return this.products


            return this.products.filter(product => product.categories.findIndex(cat => this.categories.indexOf(cat.id) !== -1) !== -1)
        },
    },
    mounted() {
        this.loadProducts()

    },
    methods: {
        changeDirection(direction) {
            this.sort.direction = direction
            this.loadOrders(0)
        },
        selectProduct(product) {
            console.log("product", product)
            this.$emit("select", product)
        },
        nextProducts(index) {
            this.loadProducts(index)
        },
        loadProducts(page = 0) {
            return this.$store.dispatch("loadProductsByCategory", {
                dataObject: {
                    search: this.search,
                    direction: this.sort.direction,
                    order_by: this.sort.param
                },
                page: page,
                size: 100
            }).then((resp) => {
                this.products = resp.data
            }).catch(() => {

            })
        }
    }
}
</script>

