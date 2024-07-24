<script setup>
import BotMediaObject from '@/ClientTg/Components/V1/BotMediaObject.vue'

</script>
<template>

    <div class="card card-style" v-if="questions.length===0">
        <div class="content d-flex justify-content-center align-items-center flex-wrap" style="min-height:300px;">
            <div>
                <p class="text-center w-100 font-weight-bold">Мы загружаем квиз!</p>
                <div class="loader"></div>
            </div>
        </div>
    </div>
    <div class="card card-style"
         v-if="questions.length>0&&step<questions.length">
        <div class="content">
            <div
                class="mb-5"
                v-for="(question, index) in questions">
                <a href="javascript:void(0)" class="chip chip-small bg-gray1-dark">
                    <i class="fa fa-check bg-green1-dark"></i>
                    <strong class="color-black font-400">Раунд {{ question.round || 1 }}</strong>
                </a>
                <p class="mb-0">{{ question.text || 'не указан' }}</p>

                <BotMediaObject
                    v-if="question.media_content" class="mb-2"
                    :type="question.content_type"
                    :content="question.media_content">
                </BotMediaObject>


                <div v-if="question.answers">
                    <p class="mb-0 font-weight-bold text-center" v-if="question.is_multiply">Вы можете выбрать несколько
                        ответов</p>

                    <div v-if="!question.is_open">
                        <label
                            v-if="!question.is_multiply"
                            v-bind:class="{'bg-green2-dark border-green2-dark color-white': answers[index].value==answer.id}"
                            class="btn btn-border btn-m btn-full  rounded-l mb-1 text-uppercase font-900 border-green2-dark d-flex justify-content-center align-items-center"
                            :for="'question-'+index+'-answer-'+answerIndex"
                            v-for="(answer, answerIndex) in question.answers">
                            {{ answer.text }}
                            <input type="radio" name="quiz"
                                   class="d-none"
                                   v-model="answers[index].value"
                                   :id="'question-'+index+'-answer-'+answerIndex" :value="answer.id">
                        </label>

                        <button
                            v-if="question.is_multiply"
                            v-bind:class="{'bg-red1-dark color-white':answers[index].value.indexOf(answer.id)!=-1}"
                            class="btn btn-border btn-m btn-full mb-1 rounded-l text-uppercase font-900 border-red2-dark  w-100 d-flex justify-content-center align-items-center"
                            v-for="(answer, answerIndex) in question.answers"
                            @click="toggleAnswer(index, answer.id)"
                            type="button">
                            {{ answer.text }}
                        </button>
                    </div>
                    <div v-else>
                        <p class="mb-0"><small>Открытый вопрос</small></p>


                        <div class="form-field form-name" v-for="(open, openIndex) in answers[index]">
                            <label class="contactNameField color-theme d-flex justify-content-between"
                                   for="command-title">
                                Ответ #{{ openIndex + 1 }}
                                <a href="javascript:void(0)"
                                   v-if="answers[index].length>1"
                                   @click="removeOpenAnswer(index, openIndex)">удалить</a>
                            </label>
                            <input type="text" name="contactNameField" maxlength="255"
                                   v-model="answers[index][openIndex]"
                                   placeholder="Например 'Новые люди'"
                                   class="contactField round-small requiredField"
                                   id="command-title" required>
                        </div>


                        <a href="javascript:void(0)" class="text-center w-100 d-block"
                           @click="addMoreOpenAnswer(index)">Добавить еще ответ</a>

                    </div>


                </div>


                <div v-if="points[index]">
                    <h6 class="text-center my-2">
                        А вот и результат
                    </h6>
                    <div v-if="points[index]">
                        <BotMediaObject
                            v-if="points[index].question.content" class="mb-2"
                            :type="points[index].question.type"
                            :content="points[index].question.content">
                        </BotMediaObject>
                    </div>

                    <p class="text-center my-3" v-if="(points[index] || {}).question">
                        <strong v-html="points[index].question.message|| 'Отлично! Идем дальше'"></strong>
                    </p>


                </div>
            </div>

            <a href="javascript:void(0)"
               @click="finish"
                v-if="!is_finish"
               class="btn btn-m btn-full mb-2 rounded-m text-uppercase font-900 shadow-s bg-green2-dark">
                Завершить опрос и посмотреть результат
            </a>
        </div>


    </div>

    <div class="card card-style" v-if="is_finish">
        <div class="content">
            <p>Поздравляю! Вы прошли данный квиз! Ваш результат:</p>
            <ol class="list-group list-group-numbered" v-if="points.length>0">
                <li class="list-group-item d-flex justify-content-between align-items-start"
                    v-for="(point, index) in points">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">{{ point.question.text || 'Текст не указан' }}</div>
                        {{ point.question.message || '-' }}

                    </div>
                    <span class="badge rounded-pill text-white p-2"
                          v-bind:class="{'bg-success':point.is_right,'bg-danger':!point.is_right}">{{
                            point.points || 0
                        }} баллов</span>
                </li>
            </ol>
            <h6 class="my-2">Вы набрали {{ summaryPoints }} баллов</h6>

            <p v-if="successPercentCount>=quiz.success_percent" v-html="quiz.success_message"></p>
            <p v-else v-html="quiz.failure_message"></p>

            <a href="javascript:void(0)"
               @click="completeAndExit"
               class="btn btn-m btn-full mt-3 rounded-s text-uppercase font-900 shadow-s bg-red1-light w-100">Завершить
                и выйти</a>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";
