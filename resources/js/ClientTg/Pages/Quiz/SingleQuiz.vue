<script setup>
import QuizItem from "@/ClientTg/Components/Quiz/QuizItem.vue";
import CallbackForm from "@/ClientTg/Components/Shop/CallbackForm.vue";
import QuizList from "@/ClientTg/Components/Quiz/QuizList.vue";
import QuizQuestionList from "@/ClientTg/Components/Quiz/QuizQuestionList.vue";
import QuizCommands from "@/ClientTg/Components/Quiz/QuizCommands.vue";
</script>
<template>

        <QuizItem
            class="p-1"
            v-if="quiz&&step===0"
            :quiz="quiz" v-on:select="step++"></QuizItem>

    <QuizQuestionList
        v-if="quiz&&step===1"
        :time="quiz.time_limit"
        v-on:complete="completeQuiz"
        :quiz-id="quiz.id"></QuizQuestionList>
    <CallbackForm/>
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
