<script setup>

defineProps({
    bot: {
        type: Object,
    },
    bot_user: {
        type: Object,
    },
    pages:{
        type: Array,
    },
 /*   slug_id: {
        type: String,
    },*/
});


import Layout from "@/ClientTg/Layouts/MobileLayout.vue";
</script>
<template>
    <Layout>
        <template #default>
            <notifications position="top right" width="100%" speed="10"/>

            <template v-for="page in pages">
                <template  v-for="item in page.elements">
                    <div v-html="item.template"></div>
                </template>
            </template>


            {{pages}}
<!--            <router-view
                :bot="bot"/>-->
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