import Countdown from 'vue3-countdown'

export default {
    props: ["quiz"],
    components: {Countdown},
    data() {
        return {
            points: [],
            direction: 'asc',
            order: 'round',
            show: true,
            step: 0,
            is_finish: false,
            is_group: false,
            loading: true,
            questions: [],
            answers: [],
            search: null,
            paginate_object: null,
            prepare: false,
        }
    },

    computed: {
        ...mapGetters(['getQuizQuestions', 'getQuizQuestionsPaginateObject']),
        successPercentCount(){
            if (this.points.length===0)
                return 0

            let rightSummary = 0;
            this.points.forEach(item=>{
                if (item.is_right)
                    rightSummary++;
            })

            return rightSummary / this.points.length

        },
        summaryPoints() {
            if (this.points.length === 0)
                return 0;
            let sum = 0;
            this.points.forEach(item => {
                sum += (item.points || 0)
            })

            return sum
        }

    },
    mounted() {
        this.loadQuizQuestions();
    },
    methods: {
        completeAndExit() {
            this.$store.dispatch("completeQuiz", {
                quiz_id: this.quiz.id,
            }).then(resp => {

            }).catch(() => {

            })
            this.$emit("complete")
        },

        finish() {
            this.is_finish = true
            this.$store.dispatch("checkAndCompleteFullQuiz", {
                quiz_id: this.quiz.id,
                answers: JSON.stringify(this.answers),
            }).then(resp => {
                this.points = resp
            }).catch((resp ) => {

            })
        },

        removeOpenAnswer(index, openIndex) {
            this.answers[index].splice(openIndex, 1)
        },
        addMoreOpenAnswer(index) {
            this.answers[index].push(null);
        },

        toggleAnswer(index, id) {
            let item = this.answers[index].value.indexOf(id)

            if (item == -1)
                this.answers[index].value.push(id)
            else
                this.answers[index].value.splice(item, 1)
        },

        loadAndOrder(order) {
            this.order = order
            this.direction = this.direction === 'desc' ? 'asc' : 'desc'
            this.loadQuizQuestions(0)
        },
        loadQuizQuestions(page = 0) {
            this.loading = true
            this.$store.dispatch("loadQuizQuestions", {
                dataObject: {
                    quiz_id: this.quiz.id,
                    search: this.search,
                    order: this.order,
                    direction: this.direction
                },
                page: page,
                size: -1
            }).then(resp => {
                this.loading = false


                this.questions = this.getQuizQuestions

                this.answers = []
                this.questions.forEach(item => {
                    let tmp = null;
                    if (item.is_multiply) {
                        tmp = {id:item.id, value:[]}
                    } else
                        tmp = {id: item.id, value: null}

                    if (item.is_open) {
                        tmp = [{id: item.id, value: null}]

                    }

                    this.answers.push(tmp)
                })
                this.paginate_object = this.getQuizQuestionsPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
<style lang="scss">
.countdown-item {
    padding: 3px 6px;
    border-radius: 3px;
    color: #fff;
    background-color: #c00;
}


.loader {
    width: 200px;
    height: 140px;
    background: #979794;
    box-sizing: border-box;
    position: relative;
    border-radius: 8px;
    perspective: 1000px;
}

.loader:before {
    content: '';
    position: absolute;
    left: 10px;
    right: 10px;
    top: 10px;
    bottom: 10px;
    border-radius: 8px;
    background: #f5f5f5 no-repeat;
    background-size: 60px 10px;
    background-image: linear-gradient(#ddd 100px, transparent 0),
    linear-gradient(#ddd 100px, transparent 0),
    linear-gradient(#ddd 100px, transparent 0),
    linear-gradient(#ddd 100px, transparent 0),
    linear-gradient(#ddd 100px, transparent 0),
    linear-gradient(#ddd 100px, transparent 0);

    background-position: 15px 30px, 15px 60px, 15px 90px,
    105px 30px, 105px 60px, 105px 90px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
}

.loader:after {
    content: '';
    position: absolute;
    width: calc(50% - 10px);
    right: 10px;
    top: 10px;
    bottom: 10px;
    border-radius: 8px;
    background: #fff no-repeat;
    background-size: 60px 10px;
    background-image: linear-gradient(#ddd 100px, transparent 0),
    linear-gradient(#ddd 100px, transparent 0),
    linear-gradient(#ddd 100px, transparent 0);
    background-position: 50% 30px, 50% 60px, 50% 90px;
    transform: rotateY(0deg);
    transform-origin: left center;
    animation: paging 1s linear infinite;
}


@keyframes paging {
    to {
        transform: rotateY(-180deg);
    }
}

</style>
