<script setup>

import BaseBotConstructor from "@/ClientTg/Components/V1/Manager/BaseBotConstructor.vue";
</script>
<template>

    <!--    todo: мои бот, мои клиенты, добавить роль is_manager, боты для примера и для копирования-->
    <!--    todo: на последнем шаге добавить выбор тарифа и оплату-->
    <!--    todo: добавить фильтрацию пользователей по разным критеряим: вип, с телефоном, в заведении и.д.-->
    <BaseBotConstructor></BaseBotConstructor>
</template>
<script>
//import baseJS from "./modules/custom.js";
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            botUser: null,
            loading: false,

        }
    },
    computed: {
        ...mapGetters(['getSelf']),
        currentBot() {
            return window.currentBot
        }

    },
    watch: {
        'getSelf': function () {
            this.botUser = this.getSelf

        },
    },
    mounted() {
        if (this.getSelf) {
            this.botUser = this.getSelf
        }
    },
    methods: {

    }
}
</script>
