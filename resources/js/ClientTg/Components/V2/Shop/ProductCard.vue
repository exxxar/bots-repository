<script setup>
import Rating from "@/ClientTg/Components/V1/Shop/Helpers/Rating.vue";
import ReviewCard from "@/ClientTg/Components/V2/Shop/ReviewCard.vue";
</script>
<template v-if="item">

    <div class="card  border-0 product-card">

        <div
            @click="showProductDetails"
            class="img-container">
            <img
                class="rounded-3"
                v-if="(item.images||[]).length>0"
                v-lazy="item.images[0]">
            <div class="controls">
                <div class="top d-flex justify-content-between w-100 align-items-center">
                    <div class="rating w-100 p-2">
                        <span class="text-white fw-bold"><i
                            class="fa-regular fa-star text-primary mr-1"></i> {{ item.rating || 0 }}</span>
                    </div>

                    <div class="terms p-2" v-if="item.delivery_terms">
                         <span class="text-white fw-bold">
                        <i class="fa-solid fa-clock text-primary"></i>
                         </span>
                    </div>
                    <span
                        v-if="item.old_price>0"
                        class="badge bg-primary mr-2 fw-bold">%</span>
                </div>

            </div>
        </div>

        <div class="card-body px-0">
            <p class="text-center mb-2" style="font-size: 12px;">{{ item.title.slice(0, 50) }} <span
                v-if="item.title.length>50">...</span></p>


            <!--            <h6 class="d-flex justify-content-center mb-3"><Rating :rating="item.rating"></Rating> </h6>-->


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
                    class="d-flex justify-content-between align-items-center px-2">

                    <button type="button"
                            v-if="inCart(item.id)===0"
                            @click="incProductCart"
                            style="font-size:12px;"
                            v-bind:class="{'btn-secondary':!canProductAction}"
                            :disabled="item.in_stop_list_at!=null|| !canProductAction"
                            class="btn btn-sm btn-light w-100 rounded-3">
                        {{ item.current_price || 0 }}₽
                        <span class="text-decoration-line-through" style="font-size:10px;"
                              v-if="item.old_price>0">{{ item.old_price || 0 }}₽</span>
                    </button>

                    <div class="btn-group w-100 rounded-3" v-if="inCart(item.id)>0">
                        <button type="button"
                                :disabled="item.in_stop_list_at!=null|| !canProductAction"
                                style="border-radius:6px 0px 0px 6px;"
                                v-bind:class="{'btn-secondary':!canProductAction}"
                                @click="decProductCart"
                                class="btn btn-sm btn-primary">-
                        </button>
                        <button type="button"
                                v-bind:class="{'btn-secondary':!canProductAction}"
                                class="btn btn-sm btn-primary ">{{ checkInCart }}
                        </button>
                        <button type="button"
                                v-bind:class="{'btn-secondary':!canProductAction}"
                                :disabled="item.in_stop_list_at!=null|| !canProductAction"
                                @click="incProductCart"
                                style="border-radius:0px 6px 6px 0px;"
                                class="btn btn-sm btn-primary">+
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


    </div>


    <!-- Modal -->
    <div class="modal fade" :id="'product-modal-info'+uuid" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-0" v-if="item">
                        <img
                            style="min-height:350px;object-fit:cover;"
                            v-lazy="selected_image"
                            class="card-img" alt="...">

                        <div class="card-img-overlay d-flex flex-column justify-content-between p-0">
                            <div class="shadow-bg" style="min-height:70px;">
                                <h6 class="text-left text-white" style="font-weight:700; line-height:100%;">
                                    {{ (item.title || 'Не указан') }}</h6>

                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="text-left mb-0 text-white">Цена <strong
                                        class="text-primary fw-bold">{{ item.current_price || 0 }}₽</strong></p>
                                    <span
                                        v-if="discount>0"
                                        class="badge bg-primary fw-bold ">-{{ discount || 0 }}%</span>

                                </div>

                            </div>


                        </div>
                    </div>

                    <div class="row g-2 row-cols-5" v-if="(item.images||[]).length>1">
                        <div class="col" v-for="img in item.images">
                            <img
                                style="min-height:60px;object-fit:contain;"
                                @click="selected_image = img"
                                v-bind:class="{'border border-primary':selected_image===img}"
                                v-lazy="img"
                                class="img-thumbnail my-2" alt="...">
                        </div>
                    </div>

                    <a href="javascript:void(0)"
                       @click="copyToClipBoard"
                       class="d-block w-100 text-center py-3"><i class="fa-solid fa-link"></i> Скопировать ссылку на товар</a>

                    <div class="p-2">
                        <p class="mb-0">Рейтинг товара</p>
                        <h6 class="d-flex justify-content-between mb-3">
                            <Rating :rating="item.rating"></Rating>
                            {{ item.rating }} из 5
                        </h6>

                    </div>


                    <div class="p-2 alert alert-warning"
                         v-if="item.delivery_terms">
                        <p class="mb-0 fw-bold">Особенности доставки</p>
                        <h6
                            v-if="item.delivery_terms"
                            class="d-flex justify-content-between mb-3">
                          {{item.delivery_terms}}
                        </h6>
                        <p v-else class="mb-0">Дополнительных условий доставки нет</p>

                    </div>

                    <div
                        v-if="item.dimension"
                        class="p-2">
                        <p class="mb-0">Параметры товара</p>
                        <table class="table">
                            <thead>
                            <th>Параметр</th>
                            <th>Значение</th>
                            </thead>
                            <tbody>
                            <tr v-if="item.dimension.width > 0">
                                <td>Ширина</td>
                                <td>{{ item.dimension.width || 0 }} см</td>
                            </tr>
                            <tr v-if="item.dimension.height > 0">
                                <td>Высота</td>
                                <td>{{ item.dimension.height || 0 }} см</td>
                            </tr>
                            <tr v-if="item.dimension.length > 0">
                                <td>Длина</td>
                                <td>{{ item.dimension.length || 0 }} см</td>
                            </tr>

                            <tr v-if="item.dimension.weight > 0">
                                <td>Вес</td>
                                <td>{{ item.dimension.weight || 0 }} кг</td>
                            </tr>
                            </tbody>
                        </table>
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
                        <p v-if="item.description" v-html="item.description"
                           class="text-justify py-2 fst-italic"></p>
                        <p v-else
                           class="text-justify py-2 fst-italic">Нет описания</p>
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
                                v-bind:class="{'btn-secondary':!canProductAction}"
                                :disabled="item.in_stop_list_at!=null|| !canProductAction"
                                @click="incProductCart"
                                class="btn btn-md btn-primary w-100 rounded-3 p-2">
                            {{ item.current_price || 0 }}₽
                            <span class="text-decoration-line-through" style="font-size:10px;" v-if="item.old_price>0">{{
                                    item.old_price || 0
                                }}₽</span>

                        </button>

                        <div class="btn-group w-100" v-if="inCart(item.id)>0">
                            <button type="button"
                                    v-bind:class="{'btn-secondary':!canProductAction}"
                                    :disabled="item.in_stop_list_at!=null|| !canProductAction"
                                    @click="decProductCart"
                                    class="btn btn-md btn-primary p-2">-
                            </button>
                            <button type="button"
                                    v-bind:class="{'btn-secondary':!canProductAction}"
                                    class="btn btn-md btn-primary">{{ checkInCart }}
                            </button>
                            <button type="button"
                                    v-bind:class="{'btn-secondary':!canProductAction}"
                                    :disabled="item.in_stop_list_at!=null|| !canProductAction"
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
            reviews: [],
            paginate: null,
            sending: false,
            is_online: true,
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
        },
        canProductAction() {
            return this.is_online && !this.sending
        },
        currentScriptId(){
          return window.currentScript
        },
        currentBot() {
            return window.currentBot
        },
        qr() {
            return "https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=" + this.productLink
        },
        productLink() {
            return "https://t.me/" + this.currentBot.bot_domain + "?start=" + btoa("001slug" + this.currentScriptId+"product"+this.item.id);
        }

    },
    mounted() {
        this.selected_image = (this.item.images || []).length > 0 ? this.item.images[0] : null

        window.addEventListener('online', () => {
            this.is_online = true
        });
        window.addEventListener('offline', () => {
            this.is_online = false
        });
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
            this.sending = true
            let incResult = this.checkInCart === 0 ?
                this.$store.dispatch("addProductToCart", this.item) :
                this.$store.dispatch("incQuantity", this.item.id)

            incResult.then(() => {
                this.sending = false
                this.$notify({
                    title: "Добавление товара",
                    text: 'Товар "'+this.item.title+'" успешно добавлен',
                    type: 'success'
                })
            }).catch(() => {
                this.sending = false
                this.$notify({
                    title: "Добавление товара",
                    text: 'Ошибка добавления товара!',
                    type: 'error'
                })
            })
        },
        decProductCart() {
            this.sending = true
            let decResult = this.checkInCart <= 1 ?
                this.$store.dispatch("removeProduct", this.item.id) :
                this.$store.dispatch("decQuantity", this.item.id)

            decResult.then(() => {
                this.sending = false
                this.$notify({
                    title: "Удаление товара",
                    text:  'Товар "'+this.item.title+'" успешно убран из корзины',
                    type: 'success'
                })
            }).catch(() => {
                this.sending = false
                this.$notify({
                    title: "Удаление товара",
                    text: 'Ошибка удаления товара!',
                    type: 'error'
                })
            })
        },
        selectInCollection(product) {
            if (this.canSelect)
                this.$emit("select-in-collection", product)
        },
        copyToClipBoard() {
            const link = this.productLink
            navigator.clipboard.writeText(link).then(() => {
                this.$notify({
                    title: "Копирование",
                    text: "Ссылка скопирована в буфер"
                })
            }).catch((err) => {
                this.$notify({
                    title: "Копирование",
                    text: "Ошибка копирования",
                    type: "error"
                })
            });
        },
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

.terms {
    span {
        background: #00000069;
        padding: 5px 6px;
        border-radius: 5px;
        font-size: 10px;
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
