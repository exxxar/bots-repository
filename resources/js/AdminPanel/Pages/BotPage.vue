<script setup>
import Layout from "@/AdminPanel/Layouts/MainAdminLayout.vue";
import BotForm from "@/AdminPanel/Components/Constructor/Bot/BotForm.vue";
import CompanyList from "@/AdminPanel/Components/Constructor/Company/CompanyList.vue";
import BotList from "@/AdminPanel/Components/Constructor/Bot/BotList.vue";

</script>

<template>
    <Layout :active="1">
        <template #default>
            <div class="container">
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <button type="button"
                                    @click="step=0"
                                    v-bind:class="{'btn-primary':step===0,'btn-outline-primary':step!==0}"
                                    class="btn">Создание бота
                            </button>
                            <button type="button"
                                    @click="step=1"
                                    v-bind:class="{'btn-primary':step===1,'btn-outline-primary':step!==1}"
                                    class="btn">Поиск бота
                            </button>
                            <button type="button"
                                    :disabled="!bot"
                                    @click="step=2"
                                    v-bind:class="{'btn-primary':step===2,'btn-outline-primary':step!==2}"
                                    class="btn">Редактирование бота
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="step===0">
                    <div class="col-12" v-if="!company">
                        <CompanyList
                            v-if="!load"
                            v-on:callback="companyListCallback"/>
                    </div>
                    <div class="col-12">
                        <BotForm v-if="company&&!load"
                                 :company="company"
                        />
                    </div>
                </div>

                <div class="row" v-if="step===1">
                    <BotList
                        v-if="!load"
                        :editor="true"
                        v-on:callback="botListCallback"/>
                </div>

                <div class="row" v-if="step===2">
                    <BotForm v-if="bot&&!load"
                             :bot="bot"
                             :editor="true"
                    />
                </div>
            </div>
        </template>
    </Layout>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            load: false,
            step: 0,
            bot: null,
            company: null
        }
    },
    computed: {
        ...mapGetters(['getCurrentBot', 'getCurrentCompany']),
    },
    mounted() {
        this.loadCurrentCompany()
        this.loadCurrentBot()

        window.addEventListener('store_current_bot-change-event', (event) => {
            this.bot = this.getCurrentBot
            this.step = this.bot ? 2 : 1;
        });

        window.addEventListener('store_current_company-change-event', (event) => {
            this.company = this.getCurrentCompany
        });
    },
    methods: {
        loadCurrentBot(bot = null) {
            this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        loadCurrentCompany(company = null) {
            this.$store.dispatch("updateCurrentCompany", {
                company: company
            }).then(() => {
                this.company = this.getCurrentCompany
            })
        },
        companyListCallback(company) {
            this.load = true
            this.loadCurrentCompany(company)
            this.$nextTick(() => {
                this.load = false
            })

        },
        botListCallback(bot) {
            this.load = true

            this.step = 2
            this.loadCurrentBot(bot)

            this.$nextTick(() => {
                this.load = false
            })
        }

    }
}
</script>
