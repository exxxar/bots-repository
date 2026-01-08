<script setup>
import ProductCard from "@/ClientTg/Components/V2/Admin/Shop/AdminProductCard.vue";
import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
import PreloaderV1 from "@/ClientTg/Components/V2/Shop/Other/PreloaderV1.vue";
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
        <template v-if="cat.products_count > cat.products.length">
            <div class="col-12">

                <button
                    @click="loadMore(cat.id, cat.products.length)"
                    class="btn btn-outline-light text-primary p-3 my-3 w-100" type="button">
                    <span v-if="!load_content">Загрузить еще
                    ({{ cat.products_count - cat.products.length }})
                    </span>
                    <span v-else class="d-inline-flex align-items-center">
                        Загружаем....
                            <span class="spinner-border" role="status">
                              <span class="visually-hidden">Loading...</span>
                            </span>
                    </span>
                </button>

            </div>
        </template>

    </template>
    <PreloaderV1 v-else/>
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
        loadMore(catId, offset) {
            this.load_content = true
            return this.$store.dispatch("loadMoreProductsByCategory", {
                partner_id: this.selected_partner?.bot_partner_id || null,
                category_id: catId,
                offset: offset,
            }).then((resp) => {

                let count = resp.length || 0


                this.load_content = false
                if (count === 0)
                {
                    this.products.find(p => p.id === catId).products_count = offset
                    return
                }

                this.products.find(p => p.id === catId).products.push(...resp)
            }).catch(() => {
                this.load_content = false
            })
        },
        changeDirection(direction) {
            this.sort.direction = direction
            this.loadOrders(0)
        },
        selectProduct(product) {

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

