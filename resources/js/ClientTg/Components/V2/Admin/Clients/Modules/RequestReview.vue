<script setup>
import BotMediaList from "@/ClientTg/Components/V1/BotMediaList.vue";
</script>

<template>
    <div>
        <button
            type="button"
            data-bs-toggle="modal" data-bs-target="#request-user-review"
            class="btn btn-outline-light text-primary w-100 p-3">Запросить оценку</button>


        <!-- Modal -->
        <div class="modal fade" id="request-user-review" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <form v-on:submit.prevent="requestReview"
                      class="modal-content">

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Админ. панель</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="form-floating mb-3">

                            <textarea class="form-control"
                                      placeholder="Текст сообщения"
                                      v-model="reviewForm.message"
                                      id="bill-info" style="min-height:200px;"></textarea>
                            <label for="bill-info" class="form-label">Сообщение для пользователя</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button
                            :disabled="loading"
                            type="submit"
                            class="btn btn-primary w-100 p-3">
                            Отправить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
   </template>
<script>
export default {
    props: ["botUser"],
    data() {
        return {
            loading: false,
            reviewForm: {
                message:null
            },
        }
    },
    methods: {
        requestReview() {
            this.loading = true;
            this.$store.dispatch("userReviewRequest", {
                dataObject: {
                    telegram_chat_id: this.botUser.telegram_chat_id,
                    ...this.reviewForm
                }
            }).then((resp) => {
                this.loading = false
                this.reviewForm.message = null

                this.$notify({
                    title: 'Отлично!',
                    text: 'Вы запросили у пользователя оставить отзыв!',
                    type: 'success'
                })
            }).catch(() => {
                this.loading = false
                this.$notify({
                    title: 'Упс...!',
                    text: 'Ошибочка...',
                    type: 'error'
                })
            })
        },

    }
}
</script>
