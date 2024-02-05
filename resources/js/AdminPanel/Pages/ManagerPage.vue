<script setup>
import Layout from "@/AdminPanel/Layouts/MainAdminLayout.vue";
import Manager from "@/AdminPanel/Components/Constructor/Manager/ManagerPrfileForm.vue";
</script>

<template>
    <Layout :active="0" :need-menu="true">
        <template #default>
            <div class="container">
                <div class="row mb-2">
                   <div class="col-12">
                       <Manager></Manager>
                   </div>
                </div>

            </div>
        </template>
    </Layout>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data(){
        return {
            load:false,
            step:0,
        }
    },
    computed: {
    },
    mounted() {

    },
    methods:{

    }
}
</script>
