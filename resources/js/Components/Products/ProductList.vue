<script setup>
import Product from "@/Components/Products/Product.vue";


</script>
<template>
    <div class="row">
        <div class="col-12">
            <input type="text" class="form-control" v-model="search">
        </div>
        <div class="col-12" v-if="products.length>0"
             v-for="(product, index) in products"
        >
            <Product :item="product"/>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            search: null,
            products: []
        }
    },
    computed: {
        ...mapGetters(['getProducts']),
    },
    watch: {
        'search': function (oldV, newV) {
            this.loadBotProducts()
        }
    },
    mounted() {
        this.loadBotProducts()
    },
    methods: {
        loadBotProducts(page = 0) {
            this.$store.dispatch("loadProducts", {
                dataObject: {
                    search: this.search
                },
                page: page
            }).then(() => {
                this.products = this.getProducts
            }).catch(() => {

            })
        }
    }
}
</script>
