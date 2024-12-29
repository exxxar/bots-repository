<script setup>
import CdekForm from "@/ClientTg/Components/V2/Admin/Cdek/CdekForm.vue";
import CdekAdminCalcForm from "@/ClientTg/Components/V2/Admin/Cdek/CdekAdminCalcForm.vue";
</script>
<template>

    <div class="container my-3">
        <div class="row">
            <div class="col-12">
                <ul class="nav justify-content-center nav-tabs my-2">
                    <li class="nav-item">
                        <a class="nav-link"
                           @click="tab=0"
                           v-bind:class="{'active':tab===0}"
                           aria-current="page" href="javascript:void(0)">Настройка</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           @click="tab=1"
                           v-bind:class="{'active':tab===1}"
                           href="javascript:void(0)">Калькулятор</a>
                    </li>

                </ul>
            </div>
            <div class="col-12" v-if="tab===0">
                <CdekForm
                    :bot="bot"
                    v-if="!load&&bot"
                />
                <div class="alert alert-light" v-else>
                    <p class="text-center">Загружаем данные...</p>
                    <div class="d-flex justify-content-center w-100">
                        <div class="spinner-border color-orange-dark" role="status">
                            <span class="sr-only">Загрузка...</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12" v-if="tab===1">
                <CdekAdminCalcForm></CdekAdminCalcForm>
            </div>
        </div>
    </div>



</template>
<script>

import {mapGetters} from "vuex";

export default {
    data() {
        return {
            tab:0,
            load: false,
            bot: null,
        }
    },

    mounted() {

        this.loadBotAdminConfig();

    },
    methods: {
        loadBotAdminConfig() {
            this.$store.dispatch("loadBotAdminConfig").then((resp) => {
                this.bot = resp.data

                this.cdek = this.bot.cdek || null


            })
        },
    }
}
</script>

