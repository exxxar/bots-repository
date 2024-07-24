<script setup>
import Rating from "@/ClientTg/Components/V1/Shop/Helpers/Rating.vue";
</script>
<template>

<div class="card border-0">
    <div class="card-body p-1">
        <div class="d-flex">
            <div class="mr-auto"
                 @click="addToCart"
                 style="max-height: 100px;">
                <img v-lazy="item.images[0]"
                     style="object-fit: cover;height: 100%;"
                     class="rounded-2" width="110">
            </div>
            <div class="w-100 px-2 d-flex flex-column justify-content-between">
                <h6 class="pb-0 mb-0 fw-bold" style="font-size:14px;">{{item.title || 'не указано'}}</h6>

                <h6 class="py-2 mb-0 d-flex justify-content-between" style="font-size:12px;">
                    <span>{{item.current_price || 0}}₽</span>
                    <span>={{item.current_price * inCart(item.id)}}₽</span>
                </h6>
                <div class="d-flex w-100">

                    <button type="button"
                            v-if="inCart(item.id)===0"
                            @click="incProductCart"
                            class="btn btn-sm btn-primary w-100 rounded-3">{{item.current_price || 0}}<sup class="font-10 opacity-50">.00</sup>₽</button>

                    <div class="btn-group w-100" v-if="inCart(item.id)>0">
                        <button type="button"
                                :disabled="item.in_stop_list_at"
                                @click="decProductCart"
                                class="btn btn-sm btn-primary">-</button>
                        <button type="button" class="btn btn-sm ">{{ checkInCart }}</button>
                        <button type="button"
                                :disabled="item.in_stop_list_at"
                                @click="incProductCart"
                                class="btn btn-sm  btn-primary">+</button>
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
    props: ["item"],
    data(){
        return {
            showCart:false
        }
    },
    computed:{
        ...mapGetters(['inCart']),
        checkInCart() {
            return this.inCart(this.item.id)
        },
        currentPrice(){
            return this.item.current_price / 100
        },
        oldPrice(){
            return this.item.old_price / 100
        }
    },
    methods:{
        addToCart(){
            this.$cart.add(this.item)
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
