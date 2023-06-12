<script setup>
import Company from "@/Components/Constructor/Company/CompanyForm.vue";
import CompanyList from "@/Components/Constructor/Company/CompanyList.vue";


</script>
<template>
    <div class="row">

        <div class="col-12">
            <CompanyList
                v-if="!load"
                v-on:callback="companyListCallback"/>
        </div>
        <div class="col-12">
            <Company
                v-if="!load&&company"
                :company="company"
                v-on:callback="companyCallback"/>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data(){
        return {
            load:false,
            company:null
        }
    },
    computed: {
        ...mapGetters(['getCurrentCompany']),
    },
    mounted() {
        this.loadCurrentCompany()
    },
    methods:{
        loadCurrentCompany(company = null){
            this.$store.dispatch("updateCurrentCompany", {
                company: company
            }).then(()=>{
                this.company = this.getCurrentCompany
                console.log("company", this.company)
            })
        },
        companyListCallback(company){
            this.load = true
            this.loadCurrentCompany(company)
            this.$nextTick(()=>{
                this.load = false
            })

        },
        companyCallback(company) {
            this.load = true
            this.$nextTick(()=>{
                this.load = false
            })

            document.documentElement.scrollTop = 0;
        },
    }
}
</script>
