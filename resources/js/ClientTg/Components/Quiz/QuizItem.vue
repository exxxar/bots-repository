<template>
    <div class="px-1">
        <div class="card w-100 shadow-xl rounded-sm" v-if="quiz">
            <img v-lazy="'/file-by-file-id/'+quiz.image"
                 v-if="quiz.image"
                 class="card-img-top rounded-m p-2" alt="...">
            <div class="card-body">
                <h5 class="card-title">{{ quiz.title || 'нет заголовка' }}</h5>
                <p class="card-text">{{ quiz.description || 'нет заголовка' }}</p>

            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item text-center text-danger" v-if="!quiz.is_active">Квиз недоступен</li>
                <li class="list-group-item"><i class="fa-solid fa-shuffle"></i> {{ types[quiz.display_type] }}</li>
                <li class="list-group-item" v-if="quiz.round_mode"><i class="fa-solid fa-stopwatch-20"></i> на вопрос {{ quiz.time_limit }} сек
                </li>
                <li class="list-group-item" v-if="quiz.round_mode">Режим викторины (квиза)
                </li>
                <li class="list-group-item" v-if="quiz.polling_mode">Режим опроса
                </li>
                <li class="list-group-item" v-if="(quiz.questions || [] ).length>0"><i
                    class="fa-regular fa-circle-question"></i> Число вопросов {{ (quiz.questions || []).length }}
                </li>
                <li class="list-group-item" v-if="!quiz.polling_mode&&(quiz.commands || [] ).length>0"><i
                    class="fa-solid fa-person-circle-question"></i> Число команд {{ (quiz.commands || []).length }}
                </li>
                <li class="list-group-item" v-if="!quiz.polling_mode"><i class="fa-solid fa-link"></i>
                    <a href="javascript:void(0)" @click="openLinkModal">Поделиться ссылкой</a>
                </li>
            </ul>
            <div class="card-body" v-if="!isCompleted">
                <button type="button"
                        v-if="(quiz.questions || [] ).length>0"
                        @click="selectQuiz(quiz)"
                        :disabled="!quiz.is_active"
                        class="btn btn-m w-100 btn-full mb-3 rounded-xl text-uppercase font-900 shadow-s bg-blue1-dark">Начать
                </button>
                <div class="alert alert-warning" v-else>Вы не можете сейчас начать квиз! В нём нет вопросов!</div>
                <div class="divider divider-small my-3 bg-highlight " v-if="needReturn"></div>
                <a href="javascript:void(0)"
                   @click="returnBack"
                   v-if="needReturn"
                   class="btn btn-m btn-full mb-3 rounded-l text-uppercase font-900 shadow-s bg-red1-dark">Вернуться
                    назад</a>

            </div>
            <div class="card-body" v-else>
                <div class="card card-style bg-28" data-card-height="130" style="height: 130px;">
                    <div class="card-center">
                        <h3 class="color-white font-700 text-center mb-0"> Баллов
                            {{ quiz.personal_info.result_points || 0 }}</h3>
                        <p class="color-white text-center opacity-60 mt-n1 mb-0">
                            Попытки {{ quiz.personal_info.current_attempts || 0 }} /
                            {{ quiz.personal_info.max_attempts || 0 }}</p>
                        <p class="color-white text-center opacity-60 mt-n1 mb-0"
                           v-if="quiz.personal_info.completed_at!=null">
                            {{ $filters.currentFull(quiz.personal_info.completed_at) }}</p>
                    </div>
                    <div class="card-overlay bg-highlight opacity-90"></div>
                </div>
                <div class="divider divider-small my-3 bg-highlight " v-if="needReturn"></div>
                <a href="javascript:void(0)"
                   @click="returnBack"
                   v-if="needReturn"
                   class="btn btn-m btn-full mb-3 rounded-l text-uppercase font-900 shadow-s bg-red1-dark">Вернуться
                    назад</a>
            </div>


        </div>
    </div>

</template>
<script>
export default {
    props: ["quiz", "needReturn"],
    data() {
        return {
            types: [
                "По порядку",
                "Перемешать всё",
                "Перемешать ответы",
                "Перемешать вопросы"
            ],
        }
    },
    mounted() {
        const url = import.meta.env.VITE_PUSHER_APP_CLUSTER
        console.log("media=>",url)
    },
    computed: {
        isCompleted() {
           // return false;
            let current_attempts = this.quiz.personal_info.current_attempts
            let max_attempts = this.quiz.personal_info.current_attempts

            return this.quiz.personal_info.completed_at != null && current_attempts >= max_attempts
        }
    },
    methods: {
        returnBack() {
            this.$emit("return")
        },
        selectQuiz(item) {
            this.$store.dispatch("startQuiz", {
                quiz_id: this.quiz.id
            }).then(resp => {
            }).catch(() => {
            })

            this.$emit("select", item)
        },
        openLinkModal() {
            let slugId = window.currentScript || null
            this.$cashback.qr("005000000" + slugId)
        },
    }
}
</script>
