<script setup>
import Location from "@/Components/Constructor/Location.vue";
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
            <Location v-if="company&&!load"
                      :company-id="company.id"
                      v-on:callback="locationCallback"
            />
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
        locationCallback() {
            this.step++;
            this.load = false

            document.documentElement.scrollTop = 0;
        },
    }
}
</script>
