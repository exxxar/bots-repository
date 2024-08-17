<script setup>
import FrontPadForm from "@/ClientTg/Components/V2/Admin/FrontPad/FrontPadForm.vue";


</script>
<template>

    <FrontPadForm
        :bot="bot"
        :data="bot.frontPad"
        v-if="!load&&bot"
    />


</template>
<script>

import {mapGetters} from "vuex";

export default {
    data() {
        return {

            load: false,
            bot: null,
        }
    },

    mounted() {

        this.loadBotAdminConfig();

    },
    methods: {
        loadBotAdminConfig() {
            this.$store.dispatch("loadBotAdminConfig").then((resp) => {
                this.bot = resp.data

                this.amo = this.bot.amo


            })
        },
    }
}
</script>

