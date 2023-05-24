<script setup>
import CompanyList from "@/Components/Constructor/CompanyList.vue";
import Bot from "@/Components/Constructor/Bot.vue";

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
            />
        </div>
    </div>
</template>
<script>
export default {
    data(){
      return {
          company:null,
          load:false
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
    }
}
</script>
