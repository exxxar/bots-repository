<script setup>
import CallbackForm from "@/ClientTg/Components/Shop/CallbackForm.vue";
import QuizList from "@/ClientTg/Components/Quiz/QuizList.vue";
import QuizQuestionList from "@/ClientTg/Components/Quiz/QuizQuestionList.vue";
import QuizCommands from "@/ClientTg/Components/Quiz/QuizCommands.vue";
</script>
<template>

    <div class="card card-style" v-if="step===0">
        <div class="content">
            <QuizList
                v-on:start="startQuiz"></QuizList>

        </div>
    </div>


<!--    <QuizCommands
        :quiz-id="selectedQuiz.id"
        v-if="step===1">
    </QuizCommands>-->

    <QuizQuestionList
        v-if="step===1"
        :time="selectedQuiz.time_limit"
        :quiz-id="selectedQuiz.id"></QuizQuestionList>

<!--    <div class="card card-style">
        <div class="content d-flex justify-content-center flex-wrap">


        </div>
    </div>-->


    <CallbackForm/>


</template>
<script>


export default {
    name: "App",

    data() {
        return {
            step:0,
            selectedQuiz:null

        };
    },
    computed: {

    },
    mounted() {

    }
    ,
    methods: {
        startQuiz(item){
            this.selectedQuiz = item
            this.step = 1
        }
    }
}
;
</script>
<style>


</style>
