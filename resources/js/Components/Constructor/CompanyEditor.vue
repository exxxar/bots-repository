<script setup>
import Company from "@/Components/Constructor/Company.vue";
import CompanyList from "@/Components/Constructor/CompanyList.vue";


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
                v-if="!load"
                :company="company"
                v-on:callback="companyCallback"/>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            load:false,
            company:null
        }
    },
    methods:{
        companyListCallback(company){
            this.load = true
            this.company = company
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
