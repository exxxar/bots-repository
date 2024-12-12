<script setup>
import Rating from "@/ClientTg/Components/V1/Shop/Helpers/Rating.vue";
import ReviewCard from "@/ClientTg/Components/V2/Shop/ReviewCard.vue";
</script>
<template v-if="item">

    <li class="list-group-item p-2 border-0 d-flex justify-content-between align-items-start">
        <div class="ms-2 w-100">
            <div
                @click="showProductDetails"
                class="fw-bold mb-1"
                style="font-size: 12px;"><i class="fa-solid fa-arrow-up-right-from-square"></i>
                {{ item.title.slice(0, 50) }} <span
                    v-if="item.title.length>50">...</span></div>

            <Rating :rating="item.rating"></Rating>

            <template v-if="collectionMode">
                <div
                    class="d-flex justify-content-between align-items-center px-2">
                    <button type="button"
                            @click="selectInCollection(item)"
                            style="font-size:12px;"
                            v-bind:class="{'btn-primary':item.is_checked,'btn-light':!item.is_checked}"
                            class="btn btn-md w-100 rounded-2 mb-2">
                        <span v-if="item.is_checked"><i class="fa-solid fa-check-double mr-2"></i> Выбрано</span>
                        <span v-else>Выбрать</span>
                    </button>
                </div>
            </template>

            <template v-else>
                <div
                    v-if="!item.in_stop_list_at"
                    class="d-flex justify-content-between align-items-center my-2">

                    <button type="button"
                            v-if="inCart(item.id)===0"
                            @click="incProductCart"
                            class="btn btn-sm btn-success  fw-bold w-100 rounded-2 p-2">
                        <i class="fa-solid fa-cart-plus"></i> В корзину {{ item.current_price || 0 }}₽
                        <span class="text-decoration-line-through" style="font-size:10px;"
                              v-if="item.old_price>0">{{ item.old_price || 0 }}₽</span>
                    </button>


                    <div class="btn-group w-100 rounded-3" v-if="inCart(item.id)>0">
                        <button type="button"
                                :disabled="item.in_stop_list_at"
                                style="border-radius:6px 0px 0px 6px;"
                                @click="decProductCart"
                                class="btn btn-sm btn-primary p-2">-
                        </button>
                        <button type="button" class="btn btn-sm btn-primary ">{{ checkInCart }}</button>
                        <button type="button"
                                :disabled="item.in_stop_list_at"
                                @click="incProductCart"
                                style="border-radius:0px 6px 6px 0px;"
                                class="btn btn-sm btn-primary p-2">+
                        </button>
                    </div>
                </div>
                <div v-else class="px-2">
                <span
                    style="font-size:12px;"
                    class="btn btn-outline-secondary rounded-3 py-2 btn-md w-100 d-flex justify-content-center align-items-center"><i
                    class="fa-solid fa-lock mr-2"></i> нет в наличии</span>
                </div>
            </template>
        </div>

        <div class="d-flex flex-column">
                <span class="badge text-bg-primary rounded-pill text-white fw-bold">
                     <i class="fa-regular fa-star text-white"></i> {{ item.rating || 0 }}
                </span>
                <span
                    v-if="item.old_price>0"
                    class="badge text-bg-primary rounded-pill text-white fw-bold mt-1">%
                </span>
        </div>


    </li>


    <!-- Modal -->
    <div class="modal fade" :id="'product-modal-info'+uuid" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Детали</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-0 bg-primary" v-if="item">
                        <div class="card-body">
                            <h6 class="text-left text-white" style="font-weight:700; line-height:100%;">
                                {{ (item.title || 'Не указан') }}</h6>

                            <div class="d-flex justify-content-between align-items-center">
                                <p class="text-left mb-0 text-white">Цена <strong
                                    class="text-white fw-bold">{{ item.current_price || 0 }}₽</strong></p>
                                <span
                                    v-if="discount>0"
                                    class="badge bg-primary fw-bold ">-{{ discount || 0 }}%</span>

                            </div>
                        </div>
                    </div>


                    <div class="p-2">
                        <p class="mb-0">Рейтинг товара</p>
                        <h6 class="d-flex justify-content-between mb-3">
                            <Rating :rating="item.rating"></Rating>
                            {{ item.rating }} из 5
                        </h6>

                    </div>


                    <ul class="nav nav-tabs justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link"
                               v-bind:class="{'active':tab===0}"
                               @click="tab=0"
                               aria-current="page"
                               href="javascript:void(0)">Описание</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               v-bind:class="{'active':tab===1}"
                               @click="loadReviews(0)"
                               href="javascript:void(0)">Отзывы</a>
                        </li>

                    </ul>

                    <div v-if="tab===0">
                        <p class="text-justify py-2 fst-italic">{{ item.description || '-' }}</p>
                    </div>

                    <div v-if="tab===1">


                        <div v-if="reviews.length>0">
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
                                v-if="paginate"
                                :pagination="paginate"/>
                        </div>
                        <div
                            v-if="reviews.length===0&&loading_reviews"
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
                            v-if="reviews.length===0&&!loading_reviews"
                            class="alert alert-light my-2" role="alert">
                            У данного товара еще нет отзывов! Для того чтобы оставить отзыв Вам необходимо заказать
                            данный товар, после чего у вас появится возможность написать ваше впечатление от него;)
                        </div>
                    </div>


                </div>
                <div class="modal-footer p-2 m-0"
                     v-if="!item.in_stop_list_at">
                    <div class="d-flex justify-content-between align-items-center w-100 p-0">
                        <button type="button"
                                v-if="inCart(item.id)===0"
                                @click="incProductCart"
                                class="btn btn-md btn-primary w-100 rounded-3 p-2">
                            {{ item.current_price || 0 }}₽
                            <span class="text-decoration-line-through" style="font-size:10px;" v-if="item.old_price>0">{{
                                    item.old_price || 0
                                }}₽</span>

                        </button>

                        <div class="btn-group w-100" v-if="inCart(item.id)>0">
                            <button type="button"
                                    :disabled="item.in_stop_list_at!=null"
                                    @click="decProductCart"
                                    class="btn btn-md btn-primary p-2">-
                            </button>
                            <button type="button" class="btn btn-md btn-primary">{{ checkInCart }}</button>
                            <button type="button"
                                    :disabled="item.in_stop_list_at!=null"
                                    @click="incProductCart"
                                    class="btn btn-md btn-primary p-2">+
                            </button>
                        </div>
                    </div>

                </div>
                <div class="modal-footer p-0 m-0"
                     v-else>
                    <p class="p-3 border-secondary border rounded-2 w-100 text-secondary fw-bold text-center">Товар
                        недоступен для заказа</p>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";
