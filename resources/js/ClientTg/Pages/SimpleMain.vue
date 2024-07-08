<script setup>

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
            <notifications position="top right" width="100%" speed="100"/>

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

        this.$notify( {
            title:'Главная',
            text:"Успешно!",
            type:"success",
        });

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

