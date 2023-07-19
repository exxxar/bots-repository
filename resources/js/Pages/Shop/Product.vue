<script setup>
import Rating from "@/Components/Shop/Helpers/Rating.vue";
</script>

<template>



    <div v-if="product" class="card card-style">

        <div class="card"
             v-bind:style="{'background-image': 'url('+(product.images[0]||'')+')' }"
             style="height:250px;">

            <div class="card-top mt-3 mr-3">
                <a href="#"
                   style="width:50px; height:50px;"
                   v-if="product.in_favorite" class="btn bg-highlight text-white order-highlight rounded-l shadow-xl  d-flex justify-content-center align-items-center float-right mr-0"><i
                    class="fa fa-heart"></i></a>
                <a href="#" v-else
                   style="width:50px; height:50px;"
                   class="btn bg-red2-dark rounded-l d-flex justify-content-center align-items-center shadow-xl bg-red2-dark color-white float-right mr-0"><i
                    class="fa fa-heart"></i></a>
            </div>

            <div class="card-bottom pb-4 pl-3">
                <h1 class="font-26" style="padding-right: 100px;">{{ product.title || 'Не указан' }}</h1>
            </div>

            <div class="card-bottom pb-4 pr-3">
                <h1 class="font-30 text-right mb-3">{{ product.current_price || 0 }}<sup
                    class="font-400 font-17 opacity-50">.99</sup></h1>
                <span
                    v-if="product.old_price>0"
                    class="badge bg-highlight color-white px-2 py-1 mt-n1 text-uppercase d-block float-right">{{ discount }}% Скидка</span>
            </div>

            <div class="card-overlay bg-gradient-fade rounded-0"></div>
        </div>


        <div class="content mt-n2">

            <div class="row">
                <div class="col-12">

                    <div class="d-flex">
                        <div class="pt-1">
                            <h5 :data-activate="'toggle-id-5-'+product.id" class="font-500 font-13">Полное описание
                                товара</h5>
                        </div>
                        <div class="ml-auto mr-1 pr-2">
                            <div class="custom-control classic-switch">
                                <input type="checkbox" class="classic-input" :id="'toggle-id-5-'+product.id">
                                <label class="custom-control-label" :for="'toggle-id-5-'+product.id"></label>
                                <i class="fa fa-plus font-11 color-green1-dark"></i>
                            </div>
                        </div>
                    </div>
                    <div :data-switch="'toggle-id-5-'+product.id" class="switch-is-unchecked">
                        <p class="mb-0 pb-0 pt-2">{{ product.description || 'Нет описания' }}</p>
                    </div>

                    <!--                    <p class="line-height-m">



                                        </p>-->
                </div>
                <div class="col-12">
                    <div>
                        <p class="font-10 mb-n2">Product Type</p>
                        <p class="font-12 color-theme font-700">Mobile PWA</p>
                    </div>
                    <div>
                        <p class="font-10 mb-n2">Code Platform</p>
                        <p class="font-12 color-theme font-700">Bootstrap 4.x</p>
                    </div>
                    <div>
                        <p class="font-10 mb-n2">Hybrid App</p>
                        <p class="font-12 color-theme font-700">PhoneGap & Cordova</p>
                    </div>
                    <div>
                        <p class="font-10 mb-n2">Support</p>
                        <p class="font-12 color-theme font-700">Included</p>
                    </div>

                </div>
            </div>


            <div class="divider mt-4 mb-2"></div>

            <div class="d-flex">
                <div>
                    <p class="mb-n1 font-10">Рейтинг & Отзывы</p>
                    <h6 class="float-left">{{product.rating}}</h6>
                 <Rating :rating="product.rating"/>
                </div>
                <div class="ml-auto">
                    <a class="icon icon-s mt-2 mr-2 rounded-m bg-red2-dark color-white" href="#"><i class="fa fa-bookmark"></i></a>
                    <a data-menu="menu-share" class="icon icon-s mt-2 rounded-m bg-highlight color-white" href="#"><i class="fa fa-share-alt"></i></a>
                </div>
            </div>

            <div class="divider mt-3"></div>

            <div class="row text-center mb-3" v-if="checkInCart>0">
                <div class="col-4 mb-n2">
                    <button type="button"
                            @click="incProductCart"
                            class="btn p-2 w-100 bg-highlight rounded-s shadow-l"><i
                        class="fa-solid fa-plus font-22"></i></button>
                </div>

                <div class="col-4 mb-n2 d-flex justify-content-center align-items-center">
                    <strong class="font-22">{{ checkInCart }}</strong>
                </div>

                <div class="col-4 mb-n2">
                    <button
                        @click="decProductCart"
                        type="button" class="btn p-2 w-100 bg-red1-dark rounded-s shadow-l"><i
                        class="fa-solid fa-minus font-22"></i></button>
                </div>

            </div>
            <div class="row text-center  mb-3" v-else>
                <div class="col-12 mb-n2">
                    <button type="button"
                            @click="incProductCart"
                            class="btn btn-full bg-highlight btn-l rounded-sm text-uppercase font-800 w-100 p-3">
                        <i class="fa-solid fa-cart-plus font-12"></i>
                        В корзину <strong>{{ currentPrice }}₽</strong>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-style">
        <div class="content">
            <h4 class="font-600">Product Showcase</h4>
            <p class="mb-3">
                A classic image slider representing the beautiful power of our product. This image slider can be made
                either single or double to suit your needs.
            </p>
        </div>
        <div class="double-slider text-center owl-no-dots owl-carousel">
            <div class="card rounded-m shadow-l bg-10" data-card-height="220"></div>
            <div class="card rounded-m shadow-l bg-11" data-card-height="220"></div>
            <div class="card rounded-m shadow-l bg-12" data-card-height="220"></div>
            <div class="card rounded-m shadow-l bg-13" data-card-height="220"></div>
            <div class="card rounded-m shadow-l bg-14" data-card-height="220"></div>
        </div>
    </div>


    <div class="card card-style">
        <div class="content">
            <h4 class="font-700">Specifications</h4>
            <p>
                A classic list of specifications that are necessary for products that give minor and major listed
                details.
            </p>

            <div class="row mb-0">
                <div class="col-4">
                    <p class="font-600 mb-n1 color-highlight">PWA</p>
                    <p>Compatible</p>
                </div>
                <div class="col-4">
                    <p class="font-600 mb-n1 color-highlight">Platform</p>
                    <p>Bootstrap 4.x</p>
                </div>
                <div class="col-4">
                    <p class="font-600 mb-n1 color-highlight">Support</p>
                    <p>Included</p>
                </div>
                <div class="w-100 mb-3"></div>
                <div class="col-6">
                    <p class="font-600 mb-n1 color-highlight">Hybrid App</p>
                    <p>Cordova or PhoneGap.</p>
                </div>
                <div class="col-6">
                    <p class="font-600 mb-n1 color-highlight">Updates?</p>
                    <p>Constant Releases.</p>
                </div>
            </div>

            <div class="divider mt-4"></div>

            <a href="#" class="btn btn-full bg-green1-dark btn-l rounded-sm text-uppercase font-800"><i
                class="fa fa-shopping-bag pr-3"></i>Purchase Now</a>
        </div>
    </div>


    <div class="card card-style" v-if="product">
        <div class="content">
            <h3>Другие изображения товара</h3>
            <p>
                A gallery showcasing our product in a better view for you to see.
            </p>
        </div>
        <div class="content">
            <div class="row text-center row-cols-3 mb-0">
                <a class="col mb-2"
                   data-lightbox="gallery-1"
                   v-for="(img, index) in product.images"
                   :href="img" :title="product.title">
                    <img v-lazy="img" :data-src="img"
                         class="preload-img img-fluid rounded-xs" alt="img">

                </a>

            </div>
        </div>
    </div>

    <div class="card card-style">
        <div class="content">
            <h3>Testimonials</h3>
            <p>
                Sharing feedback from our customers is always makes us happy.
            </p>
        </div>

        <div class="divider divider-margins"></div>

        <div class="content mt-0">
            <h1 class="mb-n2 font-15 font-700">John Doeson</h1>
            <h1 class="float-right font-700 font-30 mt-n3">5.00</h1>
            <p class="mb-2 font-10"><i class="fa fa-check-circle color-highlight scale-icon mr-2"></i>Verified Purchase
            </p>
            <p>
                The best support I have ever had. They are on top of things and with very fast and accurate replies..
            </p>
        </div>
        <div class="divider divider-margins"></div>

        <div class="content mt-0">
            <h1 class="mb-n2 font-15 font-700">Louder Johanna</h1>
            <h1 class="float-right font-700 font-30 mt-n3">4.00</h1>
            <p class="mb-2 font-10"><i class="fa fa-check-circle color-highlight scale-icon mr-2"></i>Verified Purchase
            </p>
            <p>
                I hope they will keep the support they deliver, because for me that's the most important part of buying.
            </p>
        </div>

        <div class="divider divider-margins"></div>

        <div class="content mt-0">
            <h1 class="mb-n2 font-15 font-700">Louder Johanna</h1>
            <h1 class="float-right font-700 font-30 mt-n3">4.50</h1>
            <p class="mb-2 font-10"><i class="fa fa-check-circle color-highlight scale-icon mr-2"></i>Verified Purchase
            </p>
            <p>
                I like the way it's setup, did had to do some research before I got to know how it works though.
            </p>
        </div>

        <div class="content mt-0">
            <div class="divider"></div>
            <div class="row mb-0">
                <div class="col-6">
                    <a href="#" class="btn btn-full bg-green1-dark btn-m rounded-sm text-uppercase font-800">Purchase
                        Now</a>
                </div>
                <div class="col-6">
                    <a href="#" class="btn btn-full bg-highlight btn-m rounded-sm text-uppercase font-800">Add to
                        Cart</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 preload-img" data-src="images/pictures/20s.jpg">
        <div class="card-body">
            <h2 class="color-white font-700 pt-3">Recommended</h2>
            <p class="color-white opacity-70 mb-4">
                We recommend some more awesome products for you! Swipe left or right to check them out.
            </p>

            <div class="double-slider owl-carousel owl-no-dots">
                <a href="#">
                    <div data-card-height="250" class="card rounded-sm shadow-l bg-14">
                        <div class="card-bottom">
                            <h4 class="color-white font-20 line-height-l mb-3 ml-3">Sticky <br> Mobile</h4>
                        </div>
                        <div class="card-overlay bg-black opacity-80"></div>
                    </div>
                </a>
                <a href="#">
                    <div data-card-height="250" class="card rounded-sm shadow-l bg-18">
                        <div class="card-bottom">
                            <h4 class="color-white font-20 line-height-l mb-3 ml-3">AppKit <br> Mobile</h4>
                        </div>
                        <div class="card-overlay bg-black opacity-80"></div>
                    </div>
                </a>
                <a href="#">
                    <div data-card-height="250" class="card rounded-sm shadow-l bg-14">
                        <div class="card-bottom">
                            <h4 class="color-white font-20 line-height-l mb-3 ml-3">Kolor <br> Mobile</h4>
                        </div>
                        <div class="card-overlay bg-black opacity-80"></div>
                    </div>
                </a>
                <a href="#">
                    <div data-card-height="250" class="card rounded-sm shadow-l bg-18">
                        <div class="card-bottom">
                            <h4 class="color-white font-20 line-height-l mb-3 ml-3">Ultra <br> Mobile</h4>
                        </div>
                        <div class="card-overlay bg-black opacity-80"></div>
                    </div>
                </a>
                <a href="#">
                    <div data-card-height="250" class="card rounded-sm shadow-l bg-18">
                        <div class="card-bottom">
                            <h4 class="color-white font-20 line-height-l mb-3 ml-3">AMP <br> Mobile</h4>
                        </div>
                        <div class="card-overlay bg-black opacity-80"></div>
                    </div>
                </a>
            </div>
            <a href="#" class="btn btn-full btn-m font-900 rounded-sm text-uppercase bg-white color-highlight">View
                All</a>
        </div>
        <div class="card-overlay bg-highlight opacity-95"></div>
        <div class="card-overlay dark-mode-tint"></div>
    </div>


