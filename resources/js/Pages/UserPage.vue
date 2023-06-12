<script setup>
import Layout from "@/Layouts/MainAdminLayout.vue";
import CompanyForm from "@/Components/Constructor/Company/CompanyForm.vue";
import CompanyList from "@/Components/Constructor/Company/CompanyList.vue";

</script>

<template>
    <Layout :active="2">
        <template #default>
            <div class="container">
                <div class="row">
                    <div class="alert alert-danger" role="alert">
                       Данный раздел находится в разработке
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
        ...mapGetters(['getCurrentCompany']),
    },
    mounted() {

    },
    methods:{

    }
}
</script>
