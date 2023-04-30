<script setup>
import BotList from "@/Components/Constructor/BotList.vue";
import ImageMenu from "@/Components/Constructor/ImageMenu.vue";


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
            <ImageMenu
                v-if="bot&&!load"
                :bot-id="bot.id"
                v-on:callback="imageMenuCallback"/>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            load:false,
            bot:null
        }
    },
    methods:{
        imageMenuCallback(menus){
            this.load = true
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
