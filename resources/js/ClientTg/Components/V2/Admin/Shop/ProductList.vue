<script setup>
import ProductCard from "@/ClientTg/Components/V2/Admin/Shop/ProductCard.vue";
import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
</script>
<template>
    <form
        v-on:submit.prevent="loadProducts(0)">

        <div class="input-group mb-2">

            <div class="form-floating">
                <input type="text"
                       class="form-control border-light"
                       placeholder="Поиск товара"
                       aria-label="Поиск товара"
                       v-model="search"
                       aria-describedby="button-addon2">
                <label for="floatingInput">Критерии поиска товара</label>
            </div>


            <button class="btn btn-outline-light text-primary"
                    type="submit"
                    id="button-addon2">
                Найти
            </button>
        </div>




    </form>

    <div class="form-floating my-2">
        <select
            @change="loadProducts(0)"
            v-model="sort.param"
            class="form-select" id="floatingSelect" aria-label="Floating label select example">
            <option value="id">По номеру товара</option>
            <option value="title">По названию</option>
            >
            <option value="description">По описанию</option>
            <option value="price">По цене</option>
            <option value="old_price">По наличию скидки</option>
            <option value="rating">По рейтингу</option>
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

    <p>Всего товаров: <span v-if="paginate">{{paginate.meta.total || 0}}</span></p>

    <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-2">
        <div class="col"  v-for="(product, index) in filteredProducts">
            <ProductCard
                v-on:select="selectProduct(product)"
                :item="product"/>
        </div>
    </div>


    <Pagination
        :simple="true"
        v-on:pagination_page="nextProducts"
        v-if="paginate"
        :pagination="paginate"/>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            search: null,
            products: [],
            paginate: null,
            sort: {
                param: null,
                direction: 'asc'
            },
        }
    },
    computed: {
        ...mapGetters(['getProducts', 'getProductsPaginateObject']),
        filteredProducts() {
            return this.products

            //return this.products.filter(product => product.title.toLowerCase().trim().indexOf(this.search.toLowerCase().trim()) >= 0)
        },
    },
    mounted() {
        this.loadProducts()

    },
    methods: {
        changeDirection(direction) {
            this.sort.direction = direction
            this.loadOrders(0)
        },
        selectProduct(product) {
            console.log("product", product)
            this.$emit("select", product)
        },
        nextProducts(index) {
            this.loadProducts(index)
        },
        loadProducts(page = 0) {
            return this.$store.dispatch("loadProducts", {
                dataObject: {
                    search: this.search,
                    direction: this.sort.direction,
                    order_by: this.sort.param
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

