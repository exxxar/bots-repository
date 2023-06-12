<script setup>
import Layout from "@/Layouts/MainAdminLayout.vue";
import CompanyForm from "@/Components/Constructor/Company/CompanyForm.vue";
import CompanyList from "@/Components/Constructor/Company/CompanyList.vue";

</script>

<template>
    <Layout :active="3">
        <template #default>
            <div class="container">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="card text-center">
                            <div class="card-header">
                                Работа с клиентами (компаниями)
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Набор инстурментов для работы с клиентом</h5>
                                <p class="card-text"><strong>Основные функции</strong>: <em>создание и редактирование клиента</em>, <em>работа со списком заведений клиента</em></p>
                                <a href="/company-page" class="btn btn-outline-primary p-3">Перейти к разделу</a>
                            </div>
                            <div class="card-footer text-muted">
                                Обновлено 13.06.2024
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card text-center">
                            <div class="card-header">
                                Работа с ботами
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Набор инстурментов для работы с ботами</h5>
                                <p class="card-text"><strong>Основные функции</strong>: <em>создание и редактирование бота</em>,
                                    <em>работа со страницами бота</em>,
                                    <em>графическое меню бота</em>,
                                    <em>шаблоны клавиатур бота</em>,
                                    <em>скрипты в боте</em>,
                                    <em>и т.д.</em>

                                </p>
                                <a href="/bot-page" class="btn btn-outline-primary p-3">Перейти к разделу</a>
                            </div>
                            <div class="card-footer text-muted">
                                Обновлено 13.06.2024
                            </div>
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
