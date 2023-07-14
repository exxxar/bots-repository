<template>
    <div class="d-flex pb-2">
        <div class="mr-auto" @click="goToProduct">
            <img v-lazy="item.images[0]" class="rounded-m shadow-xl" width="110">
            <a href="#" data-menu="cart-item-edit"
               class="color-highlight mt-n5 py-3 pl-2 d-block font-11"><i class="fa-regular fa-share-from-square  pl-2 pr-1"></i>Подробнее</a>
        </div>
        <div class="ml-auto w-100 pl-3">
            <h5 class="font-14 font-600 opacity-80 pb-2">{{item.title}}</h5>
            <div class="clearfix"></div>
            <h3 class="font-23 font-700 float-left pt-2 ">₽{{item.current_price || 0}}<sup class="font-15 opacity-50">.00</sup></h3>
            <div class="float-right">
                <button
                    @click="addToCart"
                    class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-green2-dark"><i class="fa-solid fa-cart-plus"></i></button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["item"],
    data(){
        return {
            showCart:false
        }
    },
    computed:{
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
