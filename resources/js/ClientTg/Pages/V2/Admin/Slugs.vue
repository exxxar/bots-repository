<script setup>

import BotSlugList from "@/ClientTg/Components/V2/Admin/Slugs/BotSlugList.vue";


</script>
<template>
    <div class="container py-3">
        <div class="row">
            <div class="col-12">
                <BotSlugList
                    :bot="bot"
                    v-if="!load"
                />
            </div>
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
