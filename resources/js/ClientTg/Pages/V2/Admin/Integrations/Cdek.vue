<script setup>
import CdekForm from "@/ClientTg/Components/V2/Admin/Cdek/CdekForm.vue";
import CdekAdminCalcForm from "@/ClientTg/Components/V2/Admin/Cdek/CdekAdminCalcForm.vue";
</script>
<template>

    <div class="container my-3">
        <div class="row">
            <div class="col-12">
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

            <div class="col-12">
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

