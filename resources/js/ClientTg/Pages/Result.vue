<script setup>
defineProps({
    message: {
        type: String
    },
    data: {
        type: String
    }
});

import Layout from "ClientTg@/Layouts/ShopLayout.vue";
import ReturnToBot from "@/ClientTg/Components/Shop/Helpers/ReturnToBot.vue";
</script>
<template>
    <Layout>

        <template #default>
            <div class="d-flex justify-content-center align-items-center w-100" style="height: 100vh;">
                <div class="card card-style bg-8 w-100 px-3" data-card-height="250" style="height: 250px;">
                    <div class="card-center">
                        <h2 class="color-white font-700 text-center mb-2">Результат</h2>
                        <p class="color-white boxed-text-l text-center opacity-60 mt-n1 mb-3">
                            {{ message }}

                        </p>

                        <p
                            v-if="statistic"
                            class="color-white boxed-text-l text-center opacity-60 mt-n1 mb-3 d-flex flex-column">
                            <span>Всего товаров затронуто <strong>{{ statistic.total_product_count || 0 }}</strong></span>
                            <span>Создано новых товаров <strong>{{ statistic.created_product_count || 0 }}</strong></span>
                            <span>Обновлено товаров <strong>{{ statistic.updated_product_count || 0 }}</strong></span>
                        </p>

                        <div class="px-3">
                            <ReturnToBot/>
                        </div>

                    </div>
                    <div class="card-overlay bg-black opacity-80"></div>
                </div>

            </div>
        </template>
    </Layout>


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
    },
    mounted() {
        if (this.data)
            this.statistic = JSON.parse(this.data)
    },
    methods: {}
}
</script>
