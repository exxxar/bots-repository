<script setup>
import BotList from "@/Components/Constructor/BotList.vue";
import Bot from "@/Components/Constructor/Bot.vue";

</script>
<template>
    <div class="row">
        <div class="alert alert-warning" role="alert">
            <strong>Важно!</strong> новые боты начнут работать только после того, как вы обновите зависимости!
        </div>

        <div class="col-12 mb-3">
            <a
                class="btn btn-outline-success w-100"
                @click="reloadWebhooks">Обновить зависимости</a>
        </div>
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
export default {
    data(){
        return {
            load:false,
            bot:null,
        }
    },
    methods:{
        reloadWebhooks(){
            axios.get("/bot/register-webhooks").then(()=>{
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Зависимости успешно обновлены!",
                    type: 'success'
                });
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
            this.bot = bot
            this.$nextTick(()=>{
                this.load = false

            })
        }
    }
}
</script>
