<script setup>
import Rating from "@/ClientTg/Components/V1/Shop/Helpers/Rating.vue";
</script>
<template>

    <div class="card border-0" v-if="item">
        <div class="card-body">
            <div class="d-flex">
                <div class="mr-auto"
                     @click="addToCart"
                     style="max-height: 100px;">

                    <img
                        v-lazy="item.images[0]"
                        style="object-fit: cover;height: 100%;"
                        class="rounded-2" width="110">
                </div>
                <div class="w-100 px-2 d-flex flex-column justify-content-between">
                    <h6 class="pb-0 mb-0 fw-bold" style="font-size:14px;">{{ item.title || 'не указано' }}</h6>

                    <h6 class="py-2 mb-0 d-flex justify-content-between" style="font-size:12px;">
                        <span>{{ item.current_price || 0 }}₽</span>
                        <span>={{ item.current_price * inCart(item.id) }}₽</span>
                    </h6>
                    <div class="d-flex w-100">

                        <button type="button"
                                v-if="inCart(item.id)===0"
                                v-bind:class="{'btn-secondary':!canProductAction}"
                                :disabled="item.in_stop_list_at!=null|| !canProductAction"
                                @click="incProductCart"
                                class="btn btn-sm btn-primary w-100 rounded-3">{{ item.current_price || 0 }}<sup
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
                                    class="btn btn-sm ">{{ checkInCart }}</button>
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
            <div class="p-2 alert alert-warning mt-2 mb-0"
                 v-if="item.delivery_terms">
                <p class="mb-0 fw-bold">Особенности доставки данного товара</p>
                <h6
                    v-if="item.delivery_terms"
                    class="d-flex justify-content-between mb-3">
                    {{item.delivery_terms}}
                </h6>
                <p v-else class="mb-0">Дополнительных условий доставки нет</p>

            </div>
        </div>
    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["item"],
    data() {
        return {
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
            return this.item.current_price / 100
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
    },
    methods: {
        addToCart() {
            this.$cart.add(this.item)
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
