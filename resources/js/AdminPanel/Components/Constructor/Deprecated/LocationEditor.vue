<script setup>
import Location from "@/AdminPanel/Components/Constructor/Location/Location.vue";
import CompanyList from "@/AdminPanel/Components/Constructor/Company/CompanyList.vue";


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
                      :company="company"
                      v-on:callback="locationCallback"
            />
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
    methods: {
        loadCurrentCompany(company = null){
            this.$store.dispatch("updateCurrentCompany", {
                company: company
            }).then(()=>{
                this.company = this.getCurrentCompany
            })
        },
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
