<script setup>
import CompanyList from "@/Components/Constructor/CompanyList.vue";
import Bot from "@/Components/Constructor/Bot.vue";
import PagesList from "@/Components/Constructor/PagesList.vue";
import Page from "@/Components/Constructor/Page.vue"
</script>
<template>
    <div class="row">
        <div class="col-12">
            <h5 class="mt-2 mb-2">Найдите существующую компанию</h5>
            <CompanyList
                v-if="!load"
                v-on:callback="companyListCallback"/>

            <Bot v-if="company&&!load"
                 :company-id="company.id"
                 v-on:callback="botCallback"
            />
        </div>


    </div>
</template>
<script>
export default {
    data() {
        return {
            company: null,

            load: false,


        }
    },

    methods: {
        botCallback(bot){
            this.load = true
            this.bot = bot
            this.$nextTick(() => {
                this.load = false
            })
        },
        companyListCallback(company) {
            this.load = true
            this.company = company
            this.$nextTick(() => {
                this.load = false
            })

        },

    }
}
</script>
