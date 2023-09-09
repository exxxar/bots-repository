<script setup>
defineProps({
    message: {
        type: String
    }
});

import CallbackForm from "@/ClientTg/Components/Shop/CallbackForm.vue";
import ReturnToBot from "@/ClientTg/Components/Shop/Helpers/ReturnToBot.vue";
import Layout from "@/ClientTg/Layouts/ShopLayout.vue";
</script>
<template>

    <Layout>

        <template #default>

            <div class="card card-style bg-8" data-card-height="250" style="height: 250px;">
                <div class="card-center">
                    <h2 class="color-white font-700 text-center mb-2">Результат</h2>
                    <p class="color-white boxed-text-l text-center opacity-60 mt-n1 mb-3">
                        {{ message }}
                    </p>

                    <ReturnToBot/>
                </div>
                <div class="card-overlay bg-black opacity-80"></div>
            </div>

            <CallbackForm/>
        </template>
    </Layout>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {}
    },
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
        tgUser() {
            const urlParams = new URLSearchParams(this.tg.initData);
            return JSON.parse(urlParams.get('user'));
        }
    },
    mounted() {

    },
    methods: {}
}
</script>
