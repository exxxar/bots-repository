<script setup>
import ProductForm from "@/ClientTg/Components/Admin/Shop/ProductForm.vue";
import ProductList from "@/ClientTg/Components/Admin/Shop/ProductList.vue";
</script>
<template>

    <div class="card card-style">
        <div class="content mb-0">
            <h3 class="font-700">Управление магазином</h3>

            <a
                v-if="link"
                :href="link"
                type="button"
                class="btn btn-border btn-m btn-full mb-1 rounded-sm text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme w-100">
                Обновить товар из ВК
            </a>

            <button class="btn btn-border btn-m btn-full mb-1 rounded-sm text-uppercase font-900 border-green1-dark color-green1-dark bg-theme w-100">
                Экспортировать заказы
            </button>

            <button class="btn btn-border btn-m btn-full mb-1 rounded-sm text-uppercase font-900 border-green1-dark color-green1-dark bg-theme w-100">
                Импорт из XLS
            </button>

            <button class="btn btn-border btn-m btn-full mb-1 rounded-sm text-uppercase font-900 border-green1-dark color-green1-dark bg-theme w-100">
                Экспорт в XLS
            </button>

            <div class="divider divider-small my-3 bg-highlight "></div>
            <button class="btn btn-m btn-full mb-3 rounded-sm text-uppercase font-900 shadow-s bg-red2-dark w-100">
                Удалить товары
            </button>
        </div>
    </div>

    <h6></h6>



    <ProductForm
        :item="selectedProduct"
        v-if="!load"
        v-on:refresh="refresh"
    />
    <ProductList
        v-on:select="selectProduct"
    />


</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            load: false,
            url: null,
            link: null,
            bot: null,
            selectedProduct: null,
        }
    },

    mounted() {
        this.updateProducts()
    },
    methods: {
        refresh() {
            this.load = true
            this.selectedProduct = null
            this.$nextTick(() => {
                this.load = false
            })
        },
        selectProduct(product) {
            this.load = true
            this.$nextTick(() => {
                this.selectedProduct = product
                console.log("Select!!", product)
                this.load = false
            })

        },
        updateProducts() {

            this.load = true
            this.$store.dispatch("updateProductsFromVk").then((resp) => {

                console.log(resp)
                this.link = resp.data.url
                this.load = false
                this.url = null
            }).catch(() => {
                this.load = false
            })

        }
    }
}
</script>
