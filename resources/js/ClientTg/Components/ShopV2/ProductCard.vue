<script setup>
import Rating from "@/ClientTg/Components/Shop/Helpers/Rating.vue";
import ProductReviewFrom from "@/ClientTg/Components/ShopV2/ProductReviewFrom.vue";
</script>
<template>

    <div class="card  border-0 product-card">
        <div
            @click="showProductDetails"
            class="img-container">
            <img
                class="rounded-3"

                v-lazy="item.images[0]">
            <div class="controls">
                <div class="top d-flex justify-content-between w-100 align-items-center">
                    <div class="rating w-100 p-2">
                        <span class="text-white fw-bold"><i class="fa-regular fa-star text-primary mr-1"></i> {{item.rating || 0}}</span>
                    </div>
                    <span
                        v-if="item.old_price>0"
                        class="badge bg-primary mr-2 fw-bold">%</span>
                </div>

            </div>
        </div>

        <div class="card-body px-0">
            <p class="text-center mb-2" style="font-size: 12px;">{{item.title.slice(0, 50)}} <span v-if="item.title.length>50">...</span></p>


<!--            <h6 class="d-flex justify-content-center mb-3"><Rating :rating="item.rating"></Rating> </h6>-->

            <div
                v-if="!item.in_stop_list_at"
                class="d-flex justify-content-between align-items-center">
                <button type="button"
                        v-if="inCart(item.id)===0"
                        @click="incProductCart"
                        class="btn btn-md btn-light w-100 rounded-3">{{item.current_price || 0}}<sup class="font-10 opacity-50">.00</sup>₽</button>

                <div class="btn-group w-100" v-if="inCart(item.id)>0">
                    <button type="button"
                            :disabled="item.in_stop_list_at"
                            @click="decProductCart"
                            class="btn btn-md btn-primary">-</button>
                    <button type="button" class="btn btn-md btn-primary border-0">{{ checkInCart }}</button>
                    <button type="button"
                            :disabled="item.in_stop_list_at"
                            @click="incProductCart"
                            class="btn btn-md btn-primary">+</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" :id="'product-modal-info'+item.id" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="card text-bg-dark" v-if="item">
                        <img v-lazy="item.images[0]"
                             class="card-img" alt="...">
                        <div class="card-img-overlay d-flex flex-column justify-content-between p-0">
                            <div class="shadow-bg">
                                <h6 class="text-left" style="font-weight:700; line-height:100%;">{{ (item.title || 'Не указан') }}</h6>
                                <p class="text-left mb-0 ">Цена <strong class="text-primary">{{ item.current_price || 0 }}<sup
                                    class="font-400 opacity-50">.00</sup></strong> ₽</p>
                            </div>



                        </div>
                    </div>
                    <div class="p-2">
                        <p class="mb-0">Рейтинг товара</p>
                        <h6 class="d-flex justify-content-between mb-3"><Rating :rating="item.rating"></Rating> {{item.rating}} из 5</h6>

                    </div>
                    <p class="text-justify py-2 fst-italic">{{item.description || '-'}}</p>


                    <button type="button" class="btn btn-link w-100 mt-2"
                            data-bs-dismiss="modal">Закрыть</button>
<!--
                    <ProductReviewFrom></ProductReviewFrom>-->

                </div>
               <div class="modal-footer p-0 m-0"
                     v-if="!item.in_stop_list_at">
                    <div class="d-flex justify-content-between align-items-center w-100 p-0">
                        <button type="button"
                                v-if="inCart(item.id)===0"
                                @click="incProductCart"
                                class="btn btn-md btn-primary w-100 rounded-3 p-3">{{item.current_price || 0}}<sup class="font-10 opacity-50">.00</sup>₽</button>

                        <div class="btn-group w-100" v-if="inCart(item.id)>0">
                            <button type="button"
                                    :disabled="item.in_stop_list_at!=null"
                                    @click="decProductCart"
                                    class="btn btn-md btn-primary p-3">-</button>
                            <button type="button" class="btn btn-md">{{ checkInCart }}</button>
                            <button type="button"
                                    :disabled="item.in_stop_list_at!=null"
                                    @click="incProductCart"
                                    class="btn btn-md btn-primary p-3">+</button>
                        </div>
                    </div>

                </div>
                <div class="modal-footer p-0 m-0"
                  v-else>
                    <p class="p-3 border-secondary border rounded-2 w-100 text-secondary fw-bold text-center">Товар недоступен для заказа</p>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["item", "displayType"],
    data(){
        return {
            showCart:false
        }
    },
    computed:{
        ...mapGetters(['inCart']),
        currentPrice(){
            return this.item.current_price / 100
        },
        oldPrice(){
            return this.item.old_price / 100
        },
        checkInCart() {
            return this.inCart(this.item.id)
        },
    },
    mounted() {
        console.log(this.item)
    },
    methods:{

        showProductDetails(){
            const myModal = new bootstrap.Modal(document.getElementById('product-modal-info'+this.item.id), {})
            myModal.show()
        },
        goToProduct(){
            this.$router.push({ name: 'ProductV2', params: { productId: this.item.id } })
        },
        incProductCart() {
            console.log("TEEEEEEEEST1231312312")
            if (this.checkInCart === 0)
                this.$store.dispatch("addProductToCart", this.item)
            else
                this.$store.dispatch("incQuantity", this.item.id)

            this.$notify({
                title:"Добавление товара",
                text: 'Товар успешно добавлен',
                type:'success'
            })
        },
        decProductCart() {

            if (this.checkInCart <= 1)
                this.$store.dispatch("removeProduct", this.item.id)
            else
                this.$store.dispatch("decQuantity", this.item.id)

            this.$notify({
                title:"Добавление товара",
                text: 'Товар успешно удален',
                type:'success'
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
    span{
        background: #00000069;
        padding: 5px 6px;
        border-radius: 5px;
        font-size: 10px;
    }
}
</style>
