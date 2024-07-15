<script setup>
import Pagination from "@/AdminPanel/Components/Pagination.vue";
</script>
<template>
    <form class="input-group mb-3"
          v-on:submit.prevent="loadProductCategories(0)">
        <input type="text"
               class="form-control border-info"
               placeholder="Поиск категории товара"
               aria-label="Поиск категории товара"
               v-model="search"
               aria-describedby="button-addon2"
               required>
        <button class="btn btn-outline-info"
                type="submit"
                id="button-addon2">
            Найти
        </button>
    </form>
    <p>Всего категорий: <span v-if="paginate">{{ paginate.meta.total || 0 }}</span></p>
    <div class="row">

        <div class="col-12">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Название</th>
                    <th scope="col">Состояние</th>
                    <th scope="col">Позиция в выдаче</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item, index) in filteredProductCategories">
                    <th scope="row">{{ item.id }}</th>
                    <td><a href="javascript:void(0)"
                           @click="selectProductCategory(item)"
                           data-bs-toggle="modal"
                           data-bs-target="#product-form-edit"></a>{{ item.title }}
                    </td>

                    <td>
                        <button type="button"
                                @click="changeCategoryStatus(item)"
                                class="btn btn-outline-success">
                            <span v-if="item.is_active">Активная</span>
                            <span v-else>Не активная</span>
                        </button>
                    </td>


                    <td>
                        <input type="number"
                               @change="updateCategory(item)"
                               v-model="item.order_position"
                               class="form-control">
                    </td>

                    <td>


                        <button
                            title="Удалить товар"
                            @click="removeProductCategory(item)"
                            class="btn btn-outline-danger">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>

                    </td>
                </tr>

                </tbody>
            </table>
        </div>

    </div>


    <Pagination
        v-on:pagination_page="nextProductCategories"
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
            categories: [],
            paginate: null,
        }
    },
    computed: {
        ...mapGetters(['getProductCategories', 'getProductCategoriesPaginateObject']),
        filteredProductCategories() {

            if (!this.search)
                return this.categories

            return this.categories.filter(category => category.title.toLowerCase().trim().indexOf(this.search.toLowerCase().trim()) >= 0)
        },
    },
    mounted() {
        this.loadProductCategories()

    },
    methods: {
        updateCategory(item){
          console.log("update", item)

            this.$store.dispatch("storeProductCategory", {
                category:item,
                bot_id: this.bot.id
            }).then((response) => {

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Данные сохранены!",
                    type: 'success'
                });
            }).catch(err => {
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Ошибка сохранения данных!",
                    type: 'error'
                });
            })
        },
        changeCategoryStatus(item) {
            let index = this.categories.findIndex(category => item.id === category.id)
            if (index === -1)
                return;

            this.categories[index].is_active = !this.categories[index].is_active
            this.$store.dispatch("changeProductCategoryStatus", item.id).then((response) => {

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Статус категории успешно изменен!",
                    type: 'success'
                });
            }).catch(err => {

            })
        },
        selectProductCategory(product) {
            this.$emit("select", product)
        },
        nextProductCategories(index) {
            this.loadProductCategories(index)
        },

        removeProductCategory(item) {
            this.$store.dispatch("removeProductCategory", item.id).then((response) => {

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Продукт успешно удален!",
                    type: 'success'
                });
            }).catch(err => {

            })
        },

        loadProductCategories(page = 0) {
            return this.$store.dispatch("loadProductCategories", {
                dataObject: {
                    bot_id: this.bot.id,
                    search: this.search
                },
                page: page
            }).then(() => {
                this.categories = this.getProductCategories
                this.paginate = this.getProductCategoriesPaginateObject
            })
        }
    }
}
</script>

