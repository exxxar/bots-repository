<script setup>
import ProductCard from "@/AdminPanel/Components/Constructor/Shop/ProductCard.vue";
import Pagination from "@/AdminPanel/Components/Pagination.vue";
</script>
<template>
    <form class="input-group mb-3"
          v-on:submit.prevent="loadProducts(0)">
        <input type="text"
               class="form-control border-info"
               placeholder="Поиск товара"
               aria-label="Поиск товара"
               v-model="search"
               aria-describedby="button-addon2"
               required>
        <button class="btn btn-outline-info"
                type="submit"
                id="button-addon2">
           Найти
        </button>
    </form>
    <p>Всего товаров: <span v-if="paginate">{{paginate.meta.total || 0}}</span></p>
    <div class="row">

        <div class="col-12">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Название</th>
                    <th scope="col">Картинка</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in filteredProducts">
                    <th scope="row">{{ item.id }}</th>
                    <td><a href="javascript:void(0)"
                           @click="selectProduct(item)"
                           data-bs-toggle="modal"
                           data-bs-target="#product-form-edit"></a>{{ item.title }}
                    </td>
                    <td>
                        <img
                            v-if="(item.images||[]).length>0"
                            :src="preparedImgUrl(item.images[0])"
                            style="width:100px;height:100px;object-fit:cover;"
                            class="d-block" alt="...">
                        <p v-else>Не добавлено</p>
                    </td>
                    <td>
                        <div class="d-flex">
                            <button
                                title="Дублировать товар"
                                @click="duplicateProduct"
                                class="btn btn-outline-info mr-1">
                                <i class="fa-regular fa-copy"></i>
                            </button>

                            <button
                                title="Удалить товар"
                                @click="removeProduct"
                                class="btn btn-outline-danger">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>

    </div>


    <Pagination
        v-on:pagination_page="nextProducts"
        v-if="paginate"
        :pagination="paginate"/>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["bot"],
    data() {
        return {
            search: null,
            products: [],
            paginate: null,
        }
    },
    computed: {
        ...mapGetters(['getProducts', 'getProductsPaginateObject']),
        filteredProducts() {

            if (!this.search)
                return this.products

            return this.products.filter(product => product.title.toLowerCase().trim().indexOf(this.search.toLowerCase().trim()) >= 0)
        },
    },
    mounted() {
        this.loadProducts()

    },
    methods: {
        selectProduct(product) {
            this.$emit("select", product)
        },
        nextProducts(index) {
            this.loadProducts(index)
        },
        preparedImgUrl(url) {
            if (url.toLocaleLowerCase().startsWith("https://") || url.toLocaleLowerCase().startsWith("http://"))
                return url;

            return "/images-by-bot-id/"+this.bot.id+"/"+url
        },
        removeProduct(item){
            this.$store.dispatch("removeProduct", item.id).then((response) => {

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Продукт успешно удален!",
                    type: 'success'
                });
            }).catch(err => {

            })
        },
        duplicateProduct(item){
            this.$store.dispatch("duplicateProduct", item.id).then((response) => {

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Продукт успешно продублирован!",
                    type: 'success'
                });
            }).catch(err => {

            })
        },
        loadProducts(page = 0) {
            return this.$store.dispatch("loadProducts", {
                dataObject: {
                    bot_id: this.bot.id,
                    search: this.search
                },
                page: page
            }).then(() => {
                this.products = this.getProducts
                this.paginate = this.getProductsPaginateObject
            })
        }
    }
}
</script>

