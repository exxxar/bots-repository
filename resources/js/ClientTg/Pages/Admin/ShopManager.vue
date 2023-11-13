<script setup>
import ProductForm from "@/ClientTg/Components/Admin/Shop/ProductForm.vue";
import ProductList from "@/ClientTg/Components/Admin/Shop/ProductList.vue";
</script>
<template>

    <div class="card card-style">
        <div class="content mb-0">
            <h3 class="font-700">Управление магазином</h3>

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
                    class="btn btn-m btn-full mb-3 mt-2 rounded-sm text-uppercase font-900 shadow-s bg-red2-dark w-100">
                    <i class="fa-regular fa-floppy-disk mr-2"></i> Сохранить
                </button>

            </form>
            <div class="divider divider-small my-3 bg-highlight "></div>
            <p
                v-if="!link"
               class="btn btn-border btn-m btn-full mb-1 rounded-sm text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme w-100">
                <i class="fa-brands fa-vk mr-2"></i>  Подготовка ссылки
                <span class="spinner-border color-blue2-dark font-12 ml-2" style="border-width: 2px; width: 1rem;height: 1rem;" role="status"></span>
            </p>
            <a
                @click="open(link)"
                v-if="link"
                href="javascript:void(0)"
                type="button"
                class="btn btn-border btn-m btn-full mb-1 rounded-sm text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme w-100">
                <i class="fa-brands fa-vk mr-2"></i> Обновить товар из ВК
            </a>

            <button
                @click="exportOrders"
                class="btn btn-border btn-m btn-full mb-1 rounded-sm text-uppercase font-900 border-green1-dark color-green1-dark bg-theme w-100">
                <i class="fa-solid fa-lock mr-2"></i> Экспортировать заказы
            </button>

            <button
                @click="importProducts"
                class="btn btn-border btn-m btn-full mb-1 rounded-sm text-uppercase font-900 border-green1-dark color-green1-dark bg-theme w-100">
                <i class="fa-solid fa-lock mr-2"></i> Импорт из XLS
            </button>

            <button
                @click="exportProducts"
                class="btn btn-border btn-m btn-full mb-1 rounded-sm text-uppercase font-900 border-green1-dark color-green1-dark bg-theme w-100">
                <i class="fa-solid fa-lock mr-2"></i> Экспорт в XLS
            </button>

            <div class="divider divider-small my-3 bg-highlight "></div>
            <button
                @click="removeAllProducts"
                :disabled="load"
                class="btn btn-m btn-full mb-3 rounded-sm text-uppercase font-900 shadow-s bg-red2-dark w-100">
                <i class="fa-solid fa-trash mr-2"></i> Удалить товары
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
        v-if="!load"
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
        exportOrders(){
            this.$botNotification.notification("Менеджер магазина", "Функция недоступна");
        },
        exportProducts(){
            this.$botNotification.notification("Менеджер магазина", "Функция недоступна");
        },
        importProducts(){
            this.$botNotification.notification("Менеджер магазина", "Функция недоступна");
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
