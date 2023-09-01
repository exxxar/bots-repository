<script setup>

import BotSlugList from "@/ClientTg/Components/Admin/Slugs/BotSlugList.vue";


</script>
<template>


    <div class="card card-style" >
        <div class="card-body">
            <BotSlugList
                :bot="bot"
                v-if="!load"
            />
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
