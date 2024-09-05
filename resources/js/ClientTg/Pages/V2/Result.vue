<script setup>
defineProps({
    message: {
        type: String
    },
    data: {
        type: String
    }
});

import Layout from "@/ClientTg/Layouts/V1Layout.vue";
import ReturnToBot from "@/ClientTg/Components/V1/Shop/Helpers/ReturnToBot.vue";
</script>
<template>
    <div class="container">

        <div class="row">
            <div class="col-12">
                <h2 class="text-primary fw-bold text-center mb-2">Результат</h2>
                <p class="text-primary mb-3">
                    {{ message }}
                </p>

                <p
                    class="text-primary d-flex flex-column">
                    <span>Всего товаров затронуто <strong>{{ statistic.total_product_count || 0 }}</strong></span>
                    <span>Совпадений товара с FrontPad <strong>{{ statistic.total_frontpad_count || 0 }}</strong></span>
                    <span>Создано новых товаров <strong>{{ statistic.created_product_count || 0 }}</strong></span>
                    <span>Обновлено товаров <strong>{{ statistic.updated_product_count || 0 }}</strong></span>
                </p>

                <template
                    v-if="statistic.front_pad_not_found_items.length>0">
                    <h6>Данные товары не совпадают с frontPad</h6>
                    <ul
                        class="list-group">
                        <li
                            class="list-group-item"
                            v-for="item in statistic.front_pad_not_found_items">
                            {{ item }}
                        </li>
                    </ul>
                </template>

            </div>
        </div>

    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            statistic: {
                total_product_count: 0,
                created_product_count: 0,
                total_frontpad_count: 0,
                updated_product_count: 0,
                front_pad_not_found_items: [],
            }
        }
    },
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
    },
    mounted() {
        if (this.data) {
            let data = JSON.parse(this.data)
            this.statistic.total_frontpad_count = data.total_product_count || 0
            this.statistic.created_product_count = data.created_product_count || 0
            this.statistic.total_frontpad_count = data.total_frontpad_count || 0
            this.statistic.updated_product_count = data.updated_product_count || 0
            this.statistic.front_pad_not_found_items = data.front_pad_not_found_items || []
        }

    },
    methods: {}
}
</script>
