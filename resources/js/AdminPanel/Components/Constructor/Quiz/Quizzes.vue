<script setup>
import QuizForm from "@/AdminPanel/Components/Constructor/Quiz/QuizForm.vue";
import QuizTable from "@/AdminPanel/Components/Constructor/Quiz/QuizTable.vue";
import QuizQuestionForm from "@/AdminPanel/Components/Constructor/Quiz/QuizQuestionForm.vue";
import QuizQuestionTable from "@/AdminPanel/Components/Constructor/Quiz/QuizQuestionTable.vue";
import QuizCommandForm from "@/AdminPanel/Components/Constructor/Quiz/QuizCommandForm.vue";
import QuizCommandTable from "@/AdminPanel/Components/Constructor/Quiz/QuizCommandTable.vue";
import QuizResultTable from "@/AdminPanel/Components/Constructor/Quiz/QuizResultTable.vue";
</script>
<template>


    <div v-if="part===0" class="py-2">
        <div class="row">
            <div class="col-12">
                <button type="button"
                        @click="createQuiz"
                        class="btn btn-primary">
                    Создать новый квиз
                </button>
            </div>
        </div>

        <QuizTable
            v-if="!loadTable"
            v-on:create="createQuiz"
            v-on:select="selectQuiz"
            :bot="bot"></QuizTable>
    </div>

    <div v-if="part===2" class="py-2">
        <div class="row">
            <div class="col-12">
                <button type="button"
                        @click="part=0"
                        class="btn btn-outline-primary">
                    Назад
                </button>
            </div>
        </div>

        <QuizForm
            v-if="!loadForm"
            v-on:callback="callbackForm"
            :bot="bot"/>
    </div>

    <div v-if="part===1" class="py-2">
        <div class="row">
            <div class="col-12">
                <button type="button"
                        @click="part=0"
                        class="btn btn-outline-primary">
                    Назад
                </button>
            </div>
        </div>

        <ul class="nav nav-tabs justify-content-center">
            <li class="nav-item" @click="tab=0">
                <a class="nav-link"
                   v-bind:class="{'active':tab===0}"
                   aria-current="page"
                   href="javascript:void(0)">Информация о квизе</a>
            </li>
            <li class="nav-item" @click="tab=1">
                <a class="nav-link"
                   v-bind:class="{'active':tab===1}"
                   href="javascript:void(0)">Вопросы квиза</a>
            </li>
            <li class="nav-item" @click="tab=2">
                <a class="nav-link"
                   v-bind:class="{'active':tab===2}"
                   href="javascript:void(0)">Команды на квизе</a>
            </li>
            <li class="nav-item" @click="tab=3">
                <a class="nav-link"
                   v-bind:class="{'active':tab===3}"
                   href="javascript:void(0)">Командная статистика</a>
            </li>

        </ul>

        <div class="row py-3" v-if="tab===0">
            <div class="col-12">
                <QuizForm
                    v-if="!loadForm"
                    v-on:callback="callbackForm"
                    :quiz="selectedQuiz"
                    :bot="bot"/>
            </div>
        </div>

        <div class="row py-3" v-if="tab===1">

            <div class="col-12">
                <QuizQuestionForm
                    v-if="!loadQuizQuestion"
                    :question="selectedQuizQuestion"
                    :quiz-id="selectedQuiz.id"
                    v-on:callback="callbackQuestionForm"
                    :bot="bot">

                </QuizQuestionForm>
            </div>
            <div class="col-12 py-3">
                <h4>Доступные вопросы</h4>
                <QuizQuestionTable
                    v-if="!loadQuizQuestionTable"
                    :quiz-id="selectedQuiz.id"
                    v-on:select="selectQuizQuestion"
                    :bot="bot">

                </QuizQuestionTable>
            </div>
        </div>

        <div class="row" v-if="tab===2">
            <div class="col-12">
                <QuizCommandForm
                    v-if="!loadQuizCommand"
                    :command="selectedQuizCommand"
                    :quiz-id="selectedQuiz.id"
                    v-on:callback="callbackCommandForm"
                    :bot="bot">
                </QuizCommandForm>
            </div>

            <div class="col-12 py-3">
                <h4>Команды участники квиза</h4>
                <QuizCommandTable
                    v-if="!loadQuizCommandTable"
                    :quiz-id="selectedQuiz.id"
                    v-on:select="selectQuizCommand"
                    :bot="bot">
                </QuizCommandTable>
            </div>
        </div>

        <div class="row" v-if="tab===3">

            <div class="col-12 py-3">
                <h4>Статистика</h4>
                                <QuizResultTable
                                    :quiz-id="selectedQuiz.id"
                                    v-on:select="selectQuizResult"
                                    :bot="bot">
                                </QuizResultTable>
            </div>
        </div>


        <div class="row" v-if="tab===4">
            <div class="col-12">
                <!--                <AppointmentReviewForm
                                    v-if="!loadReviewForm"
                                    :review="selectedReview"
                                    :bot="bot"
                                    v-on:callback="callbackReview()"
                                    :event-id="selectedEvent.id">
                                </AppointmentReviewForm>-->
            </div>
            <div class="col-12 py-3">
                <h4>Отзывы к событию</h4>
                <!--                <AppointmentReviewsTable
                                    :bot="bot"
                                    v-if="!loadReviews"
                                    v-on:select="selectReview"
                                    :event-id="selectedEvent.id">
                                </AppointmentReviewsTable>-->
            </div>
        </div>
    </div>


</template>

<script>
import {mapGetters} from "vuex";

export default {
    props: ["bot"],
    data() {
        return {
            part: 0,
            tab: 0,
            loadTable: false,
            loadQuizCommand: false,
            loadQuizQuestion: false,
            loadQuizQuestionTable: false,
            loadQuizCommandTable: false,
            loadQuizResult: false,

            loadForm: false,
            selectedQuiz: null,
            selectedQuizQuestion: null,
            selectedQuizCommand: null,
            selectedQuizResult: null,

        }
    },

    mounted() {

    },
    methods: {
        callbackCommandForm() {
            this.loadQuizCommandTable = true
            this.$nextTick(() => {
                this.loadQuizCommandTable = false
            })
        },
        callbackQuestionForm() {
            this.loadQuizQuestionTable = true
            this.$nextTick(() => {
                this.loadQuizQuestionTable = false
            })
        },
        callbackForm() {
            this.loadTable = true
            this.part = 0
            this.$nextTick(() => {
                this.loadTable = false
            })
        },
        createQuiz() {
            this.part = 2
        },
        selectQuizResult(result) {
            this.loadQuizResult = true
            this.$nextTick(() => {
                this.loadQuizResult = false
                this.selectedQuizResult = result

            })
        },
        selectQuizCommand(command) {
            this.loadQuizCommand = true
            this.$nextTick(() => {
                this.loadQuizCommand = false
                this.selectedQuizCommand = command

            })
        },

        selectQuizQuestion(question) {
            this.loadQuizQuestion = true
            this.$nextTick(() => {
                this.loadQuizQuestion = false
                this.selectedQuizQuestion = question

            })
        },
        selectQuiz(quiz) {
            this.loadForm = true
            this.$nextTick(() => {
                this.loadForm = false
                this.selectedQuiz = quiz
                this.part = 1
                this.tab = 0

            })

        }
    }
}
</script>