import {v4 as uuidv4} from "uuid";

export default {
    props: ["item", "displayType", "collectionMode", "canSelect"],
    data() {
        return {
            tab: 0,
            selected_image: null,
            loading_reviews: false,
            showCart: false,
            reviews: [],
            paginate: null,
        }
    },
    computed: {
        ...mapGetters(['inCart', 'getReviews', 'getReviewsPaginateObject']),
        currentPrice() {
            return this.item.current_price / 100
        },
        oldPrice() {
            return this.item.old_price / 100
        },
        checkInCart() {
            return this.inCart(this.item.id)
        },
        discount() {
            return this.oldPrice > 0 ? Math.round((this.currentPrice / this.oldPrice) * 100) : 0
        },
        uuid() {
            return uuidv4();
        }

    },
    mounted() {
        this.selected_image = (this.item.images || []).length > 0 ? this.item.images[0] : null
    },
    methods: {
        nextReviews(index) {
            this.loadReviews(index)
        },
        loadReviews(page = 0) {
            this.tab = 1
            this.loading_reviews = true
            return this.$store.dispatch("loadReviewsByProductId", {
                dataObject: {
                    product_id: this.item.id
                },
                page: page,
                size: 30
            }).then((resp) => {
                this.reviews = this.getReviews
                this.paginate = this.getReviewsPaginateObject
                this.loading_reviews = false

            }).catch(() => {
                this.loading_reviews = false
            })
        },
        showProductDetails() {
            const myModal = new bootstrap.Modal(document.getElementById('product-modal-info' + this.uuid), {})
            myModal.show()
        },
        goToProduct() {
            this.$router.push({name: 'ProductV2', params: {productId: this.item.id}})
        },
        incProductCart() {

            if (this.checkInCart === 0)
                this.$store.dispatch("addProductToCart", this.item)
            else
                this.$store.dispatch("incQuantity", this.item.id)

            this.$notify({
                title: "Добавление товара",
                text: 'Товар успешно добавлен',
                type: 'success'
            })
        },
        decProductCart() {

            if (this.checkInCart <= 1)
                this.$store.dispatch("removeProduct", this.item.id)
            else
                this.$store.dispatch("decQuantity", this.item.id)

            this.$notify({
                title: "Добавление товара",
                text: 'Товар успешно удален',
                type: 'success'
            })
        },
        selectInCollection(product) {
            if (this.canSelect)
                this.$emit("select-in-collection", product)
        }
    }
}
</script>
<style lang="scss">
.in-cart-count {
    padding: 4px;
    display: block;
    position: absolute;
    top: -10px;
    right: -10px;
    background: red;
    color: white;
    border-radius: 50%;
    width: 26px;
    height: 26px;
}

.product-card {
    min-height: 290px;

    img {
        object-fit: cover;
        /* height: 100%; */
        width: 100%;
        max-height: 190px;
        height: 190px;
    }

    .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 7px;

        .card-text {
            text-align: center;
            font-weight: 900;
        }
    }
}

.shadow-bg {
    background: #0000005e;
    padding: 5px;
}

.img-container {
    position: relative;
    display: block;

    img {
        position: relative;
        z-index: 1;
    }

    .controls {
        position: absolute;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 2;
    }
}

.rating {
    span {
        background: #00000069;
        padding: 5px 6px;
        border-radius: 5px;
        font-size: 10px;
    }
}
</style>
