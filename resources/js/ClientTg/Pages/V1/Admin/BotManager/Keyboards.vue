<script setup>
import KeyboardList from "@/ClientTg/Components/V1/Admin/Keyboards/KeyboardList.vue";

</script>
<template>


    <div class="card card-style">
        <div class="card-body">
            <KeyboardList
                :bot="bot"
                :select-mode="false"
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
