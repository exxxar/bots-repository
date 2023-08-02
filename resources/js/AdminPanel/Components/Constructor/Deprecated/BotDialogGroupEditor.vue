<script setup>
import BotDialogGroupList from "@/AdminPanel/Components/Constructor/Dialogs/BotDialogGroupList.vue";
import BotList from "@/AdminPanel/Components/Constructor/Bot/BotList.vue";

</script>
<template>
    <div class="row">

        <div class="col-12">
            <BotList
                v-if="!load"
                :editor="true"
                v-on:callback="botListCallback"/>
        </div>

        <div class="col-12" v-if="bot">
            <BotDialogGroupList
                :bot="bot"
                v-if="!load"
                v-on:callback="dialogGroupListCallback"/>


        </div>
        <div class="col-12">

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
        dialogGroupListCallback(company){
            this.load = true
            this.company = company
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
     /*   locationCallback() {
            this.step++;
            this.load = false

            document.documentElement.scrollTop = 0;
        },*/
    }
}
</script>
