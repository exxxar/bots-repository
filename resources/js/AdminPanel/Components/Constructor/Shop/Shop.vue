<script setup>
import ProductForm from "@/AdminPanel/Components/Constructor/Shop/ProductForm.vue";
import ProductList from "@/AdminPanel/Components/Constructor/Shop/ProductList.vue";
</script>
<template>
    <div class="card mb-2">
        <div class="card-header">
            <h6>Управление магазином</h6>
        </div>
        <div class="card-body">
            <div class="row" v-if="tab===1">
                <div class="col-12">
                    <form v-on:submit.prevent="updateProducts">
                        <div class="input-group mb-3">
                            <button
                                :disabled="load"
                                class="btn btn-outline-secondary"
                                type="sub,it" id="button-addon2">Обновить товары
                            </button>
                        </div>
                    </form>

                    <div
                        v-if="load"
                        class="alert alert-primary" role="alert">
                        Внимание! Идет подготовка ссылки для вашей авторизации в ВК
                    </div>

                    <div
                        v-if="link"
                        class="alert alert-primary" role="alert">
                        Для обновления товаров из ВК нажмие на эту кнопку <a class="btn btn-link" :href="link"
                                                                             target="_blank">ТЫЦ</a>
                    </div>


                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3">
                    <button
                        type="button"
                        @click="tab=1"
                        v-bind:class="{'btn-info text-white':tab===1}"
                        class="btn btn-outline-info w-100">Обновить товар из ВК
                    </button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-info w-100">Экспорт в XLS</button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-info w-100">Импорт из XLS</button>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-danger w-100">Удалить товары</button>
                </div>

            </div>
            <div class="row">
                <div class="col-md-3">
                    <button class="btn btn-outline-info w-100">Экспортировать заказы</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h6>Работа с товаром</h6>
        </div>
        <div class="card-body">
            <ProductForm :bot="bot"
                         :item="selectedProduct"
                         v-on:refresh="refresh"
                         v-if="bot&&!load"/>
        </div>
    </div>
    <div class="card mt-2">
        <div class="card-body">
            <ProductList
                :bot="bot"
                v-on:select="selectProduct"
                v-if="bot&&!load"/>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            load: false,
            tab: 0,
            url: null,
            link: null,
            bot: null,
            selectedProduct: null,
        }
    },
    computed: {
        ...mapGetters(['getCurrentBot']),

    },
    mounted() {
        this.bot = this.getCurrentBot
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
                this.load = false
            })

            console.log(product)
        },
        updateProducts() {

            this.load = true
            this.$store.dispatch("updateProductsFromVk", {
                dataObject: {
                    botDomain: this.getCurrentBot.bot_domain
                }
            }).then((resp) => {

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
