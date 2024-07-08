<template>

    <div  class="container py-3">
        <div class="d-flex justify-content-center align-items-center" style="min-height:350px;">
            <div style="width:200px;height:200px;border-radius:50%;overflow:hidden;">
                <img v-lazy="''" class="w-100 object-fit-cover" alt="...">
            </div>
        </div>
        <h6 class="opacity-75 mb-3">Информация о профиле</h6>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Имя</span>
                <span class="text-primary fw-bold">Testoviy</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>ID</span>
                <span class="text-primary fw-bold">1234567890</span>
            </li>
            <li

                class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Телефон</span>
                <span
                    @click="sendMyNumber"
                    class="text-primary fw-bold cursor-pointer">отправить мой номер</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Приглашено друзей</span>
                <span class="text-primary fw-bold">10</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Количество заказов</span>
                <span class="text-primary fw-bold">10</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Получено CashBack</span>
                <span class="text-primary fw-bold">1000 ₽</span>
            </li>
        </ul>

        <h6 class="opacity-75 my-3">Ваш QR-код</h6>

        <img v-lazy="qr" class="img-thumbnail" alt="...">
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    computed: {
        ...mapGetters(['getSelf']),
        logo() {
            return `/images-by-bot-id/${this.currentBot.id}/${this.currentBot.image}`
        },
        self() {
            return window.self || null
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
        this.tg.BackButton.hide()
    },
    methods:{
        sendMyNumber(){
            this.tg.requestContact(()=>{
                this.$notify({
                    title: "Профиль",
                    text: "Ваш контакт успешно отправлен!",
                    type: "success"
                })
            })
        }
    }
}
</script>
