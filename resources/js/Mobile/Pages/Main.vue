<script setup>

defineProps({
    bot: {
        type: Object,
    },
    theme:{
      type: String,
    },

});


import Layout from "@/Mobile/Layouts/MobileLayout.vue";
</script>
<template>
    <Layout>
        <template #default>
            <notifications position="top right"
                           ignoreDuplicates="true"
                           max="3"
                           width="100%" speed="10" />

            <router-view/>

        </template>
    </Layout>
</template>

<script>
import {mapGetters} from "vuex";


export default {
    computed: {
        ...mapGetters(['getSelf']),
        currentBot() {
            return window.currentBot
        },

    },
    created() {
        window.currentBot = this.bot.data
        window.theme = this.theme

        this.$notify({
            title: 'Главная',
            text: "Успешно!",
            type: "success",
        });

    },
    methods: {

    }

}
</script>


