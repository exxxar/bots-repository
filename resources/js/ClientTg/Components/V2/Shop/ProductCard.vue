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
                    <div class="rating p-2">
                        <span class="text-white fw-bold"><i
                            class="fa-regular fa-star text-primary mr-1"></i> {{ Math.round(item.rating || 0) }}</span>
                    </div>

                    <div class="terms p-2" v-if="item.delivery_terms">
                         <span class="text-white fw-bold">
                        <i class="fa-solid fa-clock text-primary"></i>
                         </span>
                    </div>
                    <span
                        v-if="item.old_price>0"
                        style="margin-right: 10px;"
                        class="badge bg-primary fw-bold">%</span>
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
                            <span v-if="item?.is_weight_product||false">г.</span>
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



</template>
<script>
import {mapGetters} from "vuex";
import {v4 as uuidv4} from "uuid";

export default {
    props: ["item", "displayType", "collectionMode", "canSelect"],
    data() {
        return {
            sending:false,
            is_online:true,
        }
    },
    computed: {
        ...mapGetters(['inCart', 'getReviews', 'getReviewsPaginateObject']),
        currentPrice() {
            return this.item.current_price / 100
        },
        checkInCart() {
            return this.inCart(this.item.id)
        },
        canProductAction() {
            return this.is_online && !this.sending
        },
        currentBot() {
            return window.currentBot
        },

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
        showProductDetails(){
          this.$productInfo.show(this.item)
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
