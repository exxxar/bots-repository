<script setup>
import BotListSelf from "@/ClientTg/Components/V1/Manager/Bots/BotListSelf.vue";
import CompanyList from "@/ClientTg/Components/V1/Manager/Clients/CompanyList.vue";
</script>
<template>
    <CompanyList
        visual-mode="true"
        v-on:callback="selectCompany">
    </CompanyList>
    <div class="divider divider-small my-3 bg-highlight "></div>
    <BotListSelf
        v-if="selectedCompanyId!=null&&!loading"
        :company-id="selectedCompanyId"></BotListSelf>

</template>
<script>

import {mapGetters} from "vuex";

export default {
    data() {
        return {
            selectedCompanyId: false,
            botUser: null,
            loading: false,

        }
    },
    computed: {
        ...mapGetters(['getSelf']),
        currentBot() {
            return window.currentBot
        }

    },
    watch: {
        'getSelf': function () {
            this.botUser = this.getSelf

        },
    },
    mounted() {
        if (this.getSelf) {
            this.botUser = this.getSelf
        }
    },
    methods: {
        selectCompany(company) {
            this.loading = true
            this.selectedCompanyId = company.id
            this.$nextTick(() => {
                this.loading = false
            })
            this.$botNotification.notification("Информация", "Вы успешно выбрали за основу данного клиента!")

        },
        selectBot(bot) {
            this.form.selected_bot_id = bot.id

            this.$botNotification.notification("Информация", "Вы успешно выбрали за основу данного бота!")

        },
    }
}
</script>
