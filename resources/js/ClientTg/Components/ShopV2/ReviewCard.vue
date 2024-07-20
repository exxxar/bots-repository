<script setup>
import Rating from "@/ClientTg/Components/Shop/Helpers/Rating.vue";
import ReviewForm from "@/ClientTg/Components/ShopV2/ReviewForm.vue";
import ProductCardSimple from "@/ClientTg/Components/ShopV2/ProductCardSimple.vue";
</script>
<template>

    <div v-if="review&&load">
        <div class="card rounded-0 border-0 w-100">
            <div class="card-body" v-if="needProduct">
                <ProductCardSimple v-if="review.product" :item="review.product"/>
            </div>
            <div class="card-body"  v-if="review.send_review_at">
                <Rating :rating="review.rating"></Rating>
                <p class="fst-italic my-2" style="word-break:break-word;">
                    {{ review.text || 'не указан' }}
                </p>
                <p class="mb-0" v-if="review.send_review_at"> Отзыва оставлен <span
                    class="text-primary fw-bold">{{$filters.currentFull( review.send_review_at)}}</span></p>
            </div>

            <div class="card-body" v-else>
                <button

                    type="button"
                    @click="showReviewsForm"
                    class="btn btn-link w-100 my-3"><i class="fa-solid fa-comment-medical mr-1"></i> Вы можете оставить отзыв</button>

            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" :id="'review-modal-info'+review.id" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ReviewForm
                            v-on:callback="callback"
                            :review="review">
                            <template #title>
                                <p v-if="review.order_id!=null&&review.product_id!=null" class="text-center"><strong>Отзыв к товару</strong></p>
                                <p v-if="review.order_id!=null&&review.product_id==null" class="text-center"><strong>Отзыв к заказу</strong></p>
                            </template>
                        </ReviewForm>

                    </div>
                </div>
            </div>
        </div>

    </div>
    </template>
<script>
export default {
    props:["modelValue","needProduct"],
    data(){
      return{
          load:true,
          review:null,
      }
    },

    mounted() {
      this.review = this.modelValue
    },
    methods:{
        callback(item){
            this.load = false
            this.review = item
            this.$nextTick(()=>{
                this.load=true
                this.$emit("update:modelValue", item)
            })

        },
        showReviewsForm() {
            const myModal = new bootstrap.Modal(document.getElementById('review-modal-info' + this.review.id), {})
            myModal.show()
        },
    }
}
</script>
