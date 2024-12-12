<script setup>
import AmoForm from "@/ClientTg/Components/V2/Admin/Amo/AmoForm.vue";
</script>
<template>

    <div class="container my-3">
        <div class="row">
            <div class="col-12">
                <AmoForm
                    :bot="bot"
                    :data="bot.amo"
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

                this.amo = this.bot.amo || null


            })
        },
    }
}
</script>

