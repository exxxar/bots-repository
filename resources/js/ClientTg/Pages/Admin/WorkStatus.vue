<script setup>
import UserInfo from 'ClientTg@/Components/UserInfo.vue';
import ReturnToBot from "ClientTg@/Components/Shop/Helpers/ReturnToBot.vue";
</script>
<template>
    <div v-if="botUser">
        <div class="card card-style" v-if="botUser.is_admin">
            <div class="content mb-2">
                <h3>Информация о пользователе</h3>
                <p>
                    Ваша персональная информация
                </p>
                <UserInfo :bot-user="botUser"></UserInfo>
                <ReturnToBot/>
                <div class="divider-icon divider-margins bg-blue2-dark my-4">
                    <i class="fa font-17 color-blue2-dark fa-cog bg-white"></i>
                </div>
                <button
                    type="button"
                    :disabled="loading"
                    @click="workStateChange"
                    class="btn btn-m btn-full mb-1 rounded-s text-uppercase font-900 shadow-s bg-green2-light w-100">
                    <span v-if="botUser.is_work">Завершить рабочую смену</span>
                    <span v-if="!botUser.is_work">Начать рабочую смены</span>
                </button>

                <button
                    type="button"
                    :disabled="loading||!botUser.is_admin"
                    @click="selfRemove"
                    class="btn btn-m btn-full mb-1 rounded-s text-uppercase font-900 shadow-s bg-red1-light w-100">
                    Разжаловать себя из администраторов
                </button>
            </div>
        </div>

        <div class="card card-style bg-red2-dark" v-else>
            <div class="content">
                <h4 class="color-white">Внимание!</h4>
                <p class="color-white">
                    Данная страница доступа только администраторам заведения!
                </p>
            </div>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            botUser: null,
            loading: false,
        }
    },
    computed: {
        ...mapGetters(['getSelf']),
        tg() {
            return window.Telegram.WebApp;
        },
    },
    watch: {
        'getSelf': function () {
            this.botUser = this.getSelf
        }
    },
    mounted() {
        if (this.getSelf) {
            this.botUser = this.getSelf
        }
    },
    methods: {
        loadReceiverUserData() {
            this.loading = true
            this.$store.dispatch("loadReceiverUserData", {
                dataObject: {
                    user_telegram_chat_id: this.botUser.telegram_chat_id
                },
            }).then(resp => {
                this.botUser = resp.data
                this.loading = false
            }).catch(() => {
                this.loading = false
            })
        },
        selfRemove() {
            this.loading = true;
            this.$store.dispatch("selfRemove").then((resp) => {
                this.loading = false
                this.loadReceiverUserData()
                this.$botNotification.success("Отлично!", "Вы больше не являетесь администратором")
                setTimeout(() => {
                    this.tg.close();
                }, 1000)
            }).catch(() => {
                this.loading = false
                this.$botNotification.warning("Упс!", "Что-то пошло не так")
            })
        },

        workStateChange() {
            this.loading = true;
            this.$store.dispatch("workStateChange").then((resp) => {
                this.loading = false
                this.loadReceiverUserData()
                this.$botNotification.success("Отлично!", "Вы успешно изменили свой рабочий статус")
            }).catch(() => {
                this.loading = false
                this.$botNotification.warning("Упс!", "Что-то пошло не так")
            })
        },
    }
}
</script>
