<script setup>
import QuizItem from "@/ClientTg/Components/Quiz/QuizItem.vue";
import CallbackForm from "@/ClientTg/Components/Shop/CallbackForm.vue";
import QuizList from "@/ClientTg/Components/Quiz/QuizList.vue";
import QuizRoundQuestionList from "@/ClientTg/Components/Quiz/QuizRoundQuestionList.vue";
import QuizFullQuestionList from "@/ClientTg/Components/Quiz/QuizFullQuestionList.vue";
import QuizCommands from "@/ClientTg/Components/Quiz/QuizCommands.vue";
</script>
<template>

    <div  v-if="quiz&&step===0">
        <QuizItem
            class="p-1"
            :quiz="quiz" v-on:select="step++"></QuizItem>

    </div>

    <div v-if="quiz&&step===1">


        <QuizRoundQuestionList
            v-if="quiz.round_mode"
            v-on:complete="completeQuiz"
            :quiz="quiz">

        </QuizRoundQuestionList>

        <QuizFullQuestionList
            v-if="!quiz.round_mode"
            v-on:complete="completeQuiz"
            :quiz="quiz">

        </QuizFullQuestionList>

    </div>
<!--    <CallbackForm/>-->
</template>
<script>


export default {
    name: "App",

    data() {
        return {
            step: 0,
            loading: false,
            quiz: null

        };
    },
    mounted() {
        this.loadQuiz()
    },
    methods: {
        completeQuiz() {
            this.loadQuiz()
            this.step = 0
        },
        loadQuiz() {
            this.loading = true
            this.$store.dispatch("loadSingleQuiz", {
                quiz_id: this.$route.params.quizId
            }).then(resp => {
                this.loading = false
                this.quiz = resp.data
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
;
</script>
<style>


</style>
