<script setup>
import ProductForm from "@/ClientTg/Components/V2/Admin/Shop/ProductForm.vue";
import ProductList from "@/ClientTg/Components/V2/Admin/Shop/ProductList.vue";
import ProductCategoryList from "@/ClientTg/Components/V2/Admin/Shop/ProductCategoryList.vue";
import CollectionList from "@/ClientTg/Components/V2/Admin/Shop/CollectionList.vue";
</script>
<template>

    <div class="container py-3">
        <div class="row">



            <div class="col-12 py-2" style="position: sticky; top: 0px;z-index: 1000;">
                <div class="btn-group w-100 px-3 catalog-tabs py-2" style="overflow-x:auto;">
                    <button
                        type="button"
                        class="btn-info btn p-3"
                        @click="tab=0"
                        style="min-width:200px;line-height:100%;"
                        v-bind:class="{'active':tab===0}"
                        aria-current="page"><i class="fa-solid fa-users mr-2"></i>Товары
                    </button>
                    <button
                        type="button"
                        class="btn-info   btn p-3"
                        @click="tab=2"
                        style="min-width:200px;line-height:100%"
                        v-bind:class="{'active':tab===2}"
                    ><i class="fa-solid fa-user-secret mr-2"></i>Категории
                    </button>
                    <button
                        type="button"
                        class="btn-info  btn p-3"
                        @click="tab=3"
                        style="min-width:300px;line-height:100%;"
                        v-bind:class="{'active':tab===3}"
                    ><i class="fa-solid fa-box-open mr-2"></i> Подборки товара (комбо)
                    </button>
                    <button
                        type="button"
                        class="btn-info btn p-3"
                        @click="tab=4"
                        style="min-width:300px;line-height:100%;"
                        v-bind:class="{'active':tab===4}"
                        aria-current="page"><i class="fa-solid fa-arrows-rotate mr-2"></i>Обновление данных
                    </button>
                </div>
            </div>

            <div class="col-12" v-show="tab===0">
                <ProductList
                    v-if="!load"
                    v-on:select="selectProduct"
                />
            </div>

            <div class="col-12" v-show="tab===3">
                <CollectionList
                    v-if="!load"
                />
            </div>

            <div class="col-12" v-if="tab===1">
                <ProductForm
                    :item="selectedProduct"
                    v-if="!load"
                    v-on:refresh="refresh"
                />
            </div>

            <template v-if="tab===4">
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
                        @click="openUpdateModal"
                        v-if="link"
                        href="javascript:void(0)"
                        target="_blank"
                        class="btn btn-primary p-3 w-100 mb-2">
                        <i class="fa-brands fa-vk mr-2"></i> Обновить товар из ВК
                    </a>
                </div>

                <div class="col-12" v-if="currentBot.iiko">
                    <a
                        @click="goTo('IikoV2')"
                        href="javascript:void(0)"
                        class="btn btn-primary p-3 w-100 my-2">
                        <i class="fa-solid fa-gears mr-2"></i> Обновить товар из IIKO
                    </a>
                </div>

                <div class="col-12">
                    <button
                        :disabled="true"
                        @click="exportOrders"
                        class="btn btn-success p-3 w-100 mb-2">
                        <i class="fa-solid fa-lock mr-2"></i> Экспортировать заказы
                    </button>

                    <button
                        :disabled="true"
                        @click="importProducts"
                        class="btn btn-success p-3 w-100 mb-2">
                        <i class="fa-solid fa-lock mr-2"></i> Импорт из XLS
                    </button>

                    <button

                        @click="exportProducts"
                        class="btn btn-success p-3 w-100 mb-2">
                        Экспорт в XLS
                    </button>


                </div>
            </template>


            <div class="col-12" v-show="tab===2">
                <ProductCategoryList></ProductCategoryList>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="update-products-modal"
         data-bs-backdrop="static"
         tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-body">
                    <h6 class="text-center my-3">Вы действительно хотите обновить товар?</h6>
                    <p class="alert alert-warning mb-2">При обновлении будут загружены все товары из вк, текущие ваши товары будут удалены и заменены на новые.</p>
                    <div class="d-flex justify-content-center">
                        <button type="button"
                                style="margin-right:10px;"
                                class="btn btn-primary px-3 mr-2" @click="doUpdateProducts">Да</button>
                        <button type="button" class="btn btn-secondary px-3" @click="hideUpdateModal">Нет</button>
                    </div>

                </div>

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
            update_products_modal: null,
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
        currentBot(){
            return window.currentBot
        }
    },
    mounted() {
        this.updateProducts()
        this.botForm.vk_shop_link = window.currentBot.vk_shop_link || null

        this.update_products_modal = new bootstrap.Modal(document.getElementById('update-products-modal'), {})

        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })
    },
    methods: {
        doUpdateProducts(){
            this.exportProducts()
            this.open(this.link)
        },
        openUpdateModal(){
          this.update_products_modal.show();
        },
        hideUpdateModal(){
            this.update_products_modal.hide();
        },
        goTo(name) {
            this.$router.push({name: name})
        },
        updateShopLink() {
            this.load = true
            this.$store.dispatch("updateShopLink",{
                botForm: this.botForm
            }).then((resp) => {
                this.load = false
                this.updateProducts()

                this.$notify({
                    title:'Менеджер магазина',
                    text: "Ссылка на источник в ВК обновлена",
                    type:'success'
                })

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

                this.$notify({
                    title:'Менеджер магазина',
                    text: "Все продукты удалены",
                    type:'success'
                })

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
        exportOrders(){
            this.$store.dispatch("exportAllOrders").then((resp) => {
                this.load = false

                this.$notify({
                    title:'Менеджер магазина',
                    text: "Все заказы экспортированы в файл",
                    type:'success'
                })

            }).catch(() => {
                this.load = false
            })
        },
        exportProducts(){
            this.$store.dispatch("exportAllProducts").then((resp) => {
                this.load = false

                this.$notify({
                    title:'Менеджер магазина',
                    text: "Все продукты экспортированы в файл",
                    type:'success'
                })

            }).catch(() => {
                this.load = false
            })
        },
        importProducts(){
            this.$notify({
                title:'Менеджер магазина',
                text: "Функция недоступна",
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
