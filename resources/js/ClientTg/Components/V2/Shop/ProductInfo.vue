<template>
    <!-- Modal -->
    <div class="modal fade" id="product-modal-info" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen">
            <div class="modal-content">
                <template v-if="item">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" >
                    <div class="card border-0" >
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
                            {{ Math.round(item.rating) }} из 5
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
                </template>

            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";


export default {
    data(){
      return {
          item:null,
          tab: 0,
          modal:null,
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

        this.modal = new bootstrap.Modal(document.getElementById('product-modal-info'), {})


        window.addEventListener('online', () => {
            this.is_online = true
        });
        window.addEventListener('offline', () => {
            this.is_online = false
        });

        window.addEventListener("product-info-event", (event) =>{
            this.item = event.detail.product
            this.selected_image = (this.item.images || []).length > 0 ? this.item.images[0] : null

            this.showProductDetails()

        });
    },
    methods:{
        goToProduct() {
            this.$router.push({name: 'ProductV2', params: {productId: this.item.id}})
        },
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
          this.modal.show()
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
