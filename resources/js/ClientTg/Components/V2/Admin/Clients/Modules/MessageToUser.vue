<script setup>
import BotMediaList from "@/ClientTg/Components/V1/BotMediaList.vue";
</script>

<template>
    <div>
        <button
            type="button"
            data-bs-toggle="modal" data-bs-target="#message-to-user"
            class="btn btn-outline-light text-primary w-100 p-3">Написать
            пользователю сообщение</button>


        <!-- Modal -->
        <div class="modal fade" id="message-to-user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <form v-on:submit.prevent="acceptUserInLocation"
                      class="modal-content">

                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Админ. панель</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <a href="javascript:void(0)"
                               class="btn btn-link w-100 "
                               @click="goToUserChatHistory"
                            >Глянуть историю переписки с пользователем</a>
                        </div>
                        <div class="form-floating mb-3">

                            <textarea class="form-control"
                                      placeholder="Текст сообщения"
                                      v-model="locationForm.info"
                                      id="bill-info" style="min-height:200px;" required></textarea>
                            <label for="bill-info" class="form-label">Написать сообщение</label>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   v-model="locationForm.need_media_content"
                                   type="checkbox" role="switch" id="toggle-need-media">
                            <label  for="toggle-need-media">Нужен медиа контент</label>

                        </div>


                        <div class="mb-3" v-if="locationForm.need_media_content">
                            <BotMediaList
                                :need-audio="true"
                                :need-video-note="true"
                                :need-video="true"
                                :need-photo="true"
                                v-on:select="selectMediaForMessage"
                                :selected="[locationForm.content]">

                            </BotMediaList>
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
            locationForm: {
                info: null,
                content: null,
                content_type: null,
                need_media_content: false,
            },
        }
    },
    methods: {
        acceptUserInLocation() {
            this.loading = true;
            this.$store.dispatch("userMessage", {
                dataObject: {
                    user_telegram_chat_id: this.botUser.telegram_chat_id,
                    ...this.locationForm
                }
            }).then((resp) => {
                this.loading = false
                this.locationForm.info = null
                this.locationForm.content = null
                this.locationForm.content_type = null
                this.locationForm.need_media_content = false
                this.$notify({
                    title: 'Отлично!',
                    text: 'Вы отметили пользователя в заведении и отправили ему сообщение',
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
        goToUserChatHistory() {
            this.$router.push({name: 'AdminChatLog', params: {botUserId: this.botUser.id}});
        },
        selectMediaForMessage(item) {
            this.locationForm.content = item.file_id || null
            this.locationForm.content_type = item.type || null

        },
    }
}
</script>
