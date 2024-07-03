<script setup>
import Rating from "ClientTg@/Components/Shop/Helpers/Rating.vue";
</script>
<template>

    <div class="card shadow-sm product-card">
        <img v-lazy="item.images[0]">
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

        goToProduct(){
            this.$router.push({ name: 'product', params: { productId: this.item.id } })
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
    img {
        object-fit: cover;
        /* height: 100%; */
        width: 100%;
        max-height: 190px;
    }
}
</style>
