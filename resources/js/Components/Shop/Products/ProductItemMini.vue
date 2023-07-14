<script setup>
import Rating from "@/Components/Shop/Helpers/Rating.vue";
</script>
<template>
    <div class="item bg-theme pb-3 rounded-m shadow-l">
        <div data-card-height="200" class="card mb-3"
             @click="goToProduct"
             style="height: 200px;"
             v-bind:style="{'background-image': 'url('+(item.images[0]||'')+')' }"
        >
            <div class="card-bottom">
                <h5 class="color-white text-center pr-2 pb-2">{{ item.title || 'Без названия' }}</h5>
            </div>
            <div class="card-overlay bg-gradient"></div>
        </div>
        <div class="d-flex px-3">
            <div>
                <h3 class="mb-n1">{{ item.current_price || 0 }}₽</h3>
                <span class="opacity-60" v-if="item.old_price>0">было {{ item.old_price || 0 }}₽</span>
                <Rating :rating="item.rating"/>
                <p class="color-red1-dark mb-0 font-11" v-if="item.in_stop_list_at">Нет в наличии</p>
                <button type="button"
                        @click="addToCart"
                        v-bind:class="{'bg-gray':item.in_stop_list_at!=null,'bg-highlight':item.in_stop_list_at==null}"
                        :disabled="item.in_stop_list_at!=null"
                        class="btn btn-xs btn-center-xs rounded-s shadow-s text-uppercase font-900">
                    <i class="fa-solid fa-cart-plus"></i>
                </button>
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
