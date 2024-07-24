<script setup>
import QuizItem from "@/ClientTg/Components/V1/Quiz/QuizItem.vue";
import CallbackForm from "@/ClientTg/Components/V1/Shop/CallbackForm.vue";
import QuizList from "@/ClientTg/Components/V1/Quiz/QuizList.vue";
import QuizQuestionList from "@/ClientTg/Components/V1/Quiz/QuizRoundQuestionList.vue";
import QuizCommands from "@/ClientTg/Components/V1/Quiz/QuizCommands.vue";
</script>
<template>

    <div class="card card-style" v-if="step===0">
        <div class="content">
            <QuizList
                v-on:open="openQuiz"></QuizList>

        </div>
    </div>

    <QuizItem
        :need-return="true"
        class="p-1"
        v-if="selectedQuiz&&step===1"
        :quiz="selectedQuiz"
        v-on:return="step=0"
        v-on:select="step++"></QuizItem>


    <QuizQuestionList
        v-if="selectedQuiz&&step===2"
        :time="selectedQuiz.time_limit"
        v-on:complete="completeQuiz"
        :quiz-id="selectedQuiz.id"></QuizQuestionList>


    <!--    <QuizCommands
            :quiz-id="selectedQuiz.id"
            v-if="step===1">
        </QuizCommands>-->


    <!--    <div class="card card-style">
            <div class="content d-flex justify-content-center flex-wrap">


            </div>
        </div>-->


<!--    <CallbackForm/>-->


</template>
<script>


export default {
    name: "App",

    data() {
        return {
            step: 0,
            selectedQuiz: null

        };
    },
    computed: {},
    mounted() {

    }
    ,
    methods: {
        openQuiz(item) {
            this.loadQuiz(item.id)
        },
        startQuiz(item) {
            this.selectedQuiz = item
            this.step = 2
        },
        completeQuiz() {
            this.loadQuiz(selectedQuiz.id)
            this.step = 0
        },
        loadQuiz(quizId) {
            this.$store.dispatch("loadSingleQuiz", {
                quiz_id: quizId
            }).then(resp => {
                this.selectedQuiz = resp.data
                this.step = 1
            }).catch(() => {
                this.step = 0
            })
        }
    }
}
;
</script>
<style>


</style>
