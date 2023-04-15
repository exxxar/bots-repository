<script setup>
import UserInfo from '@/Components/UserInfo.vue';

defineProps({
    user: {
        type: Object,
    },
    botUser: {
        type: Object
    }
});
</script>
<template>
    <div class="container pt-3 pb-3" v-if="botUser.is_admin">
        <div class="row mb-2">
            <div class="col-12">
                <UserInfo :bot-user="botUser"></UserInfo>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button @click="workStateChange"
                        :disabled="loading"
                        class="btn btn-outline-primary w-100 mb-2 ">
                    <span v-if="botUser.is_work">Завершить рабочую смену</span>
                    <span v-if="!botUser.is_work">Начать рабочую смены</span>
                </button>

            </div>
            <div class="col-12">
                <button @click="selfRemove"
                        :disabled="loading||!botUser.is_admin"
                        class="btn btn-outline-primary w-100 mb-2 ">
                    Разжаловать себя из администраторов
                </button>

            </div>
        </div>
    </div>
    <div class="container" v-else>
        <div class="row">
            <div class="alert alert-warning" role="alert">
                Вы не являетесь администратором
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            loading: false,
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
    methods: {
        selfRemove() {
            this.loading = true;
            this.$store.dispatch("selfRemove", {
                dataObject:{
                    bot_id: this.botUser.bot_id,
                    tg:this.tgUser
                }
            }).then((resp) => {
                this.loading = false
                window.location.reload()
            }).catch(() => {
                this.loading = false
            })
        },

        workStateChange() {
            this.loading = true;
            this.$store.dispatch("workStateChange", {
                dataObject:{
                    bot_id: this.botUser.bot_id,
                    tg:this.tgUser
                }

            }).then((resp) => {
                this.loading = false
                window.location.reload()
            }).catch(() => {
                this.loading = false
            })
        },
    }
}
</script>
