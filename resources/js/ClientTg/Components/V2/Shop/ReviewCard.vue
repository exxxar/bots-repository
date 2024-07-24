<script setup>
import Rating from "@/ClientTg/Components/V1/Shop/Helpers/Rating.vue";
import ReviewForm from "@/ClientTg/Components/V2/Shop/ReviewForm.vue";
import ProductCardSimple from "@/ClientTg/Components/V2/Shop/ProductCardSimple.vue";
</script>
<template>

    <div v-if="review&&load" class="w-100">
        <div class="card rounded-0 border-0 w-100">
            <div class="card-body" v-if="needProduct">
                <ProductCardSimple

                    v-if="review.product" :item="review.product"/>
            </div>
            <div class="card-body p-0" v-if="review.send_review_at">
                <Rating :rating="review.rating"></Rating>
                <p class="fst-italic my-2" style="word-break:break-word;">
                    {{ review.text || 'не указан' }}
                </p>
                <p class="mb-0" v-if="review.send_review_at"> Отзыва оставлен <span
                    class="text-primary fw-bold">{{ $filters.currentFull(review.send_review_at) }}</span></p>

                <div class="d-flex justify-content-center">
                    <div class="form-check my-2">
                        <input class="form-check-input"
                               v-model="need_user_notify"
                               type="checkbox" :id="'notify-user-for-review-'+review.id">
                        <label class="form-check-label" :for="'notify-user-for-review-'+review.id">
                            Оповестить пользователя
                        </label>
                    </div>
                </div>
                <button
                    v-if="isAdmin"
                    @click="removeReview"
                    type="button" class="btn btn-primary w-100">Сбросить отзыв
                </button>
            </div>

            <div class="card-body p-0" v-else>
                <button
                    v-if="!isAdmin"
                    type="button"
                    @click="showReviewsForm"
                    class="btn btn-link w-100"><i class="fa-solid fa-comment-medical mr-1"></i> Вы можете оставить отзыв
                </button>
                <p v-if="isAdmin">
                    Пользователь еще не оставил отзыв!
                </p>
                <button
                    v-if="isAdmin"
                    @click="notifyUser"
                    :disabled="spent_time_counter>0"
                    type="button" class="btn btn-primary w-100">
                     <span
                         v-if="spent_time_counter<=0"
                         class="color-white">Напомнить</span>
                    <span
                        v-else
                        class="color-white">Осталось ждать {{ spent_time_counter || 0 }} сек.</span>
                </button>
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
                                <p v-if="review.order_id!=null&&review.product_id!=null" class="text-center"><strong>Отзыв
                                    к товару</strong></p>
                                <p v-if="review.order_id!=null&&review.product_id==null" class="text-center"><strong>Отзыв
                                    к заказу</strong></p>
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
    props: ["modelValue", "needProduct", "isAdmin"],
    data() {
        return {
            need_user_notify:false,
            load: true,
            review: null,
            spent_time_counter: 0,
        }
    },

    mounted() {
        this.review = this.modelValue

        if (localStorage.getItem("cashman_review_card_notify_counter") != null) {
            this.is_requested = true;
            this.startTimer(localStorage.getItem("cashman_review_card_notify_counter") || 0)
        }
    },
    methods: {
        removeReview() {

            this.review.send_review_at = null
            this.review.text = null
            this.review.rating = 5
            this.review.need_user_notify = this.need_user_notify || false

            this.$store.dispatch("storeReview", {
                reviewForm: this.review
            }).then(() => {
                this.$notify({
                    title: "Отзывы",
                    text: "Вы успешно удалили отзыв",
                    type: "success"
                })
            }).catch(() => {
                this.$notify({
                    title: "Отзывы",
                    text: "Ошибка удаления отзыва",
                    type: "error"
                })
            })
        },
        callback(item) {
            this.load = false
            this.review = item
            this.$nextTick(() => {
                this.load = true
                this.$emit("update:modelValue", item)
            })

        },
        showReviewsForm() {
            const myModal = new bootstrap.Modal(document.getElementById('review-modal-info' + this.review.id), {})
            myModal.show()
        },
        notifyUser() {

            this.$store.dispatch("notifyUserForReview", {
                reviewForm: this.review
            }).then(()=>{
                this.$notify({
                    title: "Отзывы",
                    text: "Вы успешно отправили пользователю напоминание",
                    type: "success"
                })
            }).catch(()=>{
                this.$notify({
                    title: "Отзывы",
                    text: "Ошибка напоминания",
                    type: "error"
                })
            })


            this.startTimer();
        },
        startTimer(time) {
            this.spent_time_counter = time != null ? Math.min(time, 10) : 10;

            let counterId = setInterval(() => {
                    if (this.spent_time_counter > 0)
                        this.spent_time_counter--
                    else {
                        clearInterval(counterId)
                        this.is_requested = false
                        this.spent_time_counter = null
                    }
                    localStorage.setItem("cashman_review_card_notify_counter", this.spent_time_counter)
                }, 1000
            )
        },
    }
}
</script>
