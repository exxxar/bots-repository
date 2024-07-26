<script setup>
import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
</script>
<template>
    <form class="input-group mb-2"
          v-on:submit.prevent="loadProductCategories(0)">

        <div class="form-floating">
            <input type="search"
                   v-model="search"
                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput">Поиск категории товара</label>
        </div>

        <button class="btn btn-outline-light text-primary"
                type="submit"
                id="button-addon2">
            Найти
        </button>
    </form>

    <div class="form-floating my-2">
        <select
            @change="loadOrders(0)"
            v-model="sort.param"
            class="form-select" id="floatingSelect" aria-label="Floating label select example">
            <option value="id">По номеру заказа</option>
            <option value="is_cashback_crediting">По начислению CashBack</option>
            >
            <option value="summary_price">По цене заказа</option>
            <option value="product_count">По числу товара в заказе</option>
            <option value="updated_at">По дате добавления</option>
        </select>
        <label for="floatingSelect">Сортировать заказы по</label>
    </div>
    <p v-if="sort.param!=null">Направление сортировки:
        <span
            class="fw-bold"
            @click="changeDirection('desc')"
            v-if="sort.direction==='asc'">по возрастанию <i class="fa-solid fa-caret-up"></i></span>
        <span
            class="fw-bold"
            @click="changeDirection('asc')"
            v-if="sort.direction==='desc'">по убыванию <i class="fa-solid fa-caret-down"></i></span>
    </p>

    <p>Всего категорий: <span v-if="paginate">{{ paginate.meta.total || 0 }}</span></p>
    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-2">
        <div class="col" v-for="(item, index)  in filteredProductCategories">
            <div
                data-bs-toggle="modal"
                data-bs-target="#product-form-edit"
                @click="selectProductCategory(item)"
                class="card">
                <div class="card-body">
                    {{ item.title }}

                    <input type="number"
                           @change="updateCategory(item)"
                           v-model="item.order_position"
                           class="form-control">

                    <button
                        title="Удалить товар"
                        @click="removeProductCategory(item)"
                        class="btn btn-outline-danger">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
                <div class="card-footer">
                    <button type="button"
                            @click="changeCategoryStatus(item)"
                            class="btn btn-outline-success w-100">
                        <span v-if="item.is_active">Активная</span>
                        <span v-else>Не активная</span>
                    </button>
                </div>
            </div>
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
    data() {
        return {
            search: null,
            categories: [],
            paginate: null,
            sort: {
                param: null,
                direction: 'asc'
            },
        }
    },
    computed: {
        ...mapGetters(['getCategories', 'getCategoriesPaginateObject']),
        bot() {
            return window.currentBot
        },
        filteredProductCategories() {

            if (!this.search)
                return this.categories.sort((a, b) =>
                    this.sort.direction === "asc" ?
                        a.order_position - b.order_position :
                        b.order_position - a.order_position)

            return this.categories.filter(category => category.title.toLowerCase().trim().indexOf(this.search.toLowerCase().trim()) >= 0)
                .sort((a, b) =>
                    this.sort.direction === "asc" ?
                        a.order_position - b.order_position :
                        b.order_position - a.order_position)
        },
    },
    mounted() {
        this.loadProductCategories()

    },
    methods: {
        changeDirection(direction) {
            this.sort.direction = direction
            this.loadProductCategories(0)
        },
        updateCategory(item) {
            console.log("update", item)

            this.$store.dispatch("storeProductCategory", {
                category: item,
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
            return this.$store.dispatch("loadCategories", {
                dataObject: {
                    bot_id: this.bot.id,
                    search: this.search
                },
                page: page
            }).then(() => {
                this.categories = this.getCategories
                this.paginate = this.getCategoriesPaginateObject
            })
        }
    }
}
</script>

