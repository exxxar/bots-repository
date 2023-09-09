<script setup>
defineProps({
    message: {
        type: String
    },
    data: {
        type: String
    }
});


import ReturnToBot from "@/ClientTg/Components/Shop/Helpers/ReturnToBot.vue";
</script>
<template>

    <div class="d-flex justify-content-center align-items-center w-100" style="height: 100vh;">
        <div class="card card-style bg-8 w-100 px-3" data-card-height="250" style="height: 250px;">
            <div class="card-center">
                <h2 class="color-white font-700 text-center mb-2">Результат</h2>
                <p class="color-white boxed-text-l text-center opacity-60 mt-n1 mb-3">
                    {{ message }}

                </p>

                <p
                   v-if="statistic"
                    class="color-white boxed-text-l text-center opacity-60 mt-n1 mb-3">
                    <span>Всего товаров затронуто {{statistic.total_product_count || 0}}</span>
                    <span>Созданое новых товаров {{statistic.created_product_count || 0}}</span>
                    <span>Обновелно товаров {{statistic.updated_product_count || 0}}</span>
                </p>

                <ReturnToBot/>
            </div>
            <div class="card-overlay bg-black opacity-80"></div>
        </div>

    </div>





</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            statistic: null
        }
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
        if (this.data)
        this.statistic = JSON.parse(this.data)
    },
    methods: {}
}
</script>
