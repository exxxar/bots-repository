<script setup>
import ProductForm from "@/AdminPanel/Components/Constructor/Shop/ProductForm.vue";
import ProductTableList from "@/AdminPanel/Components/Constructor/Shop/ProductTableList.vue";
import ProductCategoryTableList from "@/AdminPanel/Components/Constructor/Shop/ProductCategoryTableList.vue";
</script>
<template>

    <ul class="nav nav-tabs my-3" >
        <li class="nav-item">
            <a class="nav-link"
               @click="tab=0"
               v-bind:class="{'active':tab===0}"
               aria-current="page" href="#">Товары</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"
               @click="tab=3"
               v-bind:class="{'active':tab===3}"
               href="#">Категории товаров</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"
               @click="tab=1"
               v-bind:class="{'active':tab===1}"
               href="#">Настройка&Обновление</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"
               @click="tab=2"
               v-bind:class="{'active':tab===2}"
               href="#">Заказы</a>
        </li>
    </ul>

    <div v-if="tab===1">
        <div class="card mb-2">
            <div class="card-header">
                <h6>Управление магазином</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form
                            v-on:submit.prevent="updateShopLink"
                            class=" mb-2">


                            <label class="form-label d-flex justify-content-between mt-2" id="bot-level-3">
                                <div>
                                    <Popper>
                                        <i class="fa-regular fa-circle-question mr-1"></i>
                                        <template #content>
                                            <div>Ссылка на страницу ВК с товарами для вашего магазина в боте
                                            </div>
                                        </template>
                                    </Popper>
                                    ВК-группа
                                </div>

                                <a href="https://vk.com/groups?w=groups_create" target="_blank">Создать</a>
                            </label>


                            <input type="url" class="form-control"
                                   placeholder="Ссылка на группу ВК"
                                   aria-label="ссылка на группу ВК"
                                   v-model="botForm.vk_shop_link"
                                   aria-describedby="vk_shop_link">

                            <button
                                :disabled="load"
                                class="btn btn-outline-primary mt-2 mb-2 w-100">
                                <i class="fa-regular fa-floppy-disk mr-2"></i> Сохранить
                            </button>

                        </form>
                    </div>
                    <!--                <div class="col-12">
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


                                    </div>-->
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <a
                            target="_blank"
                            :href="link"
                            class="btn btn-primary w-100">
                            Обновить товар
                        </a>

                        <!--                    <button
                                                type="button"
                                                @click="tab=1"
                                                v-bind:class="{'btn-info text-white':tab===1}"
                                                class="btn btn-outline-info w-100">Обновить товар из ВК
                                            </button>-->
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
    </div>

    <div v-if="tab===0">
        <ProductTableList
            :bot="bot"
            v-on:select="selectProduct"
            v-if="bot&&!load"/>

        <div class="modal fade" id="product-form-edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ProductForm :bot="bot"
                                     :item="selectedProduct"
                                     v-on:refresh="refresh"
                                     v-if="bot&&!load"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="tab===2">

    </div>

    <div v-if="tab===3">
        <ProductCategoryTableList
            :bot="bot"
            v-on:select="selectProductCategory"
            v-if="bot&&!load"
        ></ProductCategoryTableList>
    </div>

<!--    <div class="card">
        <div class="card-header">
            <h6>Работа с товаром</h6>
        </div>
        <div class="card-body">
            <ProductForm :bot="bot"
                         :item="selectedProduct"
                         v-on:refresh="refresh"
                         v-if="bot&&!load"/>
        </div>
    </div>-->



    <!-- Modal -->

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
            selectedCategory: null,
            botForm: {
                vk_shop_link: null,
            }
        }
    },
    computed: {
        ...mapGetters(['getCurrentBot']),

    },
    mounted() {
        this.bot = this.getCurrentBot
        this.botForm.vk_shop_link = this.bot.vk_shop_link || null
        this.updateProducts()
    },
    methods: {
        updateShopLink() {
            this.load = true
            this.botForm.bot_id = this.bot.id
            this.$store.dispatch("updateShopLink",{
                botForm: this.botForm
            }).then((resp) => {
                this.load = false
                this.updateProducts()
                this.$notify({
                    title: "Менеджер магазина",
                    text: "Ссылка на источник в ВК обновлена"
                });
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
        selectProduct(product) {
            this.load = true
            this.$nextTick(() => {
                this.selectedProduct = product
                this.load = false
            })
        },
        selectProductCategory(category){
            this.load = true
            this.$nextTick(() => {
                this.selectedCategory = category
                this.load = false
            })
        },
        updateProducts() {

            this.load = true
            this.$store.dispatch("updateProductsFromVk", {
                dataObject: {
                    bot_domain: this.getCurrentBot.bot_domain
                }
            }).then((resp) => {

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
