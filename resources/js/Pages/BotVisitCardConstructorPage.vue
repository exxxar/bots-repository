<script setup>
import Layout from "@/Layouts/MainAdminLayout.vue";
import VisitCardConstructor from "@/Components/Constructor/VisitCardConstructor.vue";

</script>

<template>
    <Layout :active="2">
        <template #default>
            <VisitCardConstructor/>
        </template>
    </Layout>

</template>
<script>
import {mapGetters} from "vuex";

export default {

}
</script>
