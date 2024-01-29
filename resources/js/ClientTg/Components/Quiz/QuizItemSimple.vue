<template>

    <div class="card card-style bg-1" style="height:200px;" v-if="quiz">
        <div class="card-center">
            <h2 class="color-white font-700 text-center mb-0">{{ quiz.title || 'нет заголовка' }}</h2>
            <p class="color-white text-center opacity-60 mt-n1 mb-3">{{ quiz.description || 'нет заголовка' }}</p>
            <a href="javascript:void(0)" class="btn btn-m rounded-l font-900 text-uppercase bg-highlight btn-center-xl" @click="selectQuiz(quiz)">Детальнее о квизе</a>
        </div>
        <div class="card-overlay bg-black opacity-70"></div>
    </div>


</template>
<script>
export default {
    props: ["quiz"],
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
    computed: {
        isCompleted() {
            let current_attempts = this.quiz.personal_info.current_attempts
            let max_attempts = this.quiz.personal_info.current_attempts

            return this.quiz.personal_info.completed_at != null && current_attempts >= max_attempts
        }
    },
    methods: {
        selectQuiz(item) {
            this.$store.dispatch("startQuiz", {
                quiz_id: this.quiz.id
            }).then(resp => {
            }).catch(() => {
            })

            this.$emit("select", item)
        },
        openLinkModal(id) {
            this.$cashback.qr("007quiz" + id)
        },
    }
}
</script>
