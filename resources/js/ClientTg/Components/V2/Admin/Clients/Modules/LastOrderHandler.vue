<script setup>
import BotMediaList from "@/ClientTg/Components/V1/BotMediaList.vue";
</script>

<template>
    <div class="btn-group-vertical w-100 py-3">
        <button
            type="button"
            class="btn btn-outline-info text-info w-100 p-3 current-step">Передать на кухню</button>

        <button
            type="button"
            class="btn btn-outline-info text-info w-100 p-3">Передать из кухни на доставку</button>

        <button
            type="button"
            class="btn btn-outline-info text-info w-100 p-3">Доставляется</button>

        <button
            type="button"
            class="btn btn-outline-info text-info w-100 p-3">Прибыло к заказчику (доставлено)</button>

        <button
            type="button"
            class="btn btn-outline-info text-info w-100 p-3">Автоматический CashBack</button>


    </div>

</template>
<script>
export default {
    props: ["orderId", "botUser"],
    data() {
        return {
            loading: false,

        }
    },
    mounted() {

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
<style>
.current-step {
    background-image: repeating-linear-gradient(-45deg, #eee 0, #eee 15px, #fff 15px, #fff 25px);
}
</style>
