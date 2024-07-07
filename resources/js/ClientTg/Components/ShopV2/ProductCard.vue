<script setup>
import Rating from "ClientTg@/Components/Shop/Helpers/Rating.vue";
</script>
<template>

    <div class="card shadow-sm product-card">
        <img
            @click="showProductDetails"
            v-lazy="item.images[0]">
        <div class="card-body">
            <p class="card-text">{{item.title}}</p>
            <div class="d-flex justify-content-between align-items-center">
                <button type="button"
                        v-if="inCart(item.id)===0"
                        @click="incProductCart"
                        class="btn btn-md btn-primary w-100 rounded-3">{{item.current_price || 0}}<sup class="font-10 opacity-50">.00</sup>₽</button>

                <div class="btn-group w-100" v-if="inCart(item.id)>0">
                    <button type="button"
                            :disabled="item.in_stop_list_at"
                            @click="decProductCart"
                            class="btn btn-md btn-primary">-</button>
                    <button type="button" class="btn btn-md">{{ checkInCart }}</button>
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
                        <div class="card-img-overlay">
                            <h5 class="card-title">{{ item.title || 'Не указан' }}</h5>
                            <p class="card-text">Цена {{ item.current_price || 0 }}<sup
                                class="font-400 opacity-50">.00</sup> ₽</p>
                        </div>
                    </div>

                    <p class="text-center py-3">{{item.description || '-'}}</p>
                    <div class="d-flex justify-content-between align-items-center px-3 mb-5">
                        <button type="button"
                                v-if="inCart(item.id)===0"
                                @click="incProductCart"
                                class="btn btn-md btn-primary w-100 rounded-3">{{item.current_price || 0}}<sup class="font-10 opacity-50">.00</sup>₽</button>

                        <div class="btn-group w-100" v-if="inCart(item.id)>0">
                            <button type="button"
                                    :disabled="item.in_stop_list_at"
                                    @click="decProductCart"
                                    class="btn btn-md btn-primary">-</button>
                            <button type="button" class="btn btn-md">{{ checkInCart }}</button>
                            <button type="button"
                                    :disabled="item.in_stop_list_at"
                                    @click="incProductCart"
                                    class="btn btn-md btn-primary">+</button>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Закрыть</button>
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
    methods:{

        showProductDetails(){
            const myModal = new bootstrap.Modal(document.getElementById('product-modal-info'+this.item.id), {})
            myModal.show()
        },
        goToProduct(){
            this.$router.push({ name: 'ProductV2', params: { productId: this.item.id } })
        },
        incProductCart() {
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
    min-height: 350px;

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
</style>
