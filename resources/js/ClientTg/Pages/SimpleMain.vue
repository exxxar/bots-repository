<script setup>
import Notifications from "@/ClientTg/Components/Modals/Notifications.vue";
import AddToCartModal from "@/ClientTg/Components/Modals/AddToCartModal.vue";
import CashBackItemInfoModal from "@/ClientTg/Components/Modals/CashBackItemInfoModal.vue";
import QrCodeModal from "@/ClientTg/Components/Modals/QrCodeModal.vue";
import EventCallbackForm from "@/ClientTg/Components/Modals/EventCallbackForm.vue";


import ShareMenuBar from "@/ClientTg/Components/Modals/ShareMenuBar.vue";
import HighlightsMenuBar from "@/ClientTg/Components/Modals/HighlightsMenuBar.vue";
import Preloader from "@/ClientTg/Components/Shop/Helpers/Preloader.vue";
import SideBar from "@/ClientTg/Components/Modals/SideBar.vue";
import SideBarAdmin from "@/ClientTg/Components/Modals/Admin/SideBarAdmin.vue";


import PageMenuModal from "@/ClientTg/Components/Modals/Admin/PageMenuModal.vue";
import RulesModal from "@/ClientTg/Components/Modals/Admin/RulesModal.vue";
import KeyboardMenuModal from "@/ClientTg/Components/Modals/Admin/KeyboardMenuModal.vue";
import TgHelperModal from "@/ClientTg/Components/Modals/Admin/TgHelperModal.vue";
import MapModal from "@/ClientTg/Components/Modals/Admin/MapModal.vue";

defineProps({
    bot: {
        type: Object,
    },
    slug_id: {
        type: String,
    },
});


import Layout from "@/ClientTg/Layouts/SimpleLayout.vue";
</script>
<template>
    <Layout>
        <template #default>
            <router-view
                :bot="bot"/>
        </template>
    </Layout>
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
    created() {
        window.currentBot = this.bot.data

        window.currentScript = this.slug_id || null

        this.$store.dispatch("loadSelf").then(() => {
            window.self = this.getSelf
        })

        this.$botNotification.success(
            "Главная",
            "Успешно!",
        );

    },
    methods: {
        open(url) {
            this.tg.openLink(url)
        },
    }

}
</script>

<style>

</style>

