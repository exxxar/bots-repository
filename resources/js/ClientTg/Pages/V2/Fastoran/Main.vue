<script setup>

</script>

<template>
    <div class="container py-3">

    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {

        }
    },
    computed: {
        ...mapGetters(['getSelf']),
        self() {
            return this.getSelf
        },
        tg() {
            return window.Telegram.WebApp;
        },
        tgUser() {
            const urlParams = new URLSearchParams(this.tg.initData);
            return JSON.parse(urlParams.get('user'));
        },
        currentBot() {
            return window.currentBot
        },
        qr() {
            return "https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=" + this.link
        },
        link() {
            return "https://t.me/" + this.currentBot.bot_domain + "?start=" + btoa("001" + this.self.telegram_chat_id);
        }
    },


    mounted() {
        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })



    },
    methods: {
        submitProfile() {
            this.$store.dispatch('updateProfile', {
                botUserForm: this.botUserForm
            }).then(() => {
                this.$notify({
                    title: "Редактирование данных",
                    text: "Данные успешно обновлены!",
                    type: "success"
                })
                window.location.reload()
            }).catch(() => {
                this.$notify({
                    title: "Редактирование данных",
                    text: "Ошибка обновления данных",
                    type: "error"
                })
            })
        },

    }
}
</script>
