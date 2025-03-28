<script setup>

defineProps({
    bot: {
        type: Object,
    },
    bot_user: {
        type: Object,
    },

});


import Layout from "@/ClientTg/Layouts/MobileLayout.vue";
</script>
<template>
    <Layout>
        <template #default>
            <notifications position="top right" width="100%" speed="10"/>

            <router-view/>
        </template>
    </Layout>
</template>

<script>
import {mapGetters} from "vuex";


export default {
    computed: {

    },
    created() {

    },
    methods: {

    }

}
</script>

<style>

</style>

