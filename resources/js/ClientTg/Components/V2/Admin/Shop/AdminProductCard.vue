<script setup>
import Rating from "@/ClientTg/Components/V1/Shop/Helpers/Rating.vue";
import ReviewCard from "@/ClientTg/Components/V2/Shop/ReviewCard.vue";

</script>
<template>

    <div

        class="card  product-card">
        <div
            @click="showProductDetails"
            class="img-container">
            <img
                class="rounded-3"
                v-lazy="item.images[0]">
            <div class="controls">
                <div class="top d-flex justify-content-between w-100 align-items-center">
                    <div class="rating w-100 p-2">
                        <span class="text-white fw-bold"><i
                            class="fa-regular fa-star text-primary mr-1"></i> {{ item.rating || 0 }}</span>
                    </div>
                    <span
                        v-if="item.old_price>0"
                        class="badge bg-primary mr-2 fw-bold">%</span>
                </div>

            </div>
        </div>

        <div class="card-body"
             @click="showProductDetails">
            <p class="text-center mb-2" style="font-size: 12px;">{{ item.title.slice(0, 50) }} <span
                v-if="item.title.length>50">...</span></p>

            <p class="text-center mb-2">
                {{ item.current_price || 0 }}₽
                <span class="text-decoration-line-through" style="font-size:10px;"
                      v-if="item.old_price>0">{{ item.old_price || 0 }}₽</span>
            </p>


        </div>
        <div class="card-footer"
             v-if="item.deleted_at">
            <button
                @click="repairProduct"
                class="btn btn-primary w-100"
                type="button">Восстановить</button>
        </div>
    </div>



</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["item", "displayType"],
    data() {
        return {
            showCart: false,



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

    },

    methods: {



        showProductDetails() {
            this.$emit("select", this.item)
        },
        goToProduct() {
            this.$router.push({name: 'ProductV2', params: {productId: this.item.id}})
        },
        repairProduct(){
            this.$store.dispatch("restoreProduct", this.item.id).then(()=>{
                this.$notify({
                    title: "Восстановление товара",
                    text: 'Товар успешно восстановлен',
                    type: 'success'
                })
                this.item.deleted_at = null
            }).catch(()=>{
                this.$notify({
                    title: "Восстановление товара",
                    text: 'Товар не удалось восстановить',
                    type: 'error'
                })
            })
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
