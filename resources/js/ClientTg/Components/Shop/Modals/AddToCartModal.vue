<template>
    <div id="menu-product-info" class="menu menu-box-bottom menu-box-detached rounded-m d-block"
         style="height:220px; display:block;"
         data-menu-effect="menu-over">

        <div class="w-100" v-if="product">
            <h4 class="text-center font-700 mt-3 pt-1 px-4">{{product.title || 'Нет заголовка'}}</h4>

            <div class="row text-center mr-2 ml-2 mb-3" v-if="checkInCart>0">
                <div class="col-4 mb-n2">
                    <button type="button"
                            @click="incProductCart"
                            class="btn p-3 w-100 bg-highlight rounded-s shadow-l"><i
                        class="fa-solid fa-plus font-22"></i></button>
                </div>

                <div class="col-4 mb-n2 d-flex justify-content-center align-items-center">
                    <strong class="font-22">{{ checkInCart }}</strong>
                </div>

                <div class="col-4 mb-n2">
                    <button
                        @click="decProductCart"
                        type="button" class="btn p-3 w-100 bg-red1-dark rounded-s shadow-l"><i
                        class="fa-solid fa-minus font-22"></i></button>
                </div>

            </div>

            <div class="row text-center mr-2 ml-2 mb-3" v-else>
                <div class="col-12 mb-n2">
                    <button type="button"
                            @click="incProductCart"
                            class="btn p-3 bg-highlight rounded-s shadow-l w-100">
                        <i class="fa-solid fa-cart-plus font-12"></i>
                        В корзину <strong>{{currentPrice}}₽</strong>
                    </button>
                </div>
            </div>

            <div class="row text-center mr-2 ml-2 mb-3">
                <div class="col-12 mb-n2">
                    <button type="button"
                            v-if="!product.in_favorite"
                            @click="addToFavorite"
                            class="btn p-3 bg-red2-dark rounded-s shadow-l w-100">
                        <i  class="fa-regular fa-heart font-12"></i>
                        В избранное
                    </button>
                    <button type="button"
                            v-else
                            @click="removeFromFavorites"
                            class="btn p-3 bg-highlight rounded-s shadow-l w-100">
                        <i class="fa-solid fa-heart font-12"></i>
                        Убрать из избранного
                    </button>
                </div>
            </div>
        </div>


    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            product: null
        }
    },
    computed: {
        ...mapGetters(['inCart', 'cartTotalCount','inFav']),
        currentBot() {
            return window.currentBot
        },
        self(){
          return window.self
        },
        currentPrice() {
            return this.product.current_price
        },
        checkInCart() {
            return this.inCart(this.product.id)
        },
    },
    mounted() {
        window.addEventListener("add-to-cart", (e) => {
            this.product = e.detail.product || null

            this.product.in_favorite = this.inFav(this.product.id)
            this.$nextTick(() => {

                $('#menu-product-info').showMenu();
            })
        });


    },
    methods: {
        removeFromFavorites(){
            this.product.in_favorite = false
            this.$store.dispatch("removeFromFavorites", this.product.id).then(()=>{
                this.$botNotification.notification("Избранное","Успешно удалено из избранного!")
            })
        },
        addToFavorite(){
            this.product.in_favorite = true
            this.$store.dispatch("addToFavorites", this.product).then(()=>{
                this.$botNotification.notification("Избранное","Успешно добавлено в избранное!")

            })
        },
        goToProduct(){
            this.$router.push({ name: 'product', params: { productId: this.product.id } })
            $('#menu-product-info').hideMenu();
        },
        incProductCart() {
            if (this.checkInCart === 0)
                this.$store.dispatch("addProductToCart", this.product)
            else
                this.$store.dispatch("incQuantity", this.product.id)

            this.$botNotification.notification("Добавление товара","Успешно добавлено в корзину!")
        },
        decProductCart() {

            if (this.checkInCart <= 1)
                this.$store.dispatch("removeProduct", this.product.id)
            else
                this.$store.dispatch("decQuantity", this.product.id)

            this.$botNotification.notification("Добавление товара","Товар удален!")
        }
    }
}
</script>
<style>

</style>
