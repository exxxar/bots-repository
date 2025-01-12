<script setup>

</script>

<template>
    <div class="card text-bg-dark rounded-0" v-if="product">
        <img v-lazy="product.images[0]" class="card-img rounded-0" alt="...">
        <div class="card-img-overlay">
            <h5 class="card-title">{{ product.title || 'Не указан' }}</h5>
            <p class="card-text">Цена {{ product.current_price || 0 }}<sup
                class="font-400 opacity-50">.00</sup></p>
        </div>
    </div>

    <div class="card" v-if="product">
        <div class="card-body">
            <p>{{product.description || '-'}}</p>
        </div>
    </div>



<!--
    <div v-if="product" class="card ">

        <div class="card"
             v-bind:style="{'background-image': 'url('+(product.images[0]||'')+')' }"
             style="height:250px;">

            <div class="card-header mt-3 mr-3">
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

                    &lt;!&ndash;                    <p class="line-height-m">



                                        </p>&ndash;&gt;
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


-->

    <div class="card " v-if="product">
        <div class="content">
            <h3>Другие изображения товара</h3>
            <p>
                A gallery showcasing our product in a better view for you to see.
            </p>
        </div>
        <div class="content" v-if="product.images">
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


    <nav
        v-if="product"
        class="navbar navbar-expand-sm bg-dark navbar-dark fixed-bottom p-0" style="border-radius:10px 10px 0px 0px;">
        <button type="button"
                v-if="inCart(product.id)===0"
                @click="incProductCart"
                class="btn btn-md btn-primary w-100 rounded-0 p-3">{{product.current_price || 0}}<sup class="font-10 opacity-50">.00</sup>₽</button>

        <div class="btn-group w-100 " v-if="inCart(product.id)>0">
            <button type="button"
                    :disabled="product.in_stop_list_at"
                    @click="decProductCart"
                    class="btn btn-md btn-primary p-3 rounded-0">-</button>
            <button type="button" class="btn btn-md bg-transparent">{{ checkInCart }}</button>
            <button type="button"
                    :disabled="product.in_stop_list_at"
                    @click="incProductCart"
                    class="btn btn-md btn-primary rounded-0">+</button>
        </div>
    </nav>
</template>
<script>
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

            this.$notify({
                title:'Добавление товара',
                text:'Успешно добавлено в корзину!',
            })


        },
        decProductCart() {

            if (this.checkInCart <= 1)
                this.$store.dispatch("removeProduct", this.product.id)
            else
                this.$store.dispatch("decQuantity", this.product.id)

            this.$notify({
                title:'Добавление товара',
                text:'Товар удален!',
            })

        },
        loadProduct() {
            let productId = this.$route.params.productId

            this.$store.dispatch("loadProduct", {
                dataObject: {
                    productId: productId
                }
            }).then(resp => {
                this.product = resp.data

                this.$store.dispatch("addToWatch", this.product)


            })
        }
    }
}
</script>
<style>

</style>
