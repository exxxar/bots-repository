<script setup>

defineProps({
    bot: {
        type: Object,
    },
    bot_user: {
        type: Object,
    },
    page:{
        type: Object,
    },
 /*   slug_id: {
        type: String,
    },*/
});

import ProductList from "@/Mobile/Pages/ProductList.vue";
import Layout from "@/Mobile/Layouts/MobileLayout.vue";
</script>
<template>
    <Layout>
        <template #default>
            <notifications position="top right" width="100%" speed="10"/>
                <template  v-for="item in page.elements">
                    <div v-if="item.type==='html'" v-html="item.template"></div>
                    <ProductList v-if="item.type==='component'&&item.component==='ProductList'" ></ProductList>
                </template>

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

