<script setup>
import Layout from "@/AdminPanel/Layouts/MainAdminLayout.vue";
import Manager from "@/AdminPanel/Components/Constructor/Manager/ManagerPrfileForm.vue";
</script>

<template>
    <Layout :active="0" :need-menu="false">
        <template #default>
            <div class="container p-0 p-md-2">
                <div class="row mb-2">
                   <div class="col-12">
                       <Manager
                           :bot="currentBot"></Manager>
                   </div>
                </div>

            </div>
        </template>
    </Layout>

</template>
<script>
import {mapGetters} from "vuex";

export default {

    data() {
        return {
            load: false,
        }
    },
    computed: {
        currentBot(){
            return window.currentBot
        }
    },
    mounted() {

    },
    methods: {




    }
}
</script>
