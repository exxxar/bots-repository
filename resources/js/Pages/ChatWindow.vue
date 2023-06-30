<script setup>
import Chat from "@/Components/Chat/ChatMini.vue";

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

    <div  v-if="bot">
        <Chat :domain="bot.bot_domain"/>
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
