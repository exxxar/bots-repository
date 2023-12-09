<script setup>
import Layout from "@/AdminPanel/Layouts/MainAdminLayout.vue";
import VisitCardConstructor from "@/AdminPanel/Components/Constructor/Deprecated/VisitCardConstructor.vue";

</script>

<template>
    <Layout :active="5" :need-menu="true">
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
