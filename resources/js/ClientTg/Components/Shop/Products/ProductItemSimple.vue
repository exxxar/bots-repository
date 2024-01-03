<script setup>
import Rating from "ClientTg@/Components/Shop/Helpers/Rating.vue";
</script>
<template>
    <div
        v-if="displayType==0"
        class="d-flex pb-2">
        <div class="mr-auto" style="max-height: 100px;">
            <img v-lazy="item.images[0]"
                 style="object-fit: cover;height: 100%;"
                 class="rounded-m shadow-xl" width="110">
<!--            <a href="#" data-menu="cart-item-edit"
               class="color-highlight mt-n5 py-3 pl-2 d-block font-11"><i class="fa-regular fa-share-from-square  pl-2 pr-1"></i>Подробнее</a>-->
        </div>
        <div class="ml-auto w-100 pl-3">
            <h5 class="font-14 font-600 opacity-80 pb-2">{{item.title || 'не указано'}}</h5>

            <div class="clearfix"></div>
            <h3 class="font-23 font-700 float-left pt-2 ">₽{{item.current_price || 0}}<sup class="font-15 opacity-50">.00</sup></h3>
            <div class="float-right">
                <button
                    @click="addToCart"
                    class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-green2-dark position-relative">
                    <i class="fa-solid fa-cart-plus"></i>
                    <span class="in-cart-count" v-if="inCart(item.id)>0">{{inCart(item.id)}}</span>
                </button>
            </div>
        </div>
    </div>

    <div
        v-if="displayType==1"
        class="d-flex rounded-m shadow-xs flex-column mb-3">
        <div class="mr-auto w-100">
            <img v-lazy="item.images[0]"
                 style="object-fit: cover;"
                 class="rounded-m w-100">
            <!--            <a href="#" data-menu="cart-item-edit"
                           class="color-highlight mt-n5 py-3 pl-2 d-block font-11"><i class="fa-regular fa-share-from-square  pl-2 pr-1"></i>Подробнее</a>-->
        </div>
        <div class="ml-auto w-100 px-3">
            <h5 class="font-14 font-600 opacity-80 pb-2">{{item.title || 'не указано'}}</h5>
            <a
                @click="item.simplify=true"
                v-if="!item.simplify"
                href="javascript:void(0)"
               class="btn btn-m btn-full rounded-sm font-900 shadow-sm text-uppercase collapsed mb-2" aria-expanded="false">
                Показать описание
            </a>

<!--            <div v-if="item.simplify">
                <p class="mb-n1 font-10">Рейтинг</p>
                <h6 class="float-left">{{item.rating}}</h6>
                <Rating :rating="item.rating"/>
            </div>-->
            <p v-if="item.simplify">{{item.description || 'Нет описания'}}</p>

            <div v-if="item.images.length>1&&item.simplify">

               <div class="row text-center row-cols-3 mb-0">
                   <a class="col mb-2"
                      data-lightbox="gallery-1"
                      v-for="(img, index) in item.images"
                      :href="img" :title="item.title">
                       <img v-lazy="img" :data-src="img"
                            class="preload-img img-fluid rounded-xs" alt="img">

                   </a>
               </div>




            </div>

            <a
                @click="item.simplify=false"
                v-if="item.simplify"
                href="javascript:void(0)"
                class="btn btn-m btn-full rounded-sm font-900 shadow-sm text-uppercase collapsed mb-2" aria-expanded="false">
                Скрыть описание
            </a>

            <div class="clearfix"></div>
            <h3 class="font-23 font-700 float-left pt-2 ">₽{{item.current_price || 0}}<sup class="font-15 opacity-50">.00</sup></h3>
            <div class="float-right">

                <button
                    @click="addToCart"
                    class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-green2-dark position-relative">
                    <i class="fa-solid fa-cart-plus"></i>
                    <span class="in-cart-count" v-if="inCart(item.id)>0">{{inCart(item.id)}}</span>
                </button>
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
        }
    },
    methods:{
        addToCart(){
            this.$cart.add(this.item)
        },
        goToProduct(){
            this.$router.push({ name: 'product', params: { productId: this.item.id } })
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
