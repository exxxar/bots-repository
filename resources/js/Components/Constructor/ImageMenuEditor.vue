<script setup>
import BotList from "@/Components/Constructor/Bot/BotList.vue";
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
                :bot="bot"
                v-on:callback="imageMenuCallback"/>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data(){
        return {
            load:false,
            bot:null
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
