
<template>

    <div class="container py-2">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    <div class="img-avatar">
                        <!--                <img
                                            v-if="settings.image"
                                            v-lazy="settings.image"
                                            class="img-avatar"/>-->

                        <img

                            v-lazy="'/images-by-bot-id/'+currentBot.id+'/'+currentBot.image">
                    </div>
                </div>


                <p   class="alert-light alert">

                    Перед началом квеста нажмите кнопку «Начать». Затем перейдите в главное меню Бота и нажмите кнопку «Пригласить Друзей/Подруг». Там вы найдете свою реферальную ссылку, которую легко можно отправить тем, кого хотели бы пригласить в наш Бот.

                    Для выполнения квеста нужно пригласить <strong>{{  (friendsForm.needed_friends || 0) -   (friendsForm.friends_on_start || 0)}}</strong>  друзей/подруг , которые активируют нашего бота по вашей реферальной ссылке.

                </p>

                <p
                    class="alert-light alert"
                    v-if="settings.rules"
                    v-html="settings.rules">
                </p>
            </div>

            <div class="col-12">
                <div class="card " v-if="friendsForm.start_at!=null">

                    <div class="card-body" >
                        <p class="text-center mb-2">У вас сейчас <strong class="fw-bold text-primary">{{ friendsForm.friends_invite || 0 }}</strong> /
                            <strong class="fw-bold text-primary">{{ (friendsForm.needed_friends || 0) -   (friendsForm.friends_on_start || 0)}}</strong> друзей</p>
                        <p class="text-center mb-2">По заданию приглашено <strong class="fw-bold text-primary">{{ friendsForm.friends_invite || 0 }}</strong>
                            друзей</p>

                        <p class="mb-2 text-center">Текущий прогресс <strong class="fw-bold text-primary">{{progress}}%</strong></p>
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
                        <p class="my-2">Когда полоска вашего прогресса достигнет <strong class="fw-bold text-primary">100%</strong> вы сможете получить свои бонусы!</p>

                    </div>


                </div>

                <div  v-if="friendsForm.start_at==null">
                    <button type="button"
                            v-if="friendsForm.complete_at==null&&friendsForm.start_at==null"
                            class="btn w-100 btn-success p-3"
                            @click="start">Приступить к заданию</button>
                </div>
                <div
                    class="card mt-3"
                    v-else>
                    <div class="card-body">
                        <p class="mt-2 mb-0" v-if="friendsForm.start_at!=null">Задание начато <strong class="fw-bold text-primary">{{friendsForm.start_at}}</strong></p>
                        <p class="mt-2 mb-2"  v-if="friendsForm.complete_at!=null">Задание завершено <strong class="fw-bold text-primary">{{friendsForm.complete_at}}</strong></p>
                        <button type="button"
                                @click="complete"
                                :disabled="progress<100||friendsForm.complete_at!=null||friendsForm.start_at==null"
                                class="btn btn-primary p-3 w-100">Получить награды</button>
                    </div>

                </div>
            </div>
        </div>
    </div>




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

                this.$notify({
                    title:'Отлично!',
                    text:"гра началась! Поехали приглашать друзей!",
                    type:"success"
                })

            }).catch(()=>{

                this.$notify({
                    title:'Упс!',
                    text:"Игра почему-то не началась...",
                    type:"error"
                })
            })
        },
        complete() {
            this.$store.dispatch("friendsGameFinish").then((response) => {
                this.prepareUserData()
                this.$notify({
                    title:'Отлично!',
                    text:"гра началась! Спасибо за участие в задании, скоро вы получите свои бонусы!",
                    type:"success"
                })
            }).catch(()=>{

                this.$notify({
                    title:'Упс!',
                    text:"Мы не можем закончить это задание....",
                    type:"error"
                })

            })
        },
        lose() {
            this.$notify({
                title:'Упс!',
                text:"Вы израсходовали все попытки!",
                type:"error"
            })
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
