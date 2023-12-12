<script setup>
import YClientsForm from "@/ClientTg/Components/Admin/YClients/YClientsForm.vue";


</script>
<template>
    <YClientsForm
        :bot="bot"
        :data="bot.y_clients"
        v-if="!load&&bot"
    />

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

            })
        },
    }
}
</script>

