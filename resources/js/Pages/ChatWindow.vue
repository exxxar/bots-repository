<script setup>
import Chat from "@/Components/Chat/Chat.vue";

defineProps({
    bot: {
        type: Object,
    },

    botUser: {
        type: Object
    },

});
</script>
<template>
    <div class="container-fluid pt-3 pb-3" v-if="bot">
       <div class="row">
           <div class="col-12">
                <Chat :domain="bot.bot_domain"/>
           </div>
       </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            load:false,
            confirm:false,
            vipForm:{
                name: null,
                phone: null,
                email: null,
                birthday: null,
                city: null,
                country: null,
                address: null,
                sex: true,

            }
        }
    },
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
        tgUser(){
            const urlParams = new URLSearchParams(this.tg.initData);
            return JSON.parse(urlParams.get('user'));
        }
    },
    methods:{
        submit(){
            this.loading = true;
            this.$store.dispatch("saveVip", {
                dataObject:{
                    bot_id: this.bot.id,
                    tg:this.tgUser,
                    form:this.vipForm
                }
            }).then((resp) => {
                this.loading = false
                window.location.reload()
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
