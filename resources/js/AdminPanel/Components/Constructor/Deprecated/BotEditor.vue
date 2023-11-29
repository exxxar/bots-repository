<script setup>
import BotList from "@/AdminPanel/Components/Constructor/Bot/BotList.vue";
import Bot from "@/AdminPanel/Components/Constructor/Bot/BotSection.vue";

</script>
<template>
    <div class="row">
        <div class="col-12">
            <BotList
                v-if="!load"
                :editor="true"
                v-on:callback="botListCallback"/>
        </div>
        <div class="col-12">
            <Bot v-if="bot&&!load"
                 :bot="bot"
                 :editor="true"
                 v-on:callback="botCallback"
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
            bot:null,
        }
    },
    computed: {
        ...mapGetters(['getCurrentBot']),
    },
    mounted() {
        this.loadCurrentBot()
    },
    methods:{
        loadCurrentBot(bot = null){
            this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(()=>{
                this.bot = this.getCurrentBot
            })
        },
        botCallback(bot){
            this.load = true
            this.bot = null
            this.$nextTick(()=>{
                this.load = false

            })
        },
        botListCallback(bot){
            this.load = true

            this.loadCurrentBot(bot)

            this.$nextTick(()=>{
                this.load = false
            })
        }
    }
}
</script>
