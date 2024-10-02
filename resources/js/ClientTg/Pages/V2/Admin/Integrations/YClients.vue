<script setup>
import YClientsForm from "@/ClientTg/Components/V1/Admin/YClients/YClientsForm.vue";


</script>
<template>

    <div class="container my-3">
        <div class="row">
            <div class="col-12">
                <YClientsForm
                    :bot="bot"
                    :data="bot.y_clients"
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

export default {
    data() {
        return {
            load: false,
            bot: null,
            y_clients:null
        }
    },

    mounted() {

        this.loadBotAdminConfig();

    },
    methods: {
        loadBotAdminConfig() {
            this.$store.dispatch("loadBotAdminConfig").then((resp) => {
                this.bot = resp.data
                this.y_clients = this.bot.y_clients

                this.$notify({
                    title:"Работа с YClients",
                    text:"Данные успешно обновлены",
                    type:"success",
                });
            })
        },
    }
}
</script>