</template>
<script>
import baseJS from '@/modules/custom.js'
import {mapGetters} from "vuex";

export default {

    data() {
        return {
            product: null,
        }
    },
    computed: {
        ...mapGetters(['inCart', 'cartTotalCount']),
        currentBot() {
            return window.currentBot
        },
        discount() {
            return Math.round(100-(this.product.current_price / this.product.old_price) * 100)
        },
        currentPrice() {
            return this.product.current_price
        },
        checkInCart() {
            return this.inCart(this.product.id)
        },
    },
    mounted() {
        this.loadProduct()
    },
    methods: {
        incProductCart() {
            if (this.checkInCart === 0)
                this.$store.dispatch("addProductToCart", this.product)
            else
                this.$store.dispatch("incQuantity", this.product.id)

            this.$botNotification.notification("Добавление товара", "Успешно добавлено в корзину!")
        },
        decProductCart() {

            if (this.checkInCart <= 1)
                this.$store.dispatch("removeProduct", this.product.id)
            else
                this.$store.dispatch("decQuantity", this.product.id)

            this.$botNotification.notification("Добавление товара", "Товар удален!")
        },
        loadProduct() {
            let productId = this.$route.params.productId

            this.$store.dispatch("loadProduct", {
                dataObject: {
                    productId: productId
                }
            }).then(resp => {
                console.log(resp)
                this.product = resp.data
                baseJS.handler()

            })
        }
    }
}
</script>
<style>

</style>
