<script setup>
import ProductCard from "@/ClientTg/Components/V2/Admin/Shop/AdminProductCard.vue";
import ProductForm from "@/ClientTg/Components/V2/Admin/Shop/ProductForm.vue";
import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
import Rating from "@/ClientTg/Components/V1/Shop/Helpers/Rating.vue";
import ReviewCard from "@/ClientTg/Components/V2/Shop/ReviewCard.vue";
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

    <p>Всего товаров: <span v-if="paginate">{{ paginate.meta.total || 0 }}</span></p>

    <div
        v-if="!isSimple"
        class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-2">
        <div class="col" v-for="(product, index) in filteredProducts">
            <ProductCard
                :key="'product-'+product.id"
                v-bind:class="{'selected':(selected||[]).indexOf(product.id)!=-1}"
                v-on:select="selectProduct(product)"
                :item="product"/>
        </div>
    </div>
    <div
        class="row"
        v-else>
        <div class="col-12">

            <ul class="list-group">
                <li
                    class="list-group-item d-flex justify-content-between align-items-center"
                    @click="selectProduct(product)"
                    v-bind:class="{'bg-success text-white fw-bold':(selected||[]).indexOf(product.id)!=-1}"
                    v-for="(product, index) in filteredProducts">
                    {{ product.title }}
                </li>
            </ul>

        </div>
    </div>


    <Pagination
        :simple="true"
        v-on:pagination_page="nextProducts"
        v-if="paginate"
        :pagination="paginate"/>


    <!-- Modal -->
    <div class="modal fade"
         id="product-modal-admin-info" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                    <button type="button" class="btn-close" @click="hideModal"></button>
                </div>
                <div class="modal-body">

                    <template v-if="selected_product!=null">
                        <div class="card border-0">
                            <img
                                style="min-height:350px;object-fit:contain;"
                                v-lazy="selected_image"
                                class="card-img" alt="..."/>

                            <div class="card-img-overlay d-flex flex-column justify-content-between p-0">
                                <div class="shadow-bg">
                                    <h6 class="text-left text-white" style="font-weight:700; line-height:100%;">
                                        {{ (selected_product.title || 'Не указан') }}</h6>
                                    <p class="text-left mb-0 text-white">Цена <strong
                                        class="text-primary fw-bold">{{ selected_product.current_price || 0 }}₽</strong>
                                    </p>
                                </div>


                            </div>
                        </div>

                        <div class="row g-2 row-cols-5" v-if="selected_product.images.length>1">
                            <div class="col" v-for="img in selected_product.images">
                                <img
                                    style="min-height:60px;object-fit:contain;"
                                    @click="selected_image = img"
                                    v-bind:class="{'border border-primary':selected_image===img}"
                                    v-lazy="img"
                                    class="img-thumbnail my-2" alt="...">
                            </div>
                        </div>

                        <div class="p-2">
                            <p class="mb-0">Рейтинг товара</p>
                            <h6 class="d-flex justify-content-between mb-3">
                                <Rating :rating="selected_product.rating"></Rating>
                                {{ selected_product.rating }} из 5
                            </h6>

                        </div>

                        <ul class="nav nav-tabs justify-content-center">
                            <li
                                @click="tab=0"
                                class="nav-item">
                                <a class="nav-link"
                                   v-bind:class="{'active':tab===0}"

                                   aria-current="page"
                                   href="javascript:void(0)">Описание</a>
                            </li>
                            <li
                                @click="tab=2"
                                class="nav-item">
                                <a class="nav-link"
                                   v-bind:class="{'active':tab===2}"

                                   href="javascript:void(0)">Редактор</a>
                            </li>
                            <li
                                @click="tab=1"
                                class="nav-item">
                                <a class="nav-link"
                                   v-bind:class="{'active':tab===1}"

                                   href="javascript:void(0)">Отзывы</a>
                            </li>

                        </ul>

                        <div v-show="tab===0">
                            <p class="text-justify py-2 fst-italic">{{ selected_product.description || '-' }}</p>
                        </div>

                        <div v-show="tab===1">

                            <div v-if="(reviews||[]).length>0">
                                <ul class="list-group w-100 py-2 rounded-0">
                                    <li class="list-group-item" v-for="(review, index) in reviews">
                                        <ReviewCard
                                            :need-product="false"
                                            v-model="reviews[index]"></ReviewCard>
                                    </li>

                                </ul>

                                <Pagination
                                    :simple="true"
                                    v-on:pagination_page="nextReviews"
                                    v-if="review_paginate"
                                    :pagination="review_paginate"/>
                            </div>
                            <div
                                v-if="(reviews||[]).length===0&&loading_reviews"
                                class="alert alert-light my-2 d-flex justify-content-center align-items-center"
                                role="alert">
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <div class="spinner-grow text-primary my-3" role="status">
                                        <span class="visually-hidden">Загружаем....</span>
                                    </div>
                                    <p>Загружаем отзывы...</p>
                                </div>

                            </div>
                            <div
                                v-if="(reviews||[]).length===0&&!loading_reviews"
                                class="alert alert-light my-2" role="alert">
                                У данного товара еще нет отзывов! Для того чтобы оставить отзыв Вам необходимо заказать
                                данный товар, после чего у вас появится возможность написать ваше впечатление от него;)
                            </div>
                        </div>

                        <div v-show="tab===2">
                            <ProductForm
                                v-on:remove-product="openRemoveModal"
                                v-on:callback="loadProducts(0)"
                                :item="selected_product"
                            />
                        </div>
                    </template>


                </div>


            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="remove-modal"
         data-bs-backdrop="static"
         tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Удаление товара</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-center my-3">Вы действительно хотите удалить этот товар?</h6>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-primary px-3 mr-2" @click="removeProduct">Да</button>
                        <button type="button" class="btn btn-secondary px-3" @click="hideRemoveModal">Нет</button>
                    </div>

                </div>

            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["selected", "isSimple"],
    data() {
        return {
            tab: 0,
            selected_image: null,
            search: null,
            products: [],
            reviews: [],
            modal: null,
            accept_remove_modal: null,
            selected_product: null,
            paginate: null,
            review_paginate: null,
            loading_reviews: true,
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
        this.modal = new bootstrap.Modal(document.getElementById('product-modal-admin-info'), {})
        this.accept_remove_modal = new bootstrap.Modal(document.getElementById('remove-modal'), {})
    },
    methods: {
        openRemoveModal() {
            this.accept_remove_modal.show();
        },
        nextReviews(index) {
            this.loadReviews(index)
        },
        changeDirection(direction) {
            this.sort.direction = direction
            this.loadOrders(0)
        },
        loadReviews(page = 0) {
            // this.tab = 1
            this.loading_reviews = true
            return this.$store.dispatch("loadReviewsByProductId", {
                dataObject: {
                    product_id: this.selected_product.id
                },
                page: page,
                size: 30
            }).then((resp) => {
                this.reviews = this.getReviews
                this.review_paginate = this.getReviewsPaginateObject
                this.loading_reviews = false

            }).catch(() => {
                this.loading_reviews = false
            })
        },
        hideModal() {
            this.$nextTick(() => {
                this.selected_product = null
                this.modal.hide()
            })

        },
        hideRemoveModal() {
                this.accept_remove_modal.hide()
        },
        selectProduct(product) {

            this.selected_product = null

            this.$nextTick(() => {
                this.selected_product = product

                this.loadReviews()

                this.selected_image = this.selected_product.images[0] || null
                this.selected_image = this.selected_product.images[0] || null
                this.modal.show();
            })


            // this.$emit("select", product)
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
        },

        removeProduct() {

            this.$store.dispatch("removeShopProduct", this.selected_product.id)
                .then((resp) => {
                    this.hideModal()
                    this.hideRemoveModal()

                    this.loadProducts()

                    this.$notify({
                        title: 'Редактор товара',
                        text: 'Товар успешно удален',
                        type: 'success'
                    })

                }).catch(() => {
                this.hideModal()
                this.hideRemoveModal()
                this.loadProducts()
            })
        },
    }
}
</script>

