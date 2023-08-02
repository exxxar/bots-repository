<script setup>
import Layout from "@/AdminPanel/Layouts/MainAdminLayout.vue";
import VisitCardConstructor from "@/AdminPanel/Components/Constructor/VisitCardConstructor.vue";

</script>

<template>
    <Layout :active="5">
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
