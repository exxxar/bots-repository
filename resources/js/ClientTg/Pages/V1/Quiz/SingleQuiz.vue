<script setup>
import QuizItem from "@/ClientTg/Components/V1/Quiz/QuizItem.vue";
import CallbackForm from "@/ClientTg/Components/V1/Shop/CallbackForm.vue";
import QuizList from "@/ClientTg/Components/V1/Quiz/QuizList.vue";
import QuizRoundQuestionList from "@/ClientTg/Components/V1/Quiz/QuizRoundQuestionList.vue";
import QuizFullQuestionList from "@/ClientTg/Components/V1/Quiz/QuizFullQuestionList.vue";
import QuizCommands from "@/ClientTg/Components/V1/Quiz/QuizCommands.vue";
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
