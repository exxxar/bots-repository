<script setup>
import Layout from "@/Layouts/MainAdminLayout.vue";
import CompanyForm from "@/Components/Constructor/Company/CompanyForm.vue";
import CompanyList from "@/Components/Constructor/Company/CompanyList.vue";

</script>

<template>
    <Layout :active="0">
        <template #default>
            <div class="container">
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <button type="button"
                                    @click="step=0"
                                    v-bind:class="{'btn-primary':step===0,'btn-outline-primary':step!==0}"
                                    class="btn">Создание клиента</button>
                            <button type="button"
                                    @click="step=1"
                                    v-bind:class="{'btn-primary':step===1,'btn-outline-primary':step!==1}"
                                    class="btn">Поиск клиента</button>
                            <button type="button"
                                    :disabled="!company"
                                    @click="step=2"
                                    v-bind:class="{'btn-primary':step===2,'btn-outline-primary':step!==2}"
                                    class="btn">Редактирование клиента</button>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="step===0">
                    <div class="col-12">
                        <CompanyForm
                            v-if="!load"
                            v-on:callback="companyCallback"/>
                    </div>
                </div>

                <div class="row" v-if="step===1">
                    <div class="col-12">
                        <CompanyList
                            v-if="!load"
                            v-on:callback="companyListCallback"/>
                    </div>
                </div>

                <div class="row" v-if="step===2">
                    <div class="col-12">
                        <CompanyForm
                            v-if="!load&&company"
                            :company="company"
                            :editor="true"
                            v-on:callback="companyCallback"/>
                    </div>
                </div>
            </div>
        </template>
    </Layout>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data(){
        return {
            load:false,
            step:0,
            company: null
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
            })
        },
        companyListCallback(company){
            this.load = true
            this.loadCurrentCompany(company)
            this.step = 2
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
