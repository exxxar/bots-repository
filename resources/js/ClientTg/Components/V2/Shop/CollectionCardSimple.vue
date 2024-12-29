<script setup>
import Rating from "@/ClientTg/Components/V1/Shop/Helpers/Rating.vue";
</script>
<template>

    <div
        v-if="item"
        class="card border-0">
        <div class="card-body p-1">

            <div class="d-flex">
                <div class="mr-auto"
                     style="max-height: 100px;">
                    <img
                        class="rounded-2" width="110"
                        style="object-fit: cover;height: 100%;"
                        v-lazy="'/images-by-company-id/'+bot.company.id+'/'+item.image" alt="">

                </div>
                <div class="w-100 px-2 d-flex flex-column justify-content-between">
                    <h6 class="pb-0 mb-0 fw-bold" style="font-size:14px;">
                        {{ item.title || 'не указано' }}
                        <small
                            class="fw-bold"> ({{ (item.products || []).length }} ед.)</small>
                    </h6>

                    <p class="fst-italic mb-2 " style="font-size:10px;">{{ description }}</p>
                    <h6 class="py-2 mb-0 d-flex justify-content-between" style="font-size:12px;">
                        <span>{{ currentPrice || 0 }}₽</span>
                        <span>={{ currentPrice * checkInCart }}₽</span>
                    </h6>
                    <div class="d-flex w-100">

                        <button type="button"
                                v-if="checkInCart===0"
                                @click="incProductCart"
                                class="btn btn-sm btn-primary w-100 rounded-3">{{ currentPrice || 0 }}<sup
                            class="font-10 opacity-50">.00</sup>₽
                        </button>

                        <div class="btn-group w-100" v-if="checkInCart>0">
                            <button type="button"
                                    :disabled="item.in_stop_list_at"
                                    @click="decProductCart"
                                    class="btn btn-sm btn-primary">-
                            </button>
                            <button type="button" class="btn btn-sm ">{{ checkInCart }}</button>
                            <button type="button"
                                    :disabled="item.in_stop_list_at"
                                    @click="incProductCart"
                                    class="btn btn-sm  btn-primary">+
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["item", "params"],
    data() {
        return {
            showCart: false
        }
    },
    computed: {
        ...mapGetters(['inCollectionCart']),
        checkInCart() {
            return this.inCollectionCart(this.item.id, this.params?.variant_id || null)
        },
        description() {
            let titles = ""

            let ids = this.params.ids || []

            this.item.products.forEach(item => {
                titles += (ids.indexOf(item.id) !== -1 ? item.title + ", " : "")
            })


            return titles
        },
        currentPrice() {
            let price = 0

            let ids = this.params.ids || []

            this.item.products.forEach(item => {
                price += ids.indexOf(item.id) !== -1 ? item.current_price || 0 : 0
            })


            return price
        },
        oldPrice() {
            return 0//this.item.old_price / 100
        },
        bot() {
            return window.currentBot
        },

    },
    mounted() {

    },
    methods: {

        incProductCart() {
            let incResult = this.checkInCart === 0 ?
                this.$store.dispatch("addCollectionToCart", this.item) :
                this.$store.dispatch("incCollectionQuantity", {
                    product_collection_id: this.item.id,
                    variant_id: this.params.variant_id
                })


            incResult.then(() => {
                this.$notify({
                    title: "Добавление товара",
                    text: 'Товар успешно добавлен',
                    type: 'success'
                })
            }).catch(() => {
                this.$notify({
                    title: "Добавление товара",
                    text: 'Ошибка добавления товара!',
                    type: 'error'
                })
            })


        },
        decProductCart() {

            let decResult = this.checkInCart <= 1 ?
                this.$store.dispatch("removeCollectionFromCart", {
                    product_collection_id: this.item.id,
                    variant_id: this.params.variant_id
                }) :
                this.$store.dispatch("decCollectionQuantity", {
                    product_collection_id: this.item.id,
                    variant_id: this.params.variant_id
                })

            decResult.then(() => {
                this.$notify({
                    title: "Удаление товара",
                    text: 'Товар успешно удален',
                    type: 'success'
                })
            }).catch(() => {
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
