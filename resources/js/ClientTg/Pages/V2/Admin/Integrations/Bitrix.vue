<script setup>
import AmoForm from "@/ClientTg/Components/V2/Admin/Amo/AmoForm.vue";
</script>
<template>

    <AmoForm
        :bot="bot"
        :data="bot.amo"
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

