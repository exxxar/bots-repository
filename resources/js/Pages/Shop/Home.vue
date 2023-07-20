<script setup>
import ProductItemMini from "@/Components/Shop/Products/ProductItemMini.vue";
import ProductItemLarge from "@/Components/Shop/Products/ProductItemLarge.vue";

</script>

<template>

    <div class="content mb-4">
        <div class="search-box bg-theme rounded-m shadow-xl bottom-0">
            <i class="fa fa-search"></i>
            <input type="text" class="border-0" placeholder="What are you looking for? (try all) " data-search>
        </div>
        <div class="search-results disabled-search-list card card-style mx-0 mt-3 px-2">
            <div class="list-group list-custom-large">
                <a href="#" data-filter-item data-filter-name="all demo smartphone apple iphone">
                    <i class="fab fa-apple color-gray2-dark"></i>
                    <span>Apple</span>
                    <strong>Works on iOS 10 and Higher</strong>
                    <i class="fa fa-angle-right mr-2"></i>
                </a>
                <a href="#" data-filter-item data-filter-name="all demo smartphone apple iphone">
                    <i class="fab fa-android color-green1-dark"></i>
                    <span>Android</span>
                    <strong>Works on Android 5.1.1 and Higher</strong>
                    <i class="fa fa-angle-right mr-2"></i>
                </a>
                <a href="#" data-filter-item data-filter-name="all demo code css3 css">
                    <i class="fab fa-css3 color-blue2-dark font-17"></i>
                    <span>CSS3 </span>
                    <strong>Beautiful Design. Simple Code.</strong>
                    <i class="fa fa-angle-right mr-2"></i>
                </a>
                <a href="#" data-filter-item data-filter-name="all demo code html5 html">
                    <i class="fab fa-html5 color-orange-dark"></i>
                    <span>HTML5 </span>
                    <strong>Powerful and Universally Compatible</strong>
                    <i class="fa fa-angle-right mr-2"></i>
                </a>
                <a href="#" data-filter-item data-filter-name="all demo support help">
                    <i class="fa fa-life-ring color-red2-dark font-17"></i>
                    <span>Support </span>
                    <strong>Elite Quality, 24/7 Support for Buyers</strong>
                    <i class="fa fa-angle-right mr-2"></i>
                </a>
                <a href="#" data-filter-item data-filter-name="all demo code js jquery java javascript">
                    <i class="fab fa-js color-yellow2-dark font-17"></i>
                    <span>JavaScript </span>
                    <strong>Clean Code, Easy to Use and Modify</strong>
                    <i class="fa fa-angle-right mr-2"></i>
                </a>
                <a href="#" data-filter-item data-filter-name="all demo support elite help documentation">
                    <i class="fa fa-file color-gray2-dark font-17"></i>
                    <span>Documentation </span>
                    <strong>Every Feature and Aspect Covered.</strong>
                    <i class="fa fa-angle-right mr-2"></i>
                </a>
            </div>
        </div>
    </div>


    <div v-if="randomProducts" class="single-slider owl-no-dots owl-carousel mt-n4 owl-loaded owl-drag">
        <ProductItemLarge :item="item" v-for="(item, index) in randomProducts"/>
    </div>

    <div class="content mb-3">
        <h5 class="float-left font-16 font-500">Случайные 20 товаров</h5>
        <a class="float-right font-12 color-highlight mt-n1" href="#/products">Посмотреть все</a>
        <div class="clearfix"></div>
    </div>

    <div v-if="randomProducts" class="double-slider owl-carousel owl-no-dots">
        <ProductItemMini :item="item" v-for="(item, index) in randomProducts"/>
    </div>


    <div class="divider divider-margins mt-4"></div>

    <div class="card preload-img" data-src="/shop/images/pictures/20s.jpg">
        <div class="card-body">
            <h4 class="color-white font-600">Why our Store?</h4>
            <p class="color-white opacity-80">
                Our store guarantees the followig perks to all it's customers. We love you all
            </p>
            <div class="card card-style ml-0 mr-0 bg-white">
                <div class="row mt-3 pt-1 mb-3">
                    <div class="col-6">
                        <i class="float-left ml-3 mr-3"
                           data-feather="globe"
                           data-feather-line="1"
                           data-feather-size="35"
                           data-feather-color="blue2-dark"
                           data-feather-bg="blue2-fade-light">
                        </i>
                        <h5 class="color-black float-left font-13 font-500 line-height-s pb-3 mb-3">Global<br>Shipping
                        </h5>
                    </div>
                    <div class="col-6 pl-0">
                        <i class="float-left ml-3 mr-3"
                           data-feather="smartphone"
                           data-feather-line="1"
                           data-feather-size="35"
                           data-feather-color="dark2-dark"
                           data-feather-bg="dark2-fade-light">
                        </i>
                        <h5 class="color-black float-left font-13 font-500 line-height-s pb-3 mb-3">24/7<br>Support</h5>
                    </div>
                    <div class="col-6">
                        <i class="float-left ml-3 mr-3"
                           data-feather="star"
                           data-feather-line="1"
                           data-feather-size="35"
                           data-feather-color="yellow1-dark"
                           data-feather-bg="yellow1-fade-light">
                        </i>
                        <h5 class="color-black float-left font-13 font-500 line-height-s">5.0<br>Rating</h5>
                    </div>
                    <div class="col-6 pl-0">
                        <i class="float-left ml-3 mr-3"
                           data-feather="truck"
                           data-feather-line="1"
                           data-feather-size="33"
                           data-feather-color="green1-dark"
                           data-feather-bg="green1-fade-light">
                        </i>
                        <h5 class="color-black float-left font-13 font-500 line-height-s">Free<br>Returns</h5>
                    </div>

                </div>
            </div>
        </div>
        <div class="card-overlay bg-highlight opacity-95"></div>
        <div class="card-overlay dark-mode-tint"></div>
    </div>


    <div class="divider divider-margins mt-4"></div>

    <div class="content mb-3">
        <h5 class="float-left font-16 font-500">Based on Your Favorites</h5>
        <a class="float-right font-12 color-highlight mt-n1" href="#">View All</a>
        <div class="clearfix"></div>
    </div>

    <div class="double-slider owl-carousel owl-no-dots">
        <div class="item bg-theme pb-3 rounded-m shadow-l">
            <div data-card-height="200" class="card mb-3 bg-14">
                <div class="card-bottom">
                    <h5 class="color-white text-center pr-2 pb-2">Sticky Mobile</h5>
                </div>
                <div class="card-overlay bg-gradient"></div>
            </div>
            <div class="d-flex px-3">
                <div>
                    <h3 class="mb-n1">$24.99</h3>
                    <span class="opacity-60">was $49.99</span>
                    <p class="mb-0">
                        <i class="fa fa-star color-yellow1-dark"></i>
                        <i class="fa fa-star color-yellow1-dark"></i>
                        <i class="fa fa-star color-yellow1-dark"></i>
                        <i class="fa fa-star color-yellow1-dark"></i>
                        <i class="fa fa-star color-yellow1-dark"></i>
                    </p>
                    <p class="color-green1-dark mb-0 font-11">Available In stock</p>
                </div>
            </div>
        </div>
        <div class="item bg-theme pb-3 rounded-m shadow-l">
            <div data-card-height="200" class="card mb-3 bg-15">
                <div class="card-bottom">
                    <h5 class="color-white text-center pr-2 pb-2">Appkit</h5>
                </div>
                <div class="card-overlay bg-gradient"></div>
            </div>
            <div class="d-flex px-3">
                <div>
                    <h3 class="mb-n1">$34.99</h3>
                    <span class="opacity-60">was $59.99</span>
                    <p class="mb-0">
                        <i class="fa fa-star color-yellow1-dark"></i>
                        <i class="fa fa-star color-yellow1-dark"></i>
                        <i class="fa fa-star color-yellow1-dark"></i>
                        <i class="fa fa-star color-yellow1-dark"></i>
                        <i class="fa fa-star color-yellow1-dark"></i>
                    </p>
                    <p class="color-yellow1-dark mb-0 font-11">Limited Stock</p>
                </div>
            </div>
        </div>
        <div class="item bg-theme pb-3 rounded-m shadow-l">
            <div data-card-height="200" class="card mb-3 bg-16">
                <div class="card-bottom">
                    <h5 class="color-white text-center pr-2 pb-2">DuoDrawer</h5>
                </div>
                <div class="card-overlay bg-gradient"></div>
            </div>
            <div class="d-flex px-3">
                <div>
                    <h3 class="mb-n1">$144.99</h3>
                    <span class="opacity-60">was $99.99</span>
                    <p class="mb-0">
                        <i class="fa fa-star color-yellow1-dark"></i>
                        <i class="fa fa-star color-yellow1-dark"></i>
                        <i class="fa fa-star color-yellow1-dark"></i>
                        <i class="fa fa-star color-yellow1-dark"></i>
                        <i class="fa fa-star color-yellow1-dark"></i>
                    </p>
                    <p class="color-red2-dark mb-0 font-11">Out of Stock</p>
                </div>
            </div>
        </div>
        <div class="item bg-theme pb-3 rounded-m shadow-l">
            <div data-card-height="200" class="card mb-3 bg-17">
                <div class="card-bottom">
                    <h5 class="color-white text-center pr-2 pb-2">EazyMobile</h5>
                </div>
                <div class="card-overlay bg-gradient"></div>
            </div>
            <div class="d-flex px-3">
                <div>
                    <h3 class="mb-n1">$154.99</h3>
                    <span class="opacity-60">was $299.99</span>
                    <p class="mb-0">
                        <i class="fa fa-star color-yellow1-dark"></i>
                        <i class="fa fa-star color-yellow1-dark"></i>
                        <i class="fa fa-star color-yellow1-dark"></i>
                        <i class="fa fa-star color-yellow1-dark"></i>
                        <i class="fa fa-star color-yellow1-dark"></i>
                    </p>
                    <p class="color-blue2-dark mb-0 font-11">Coming Soon</p>
                </div>
            </div>
        </div>
    </div>


    <div class="divider divider-margins mt-4"></div>


    <div class="card mt-4 preload-img" data-src="/shop/images/pictures/20s.jpg">
        <div class="card-body">
            <h3 class="color-white font-600">Best Priced Pack</h3>
            <p class="color-white opacity-80">
                The best value pack you can purchase for your needs created especially to suit you.
            </p>

            <div class="card rounded-m shadow-xl mb-0">
                <div class="content">
                    <div class="d-flex pb-3">
                        <div class="pr-3">
                            <h5 class="font-14 font-600 opacity-80 pb-2">Appkit Mobile Website Template and PWA. </h5>
                            <h1 class="font-24 font-700 ">$21<sup class="font-15 opacity-50">.99</sup></h1>
                        </div>
                        <div class="ml-auto">
                            <img src="/shop/images/pictures/2s.jpg" class="rounded-m shadow-xl" width="90">
                        </div>
                    </div>

                    <div class="divider mb-4"></div>

                    <div class="d-flex pb-2">
                        <div class="pr-3">
                            <h5 class="font-14 font-600 opacity-80 pb-2">6 Months Hands on Support Included in
                                Pack. </h5>
                            <h1 class="font-24 font-700 color-green1-dark">$0<sup class="font-15 opacity-50">.00</sup>
                            </h1>
                        </div>
                        <div class="ml-auto">
                            <img src="/shop/images/pictures/3s.jpg" class="rounded-m shadow-xl" width="90">
                        </div>
                    </div>

                    <div class="divider mb-4"></div>

                    <a href="#" class="btn btn-full btn-m bg-highlight font-700 text-uppercase rounded-m shadow-xl"> Add
                        to Cart</a>

                </div>
            </div>
        </div>
        <div class="card-overlay bg-highlight opacity-95"></div>
        <div class="card-overlay dark-mode-tint"></div>
    </div>

    <div class="divider divider-margins"></div>

    <div class="content mb-3">
        <h5 class="float-left font-16 font-500">Browse Our Categories</h5>
        <a class="float-right font-12 color-highlight mt-n1" href="#">View All</a>
        <div class="clearfix"></div>
    </div>

    <a href="#" data-card-height="80" class="card card-style mb-3">
        <div class="card-center">
            <h5 class="pl-3">Mobile PWA's</h5>
            <p class="pl-3 mt-n2 font-12 color-highlight mb-0">Focus on your Projects</p>
        </div>
        <div class="card-center opacity-30">
            <i class="fa fa-arrow-right opacity-50 float-right color-theme pr-3"></i>
        </div>
    </a>
    <a href="#" data-card-height="80" class="card card-style mb-3">
        <div class="card-center">
            <h5 class="pl-3">Site Templates</h5>
            <p class="pl-3 mt-n2 font-12 color-highlight mb-0">Focus on your Projects</p>
        </div>
        <div class="card-center opacity-30">
            <i class="fa fa-arrow-right opacity-50 float-right color-theme pr-3"></i>
        </div>
    </a>
    <a href="#" data-card-height="80" class="card card-style mb-4">
        <div class="card-center">
            <h5 class="pl-3">Web Themes</h5>
            <p class="pl-3 mt-n2 font-12 color-highlight mb-0">Focus on your Projects</p>
        </div>
        <div class="card-center opacity-30">
            <i class="fa fa-arrow-right opacity-50 float-right color-theme pr-3"></i>
        </div>
    </a>

    <div class="divider divider-margins"></div>


</template>
<script>

import baseJS from '@/modules/custom.js'
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            self:null,
            randomProducts: null
        }
    },
    computed: {
        ...mapGetters(['getProducts']),
        currentBot() {
            return window.currentBot;
        },
        logo(){
            return `/images-by-bot-id/${this.currentBot.id}/${this.currentBot.image}`
        },

    },
    mounted() {

        this.watchStore()
        this.loadRandomProducts()

    },
    methods: {
        watchStore(){
            this.$store.watch(
                () => this.$store.getters.getSelf,
                data => {
                    this.self = this.$store.getters.getSelf
                    baseJS.handler()
                }
            )
        },
        loadRandomProducts() {
            return this.$store.dispatch("loadRandomProducts", {
                dataObject: {
                    bot_id: this.currentBot.id
                }
            }).then(() => {
                this.randomProducts = this.getProducts

               // baseJS.handler()

            })
        }
    }
}
</script>
