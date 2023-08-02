<script setup>

import BotList from "@/AdminPanel/Components/Constructor/Bot/BotList.vue";
import Page from "@/AdminPanel/Components/Constructor/Pages/Page.vue";
import PagesList from "@/AdminPanel/Components/Constructor/Pages/PagesList.vue";

</script>
<template>
    <div class="row pb-2 pt-2">
        <div class="col-12">
            <BotList
                v-if="!load"
                :editor="true"
                v-on:callback="botListCallback"/>
        </div>

        <div class="col-12" v-if="bot">
            <PagesList
                v-if="!load&&bot"
                :bot-id="bot.id"
                :editor="true"
                v-on:callback="pageListCallback"/>
        </div>

        <div v-if="bot&&!load">
            <Page
                :page="page"
                :bot="bot"
                v-on:callback="pageCallback"/>

        </div>


    </div>
</template>
<script>

import {mapGetters} from "vuex";

export default {

    data() {
        return {
            bot: null,
            page: null,
            load: false,

        }
    },
    mounted() {
        this.loadCurrentBot()
    },
    computed: {
        ...mapGetters(['getCurrentBot']),
    },
    methods:{
        loadCurrentBot(bot = null){
            this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(()=>{
                this.bot = this.getCurrentBot
            })
        },
        botListCallback(bot) {
            this.load = true
            this.bot = bot
            this.$nextTick(() => {
                this.load = false

            })
        },
        pageListCallback(page) {
            this.load = true
            this.page = page
            this.$nextTick(() => {
                this.load = false
            });
        },
        pageCallback(page){
            this.load = true
            this.$nextTick(() => {
                this.load = false
            });
        }
    }
}
</script>
