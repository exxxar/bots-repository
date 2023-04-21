<script setup>
import BotList from "@/Components/Constructor/BotList.vue";
import Bot from "@/Components/Constructor/Bot.vue";

</script>
<template>
    <div class="row">
        <div class="col-12">
            <BotList
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
export default {
    data(){
        return {
            load:false,
            bot:null,
        }
    },
    methods:{
        botCallback(bot){

        },
        botListCallback(bot){
            this.load = true
            console.log("select", bot)
            this.bot = bot
            this.$nextTick(()=>{
                this.load = false

            })
        }
    }
}
</script>
