<script setup>
import ProductForm from "@/ClientTg/Components/V2/Admin/Shop/ProductForm.vue";
import ProductList from "@/ClientTg/Components/V2/Admin/Shop/ProductList.vue";
import ProductCategoryList from "@/ClientTg/Components/V2/Admin/Shop/ProductCategoryList.vue";
</script>
<template>

    <div class="container py-3">
        <div class="row">
            <div class="col-12">
                <form
                    v-on:submit.prevent="updateShopLink"
                    class="mb-2">


                    <div class="form-floating mb-2">
                        <input type="url" class="form-control"
                               placeholder="Ссылка на группу ВК"
                               aria-label="ссылка на группу ВК"
                               v-model="botForm.vk_shop_link"
                               aria-describedby="vk_shop_link">
                        <label for="floatingInput">Ссылка на страницу ВК с товарами</label>
                    </div>


                    <button
                        :disabled="load"
                        class="btn btn-outline-info w-100 p-3">
                        <i class="fa-regular fa-floppy-disk mr-2"></i> Сохранить
                    </button>

                </form>

                <p
                    v-if="!link"
                    class="btn btn-outline-warning p-3 mb-1 w-100">
                    <i class="fa-brands fa-vk mr-2"></i>Подготовка ссылки
                    <span class="spinner-border text-warning ml-2" style="border-width: 2px; width: 1rem;height: 1rem;" role="status"></span>
                </p>
                <a
                    @click="open(link)"
                    v-if="link"
                    href="javascript:void(0)"
                    type="button"
                    class="btn btn-primary p-3 w-100">
                    <i class="fa-brands fa-vk mr-2"></i> Обновить товар из ВК
                </a>
            </div>

            <div class="col-12 py-2">
                <ul class="nav nav-tabs justify-content-center catalog-tabs">
                    <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            @click="tab=0"
                            style="font-weight:bold;"
                            v-bind:class="{'active':tab===0}"
                            aria-current="page"><i class="fa-solid fa-users mr-2"></i>Товары
                        </button>
                    </li>
                    <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            @click="tab=2"
                            style="font-weight:bold;"
                            v-bind:class="{'active':tab===2}"
                        ><i class="fa-solid fa-user-secret mr-2"></i>Категории
                        </button>
                    </li>


                </ul>
            </div>

            <div class="col-12" v-show="tab===0">
                <ProductList
                    v-if="!load"
                    v-on:select="selectProduct"
                />
            </div>

            <div class="col-12" v-show="tab===1">
                <ProductForm
                    :item="selectedProduct"
                    v-if="!load"
                    v-on:refresh="refresh"
                />
            </div>

            <div class="col-12" v-show="tab===2">
                <ProductCategoryList></ProductCategoryList>
            </div>
        </div>
    </div>









</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            tab:0,
            load: false,
            url: null,
            link: null,
            bot: null,
            selectedProduct: null,
            botForm: {
                vk_shop_link: null,
            }
        }
    },
    computed:{
        tg() {
            return window.Telegram.WebApp;
        },
    },
    mounted() {
        this.updateProducts()
        this.botForm.vk_shop_link = window.currentBot.vk_shop_link || null

        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })
    },
    methods: {
        updateShopLink() {
            this.load = true
            this.$store.dispatch("updateShopLink",{
                botForm: this.botForm
            }).then((resp) => {
                this.load = false
                this.updateProducts()
                this.$botNotification.notification("Менеджер магазина", "Ссылка на источник в ВК обновлена");
            }).catch(() => {
                this.load = false
            })
        },
        refresh() {
            this.load = true
            this.selectedProduct = null
            this.$nextTick(() => {
                this.load = false
            })
        },
        removeAllProducts() {
            this.load = true
            this.$store.dispatch("removeAllProducts").then((resp) => {
                this.load = false
                this.$botNotification.notification("Менеджер магазина", "Все продукты удалены");
            }).catch(() => {
                this.load = false
            })
        },
        selectProduct(product) {
            this.load = true
            this.$nextTick(() => {
                this.selectedProduct = product
                this.load = false
            })

        },
        updateProducts() {

            this.load = true
            this.$store.dispatch("updateProductsFromVk").then((resp) => {
                this.link = resp.data.url || null
                this.load = false
                this.url = null
            }).catch(() => {
                this.load = false
            })

        },
        open(url) {
            this.tg.openLink(url)
        },
    }
}
</script>
<style lang="scss">
.scrolled-area {
    overflow-x:auto;
    display: flex;

    padding: 5px 5px;

    .nav {
        width: auto;
        box-sizing: content-box;
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        align-items: center;
        .nav-item {
            min-width: 150px;
            button{
                width: 100%;
            }
        }
    }
}
</style>
