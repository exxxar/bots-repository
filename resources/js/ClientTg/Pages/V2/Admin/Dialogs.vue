<script setup>
import BotDialogGroupList from "@/ClientTg/Components/V1/Admin/Dialogs/BotDialogGroupList.vue";
</script>
<template>

    <div class="card card-style">
        <div class="card-body">
            <BotDialogGroupList
                :bot="bot"
                v-if="!load"/>
        </div>
    </div>

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

            })
        },


    }
}
</script>
<style lang="scss">

</style>
