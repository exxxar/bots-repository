<script setup>
import QuizCommandForm from "@/ClientTg/Components/Quiz/QuizCommandForm.vue";
import QuizCommandList from "@/ClientTg/Components/Quiz/QuizCommandList.vue";
</script>
<template>
    <div class="card card-style">
        <div class="card-body">
            <h6>Новая команда</h6>
            <p class="mb-1">
                У вас есть свои участники, вы чувствуете в себе силы чтобы возглавить команду, вы прирожденный лидер, или же вы просто хотите начать игру,
                тогда вы должны создать команду (или присоединиться к существующей) дав ей имя и короткое описание, а затем выдав ссылку участникам команды.
            </p>
            <QuizCommandForm
                v-if="need_command_form"
                v-on:callback="callbackQuizCommandForm"
                :quiz-id="quizId"></QuizCommandForm>

            <a href="javascript:void(0)"
               @click="need_command_form=!need_command_form"
               class="btn btn-m btn-full mb-3 rounded-s text-uppercase font-900 shadow-s bg-red1-light">
               <span v-if="!need_command_form">Создать новую команду</span>
               <span v-if="need_command_form">Свернуть создание команды</span>
            </a>
        </div>
    </div>



    <QuizCommandList
        v-if="!load_command_list"
        :quiz-id="quizId">
        <template v-slot:header>
            <h3 class="color-black font-700 font-18 text-center mt-3 mb-2">Выбери свою команду</h3>
            <p class="color-black  text-center opacity-40 mt-n1 mb-2 px-3">Возможно, ваша команда уже создана и ждет вас!</p>
        </template>
    </QuizCommandList>

</template>
<script>
export default {
    props:["quizId"],
    data(){
        return {
            need_command_form:false,
            load_command_list:false,
        }
    },
    methods:{
        callbackQuizCommandForm(){
            this.load_command_list = true
            this.$nextTick(()=>{
                this.load_command_list = false
            })
        }
    }
}
</script>
