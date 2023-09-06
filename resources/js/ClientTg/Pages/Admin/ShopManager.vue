<script setup>
import ProductForm from "@/ClientTg/Components/Admin/Shop/ProductForm.vue";
import ProductList from "@/ClientTg/Components/Admin/Shop/ProductList.vue";
</script>
<template>

    <div class="card card-style">
        <div class="content mb-0">
            <h3 class="font-700">Управление магазином</h3>
            <p class="mt-n2 mb-4">
                Three levels of roundness, small, medium and large.
            </p>
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



            <button
                type="button"
                @click="tab=1"
                v-bind:class="{'btn-info text-white':tab===1}"
                class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-blue2-dark w-100">
                Обновить товар из ВК
            </button>

            <button class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-blue2-dark w-100">
                Экспортировать заказы
            </button>

            <button class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-blue2-dark w-100">
                Импорт из XLS
            </button>

            <button class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-blue2-dark w-100">
                Экспорт в XLS
            </button>

            <button class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-blue2-dark w-100">
                Удалить товары
            </button>
        </div>
    </div>

    <h6></h6>



    <ProductForm
        :item="selectedProduct"
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
            tab: 0,
            url: null,
            link: null,
            bot: null,
            selectedProduct: null,
        }
    },

    mounted() {

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
