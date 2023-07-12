<template>

    <div id="menu-share-thumbs"
         class="menu menu-box-modal menu-box-detached rounded-m"
         data-menu-height="500"
         data-menu-width="320">

        <div v-if="product" class="w-100">
            <div v-if="product.images.length>1"
                 class="single-slider owl-carousel owl-no-dots owl-has-controls mt-3" style="height:300px;">
                <div style="height: 300px;"
                     v-for="img in product.images"
                     v-bind:style="{'background-image': 'url('+img+')' }"
                     class="card  rounded-m shadow-l">

                    <div class="card-overlay bg-gradient"></div>
                </div>

            </div>
            <div class="mt-3" v-else>
                <img v-lazy="product.images[0]" class="w-100 object-fit-cover"  alt="">
            </div>
            <p class="text-center mb-0"><small class="font-8 text-gray-400">Изображений товара {{product.images.length}}</small></p>


            <h1 class="text-center font-700 pt-1 mb-3">{{ product.title || 'Нет заголовка' }}</h1>

            <div class="row text-center mr-2 ml-2 mb-3" v-if="checkInCart>0">
                <div class="col-4 mb-n2">
                    <button type="button"
                            @click="incProductCart"
                            class="btn icon-l bg-green1-dark rounded-s shadow-l"><i
                        class="fa-solid fa-plus font-22"></i></button>
                </div>

                <div class="col-4 mb-n2 d-flex justify-content-center align-items-center">
                    <strong class="font-22">{{ checkInCart }}</strong>
                </div>

                <div class="col-4 mb-n2">
                    <button
                        @click="decProductCart"
                        type="button" class="btn icon-l bg-red1-dark rounded-s shadow-l"><i
                        class="fa-solid fa-minus font-22"></i></button>
                </div>

            </div>

            <div class="row text-center mr-2 ml-2 mb-3" v-else>
                <div class="col-12 mb-n2">
                    <button type="button"
                            @click="incProductCart"
                            class="btn p-3 bg-green1-dark rounded-s shadow-l w-100">
                        <i class="fa-solid fa-cart-plus font-12"></i>
                        Добавить в корзину
                    </button>
                </div>
            </div>
            <div class="row text-center mr-2 ml-2 mb-3">
                <div class="col-12 mb-n2">
                    <button type="button"
                            @click="goToProduct"
                            class="btn p-3 bg-blue2-dark rounded-s shadow-l w-100">
                        <i class="fa-solid fa-share-from-square font-12"></i>
                        К товару
                    </button>
                </div>
            </div>


            <div class="divider divider-margins"></div>

            <div class="w-100 d-flex flex-wrap p-2 justify-content-center" v-if="product.categories">
                <span class="badge bg-primary text-white mr-2"
                      v-for="category in product.categories">{{ category.title || 'Нет названия' }}</span>
            </div>

            <p class="boxed-text-xl under-heading mb-2 text-justify">
                {{ product.description || 'Нет описания' }}
            </p>

            <div class="divider divider-margins mt-n1 mb-3"></div>
            <p class="text-center font-10 mb-0">Copyright <span class="copyright-year"></span> - Enabled. All rights
                reserved.</p>
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
        ...mapGetters(['inCart', 'cartTotalCount']),
        currentBot() {
            return window.currentBot
        },
        checkInCart() {
            return this.inCart(this.product.id)
        },
    },
    mounted() {
        window.addEventListener("add-to-cart", (e) => {
            this.product = e.detail.product || null
            this.$nextTick(() => {

                $('.single-slider').owlCarousel({
                    loop: true,
                    margin: 20,
                    nav: false,
                    lazyLoad: true,
                    items: 1,
                    autoplay: true,
                    autoplayTimeout: 4000
                });

                $('#menu-share-thumbs').showMenu();
            })
        });


    },
    methods: {
        goToProduct(){
            this.$router.push({ name: 'product', params: { productId: this.product.id } })
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

            this.$botNotification.notification("Добавление товара","Успешно добавлено в корзину!")
        }
    }
}
</script>
