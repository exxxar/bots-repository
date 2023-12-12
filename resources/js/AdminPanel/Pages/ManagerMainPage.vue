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
            <div class="container" style="max-width: 1000px;">
                <div class="row">
                    <div class="col-3  d-flex align-items-center">
                        <i class="fa-solid fa-clipboard-question mr-2"></i>
                        <p class="mb-0">Название бота</p>
                        <sub><i class="fa-solid fa-pencil ml-1"></i></sub>
                    </div>
                    <div class="col-6">
                        <p class="mb-0">cashman_bot</p>
                    </div>
                    <div class="col-3 d-flex justify-content-center">
                        <button class="btn btn-primary rounded-5" style="min-width:150px;">Сохранить</button>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-3 d-flex align-items-center">
                        <i class="fa-solid fa-clipboard-question mr-2"></i>
                        <p class="mb-0">Токен</p>
                        <sub><i class="fa-solid fa-pencil ml-1"></i></sub>
                    </div>
                    <div class="col-6">
                        <p class="mb-0">*****************</p>
                    </div>
                    <div class="col-3 d-flex justify-content-center">
                        <button class="btn btn-link">Другие страницы</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                            <label for="floatingInput">Email address</label>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-12">
                        <div class="form-floating">
                            <textarea class="form-control"
                                      style="min-height:300px;"
                                      placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                            <label for="floatingTextarea">Comments</label>
                        </div>
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
