<script setup>
import ReturnToBot from "@/ClientTg/Components/Shop/Helpers/ReturnToBot.vue";
</script>
<template>



    <div class="card card-style">
        <div class="content d-flex justify-content-center flex-wrap">

            <div class="img-avatar">
<!--                <img
                    v-if="settings.image"
                    v-lazy="settings.image"
                    class="img-avatar"/>-->

                <img

                    v-lazy="'/images-by-bot-id/'+currentBot.id+'/'+currentBot.image">
            </div>

            <p>
                Привет, друг! У меня для тебя отличная новость - теперь ты можешь пригласить своих друзей и получить
                бонусы!
                Просто пригласи <strong>{{  (friendsForm.needed_friends || 0) -   (friendsForm.friends_on_start || 0)}}</strong> друзей,
                которые запустят нашего бота по твоей реферальной ссылке, и получи
                вкусные бонусы, которые можно использовать при покупке наших товаров.
            </p>

            <p
                v-if="settings.rules"
                v-html="settings.rules">
            </p>

        </div>

        <div class="content" v-if="friendsForm.start_at!=null">
            <p class="text-center mb-2">У вас сейчас <strong>{{ friendsForm.friends_invite || 0 }}</strong> /
                <strong>{{ (friendsForm.needed_friends || 0) -   (friendsForm.friends_on_start || 0)}}</strong> друзей</p>
            <p class="text-center mb-2">По заданию приглашено <strong>{{ friendsForm.friends_invite || 0 }}</strong>
                друзей</p>

            <p class="mb-2 text-primary-emphasis">Текущий прогресс <strong>{{progress}}%</strong></p>
            <div class="progress w-100"
                 style="height:40px; border-radius:25px;"
                 role="progressbar"
                 aria-label="Basic example" aria-valuenow="25" aria-valuemin="0"
                 aria-valuemax="100">
                <div class="progress-bar bg-success progress-bar-striped"
                     v-bind:style="{'width': progress+'%'}">
                    {{ progress.toFixed(2) }}%
                </div>

            </div>
            <p class="my-2">Когда полоска вашего прогресса достигнет 100% вы сможете получить свои бонусы!</p>

        </div>

        <div class="content">
            <button type="button"
                    v-if="friendsForm.complete_at!=null&&friendsForm.start_at==null"
                    class="btn btn-m btn-full my-3 rounded-xl text-uppercase font-900 shadow-s bg-green2-dark w-100"
                    @click="start">Приступить к заданию</button>

            <p v-if="friendsForm.start_at!=null">Задание начато <strong>{{friendsForm.start_at}}</strong></p>
            <p v-if="friendsForm.complete_at!=null">Задание завершено <strong>{{friendsForm.complete_at}}</strong></p>
            <button type="button"
                    @click="complete"
                    :disabled="progress<100||friendsForm.complete_at!=null||friendsForm.start_at==null"
                    class="btn btn-m btn-full my-3 rounded-xl text-uppercase font-900 shadow-s bg-green2-dark w-100">Получить награды</button>
        </div>
    </div>


    <!--
        <CallbackForm/>
    -->


</template>
<script>


export default {
    name: "App",

    data() {
        return {

            action: null,
            settings: {
                image: null,
                rules: null,
            },
            friendsForm: {
                friends_invite: 0,
                current_friends: 0,
                needed_friends: 0,
                friends_on_start: 0,
                start_at: null,
                complete_at: null,
            },

        };
    },
    computed: {
        currentBot() {
            return window.currentBot
        },
        progress() {
            let max = this.friendsForm.needed_friends || 0
            let current = this.friendsForm.current_friends || 0
            let start = this.friendsForm.friends_on_start || 0

            return ((current-start) / (max-start)) * 100
        },

    },
    mounted() {
        this.prepareUserData()
    }
    ,
    methods: {
        start() {
            this.$store.dispatch("friendsGameStart").then((response) => {
                this.prepareUserData()
                this.$botNotification.success("Отлично!", "Игра началась! Поехали приглашать друзей")
            }).catch(()=>{
                this.$botNotification.warning("Упс...!", "Игра почему-то не началась...")
            })
        },
        complete() {
            this.$store.dispatch("friendsGameFinish").then((response) => {
                this.prepareUserData()
                this.$botNotification.success("Отлично!", "Спасибо за участие в задании, скоро вы получите свои бонусы!")
            }).catch(()=>{
                this.$botNotification.warning("Упс...!", "Мы не можем закончить это задание...")
            })
        },
        lose() {
            this.$botNotification.warning("Упс!", "Вы израсходовали все попытки!")
        },
        prepareUserData() {
            return this.$store.dispatch("friendsGamePrepare").then((response) => {
                this.action = response.action
                this.settings.rules = response.rules || null
                this.settings.image = response.image || null
                if (this.action.data) {
                    this.friendsForm.current_friends = this.action.data.current_friends || 0
                    this.friendsForm.needed_friends = this.action.data.needed_friends || 0
                    this.friendsForm.friends_invite = this.action.data.friends_invite || 0
                    this.friendsForm.friends_on_start = this.action.data.friends_on_start || 0
                    this.friendsForm.start_at = this.action.data.start_at || null
                    this.friendsForm.complete_at = this.action.data.complete_at || null
                }

            })
        },


    },
}
;
</script>
<style>
.wheel-base-container .wheel-base-indicator {
    left: 45px !important;
}

.wheel .content {
    font-size: 14px;
    font-weight: 900;
    margin: 0 !important;
}

</style>
