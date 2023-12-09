<script setup>
import Layout from "@/AdminPanel/Layouts/MainAdminLayout.vue";
import BotSection from "@/AdminPanel/Components/Constructor/Bot/BotSection.vue";
import CompanyList from "@/AdminPanel/Components/Constructor/Company/CompanyList.vue";
import BotList from "@/AdminPanel/Components/Constructor/Bot/BotList.vue";
import BotForm from "@/AdminPanel/Components/Constructor/Bot/BotForm.vue";

</script>

<template>
    <Layout :active="1" :need-menu="false">
        <template #default>
            <div class="container-sm">
                <div class="row">
                    <div class="col-3">
                        <p>Название бота</p>
                    </div>
                    <div class="col-6">
                        <p>cashman_bot</p>
                    </div>
                    <div class="col-3">
                        <button class="btn btn-primary rounded-5 w-100">Сохранить</button>
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
            step: 0,
            bot: null,
            company: null
        }
    },
    computed: {
        ...mapGetters(['getCurrentBot', 'getCurrentCompany']),
    },
    mounted() {
        this.loadCurrentCompany()
        this.loadCurrentBot()
    },
    methods: {


    }
}
</script>
