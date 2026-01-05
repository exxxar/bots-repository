<script setup>
import Rating from "@/ClientTg/Components/V1/Shop/Helpers/Rating.vue";
</script>
<template>

    <div class="card border-0 mb-3" v-if="item">
        <div class="card-body p-0">
            <div class="d-flex mb-2">
                <div class="mr-auto"
                     @click="addToCart"
                     style="max-height: 100px;">

                    <img
                        v-lazy="item.images[0]"
                        style="object-fit: cover;height: 100%;"
                        class="rounded-2" width="110">

                </div>
                <div class="w-100 px-2 d-flex flex-column justify-content-between">
                    <h6 class="pb-0 mb-0 fw-bold" style="font-size:14px;">{{ item.title || 'не указано' }}
                        <slot name="partner"></slot>
                    </h6>

                    <h6 class="py-2 mb-0 d-flex justify-content-between" style="font-size:12px;">
                        <span v-if="!config.discount_price">{{ currentPrice }}₽</span>
                        <span v-else> {{ config.discount_price }}₽ <span
                            class="text-decoration-line-through">{{ item.current_price  || 0 }}₽</span>
                            (<span class="text-danger fw-bold">-{{ config.discount_amount || 0 }}₽</span>)
                        </span>
                        <span v-if="!item?.is_weight_product||false">={{ currentPrice * inCart(item.id) }}₽</span>
                        <span v-else>{{ (currentPrice * inCart(item.id)) / (item.weight_config?.step || 100) }}₽</span>
                    </h6>
                    <div class="d-flex w-100">

                        <button type="button"
                                v-if="inCart(item.id)===0"
                                v-bind:class="{'btn-secondary':!canProductAction}"
                                :disabled="item.in_stop_list_at!=null|| !canProductAction"
                                @click="incProductCart"
                                class="btn btn-sm btn-primary w-100 rounded-3">{{ currentPrice }}<sup
                            class="font-10 opacity-50">.00</sup>₽
                        </button>

                        <div class="btn-group w-100" v-if="inCart(item.id)>0">
                            <button type="button"
                                    v-bind:class="{'btn-secondary':!canProductAction}"
                                    :disabled="item.in_stop_list_at!=null|| !canProductAction"
                                    @click="decProductCart"
                                    class="btn btn-sm btn-primary">-
                            </button>
                            <button type="button"
                                    v-bind:class="{'btn-secondary':!canProductAction}"
                                    class="btn btn-sm ">{{ checkInCart }}
                                <span v-if="item?.is_weight_product||false">г.</span>
                            </button>
                            <button type="button"
                                    v-bind:class="{'btn-secondary':!canProductAction}"
                                    :disabled="item.in_stop_list_at!=null|| !canProductAction"
                                    @click="incProductCart"
                                    class="btn btn-sm  btn-primary">+
                            </button>
                        </div>
                    </div>
                </div>

            </div>
            <div class="form-check form-switch mb-2">
                <input
                    v-model="form.need_comment"
                    class="form-check-input" type="checkbox" value="" :id="'item-comment-'+item.id" switch>
                <label class="form-check-label"
                       style="font-size:12px;"
                       :for="'item-comment-'+item.id">
                    Добавить комментарий к товару
                </label>
            </div>

            <div class="form-floating" v-if="form.need_comment">
                <input type="text"
                       @blur="addCommentToProduct"
                       v-model="form.comment"
                       class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Комментарий</label>
            </div>

            <div class="p-2 alert alert-warning mt-2 mb-0"
                 v-if="item.delivery_terms">
                <p class="mb-0 fw-bold">Особенности доставки данного товара</p>
                <h6
                    v-if="item.delivery_terms"
                    class="d-flex justify-content-between mb-3">
                    {{ item.delivery_terms }}
                </h6>
                <p v-else class="mb-0">Дополнительных условий доставки нет</p>

            </div>
        </div>
    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["item", "comment", "config"],
    data() {
        return {
            form: {
                id: null,
                comment: null,
                need_comment: false,
            },
            sending: false,
            is_online: true,
        }
    },
    computed: {
        ...mapGetters(['inCart']),
        canProductAction() {
            return this.is_online && !this.sending
        },
        checkInCart() {
            return this.inCart(this.item.id)
        },
        currentPrice() {
            return this.config?.discount_price ? (this.config?.discount_price || 0) : (this.item.current_price || 0)
        },
        oldPrice() {
            return this.item.old_price / 100
        }
    },
    mounted() {
        window.addEventListener('online', () => {
            this.is_online = true
        });
        window.addEventListener('offline', () => {
            this.is_online = false
        });

        if (this.comment) {
            console.log("COMMENT", this.comment, this.form.need_comment)
            this.form.need_comment = true
            this.form.comment = this.comment
        }

    },
    methods: {
        addToCart() {
            this.$cart.add(this.item)
        },
        addCommentToProduct() {
            this.sending = true
            this.form.id = this.item.id
            let incResult = this.$store.dispatch("addCommentToProduct", this.form)

            incResult.then(() => {
                this.sending = false
                this.$notify({
                    title: "Товар",
                    text: 'Комментарий к товару успешно добавлен',
                    type: 'success'
                })

                if (this.comment) {
                    this.form.need_comment = true
                    this.form.comment = this.comment
                }

            }).catch(() => {
                this.sending = false
                this.$notify({
                    title: "Товар",
                    text: 'Ошибка добавления комментария к товару!',
                    type: 'error'
                })
            })
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
                    text: 'Товар успешно добавлен',
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
                    text: 'Товар успешно удален',
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
        }
    }
}
</script>
<style>
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
</style>
