<script setup>
import CompanyList from "@/AdminPanel/Components/Constructor/Company/CompanyList.vue";
import Bot from "@/AdminPanel/Components/Constructor/Bot/BotSection.vue";
import PagesList from "@/AdminPanel/Components/Constructor/Pages/PagesList.vue";
import Page from "@/AdminPanel/Components/Constructor/Pages/Page.vue"
</script>
<template>
    <div class="row">
        <div class="col-12">
            <h5 class="mt-2 mb-2">Найдите существующую компанию</h5>
            <CompanyList
                v-if="!load"
                v-on:callback="companyListCallback"/>

            <Bot v-if="company&&!load"
                 :company="company"
                 v-on:callback="botCallback"
            />
        </div>


    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            company: null,
            load: false,
        }
    },
    computed: {
        ...mapGetters(['getCurrentCompany']),
    },
    mounted() {
        this.loadCurrentCompany()
    },
    methods: {
        loadCurrentCompany(company = null){
            this.$store.dispatch("updateCurrentCompany", {
                company: company
            }).then(()=>{
                this.company = this.getCurrentCompany
            })
        },
        botCallback(bot){
            this.load = true
            this.bot = bot
            this.$nextTick(() => {
                this.load = false
            })
        },
        companyListCallback(company) {
            this.load = true
            this.loadCurrentCompany(company)
            this.$nextTick(() => {
                this.load = false
            })

        },

    }
}
</script>
