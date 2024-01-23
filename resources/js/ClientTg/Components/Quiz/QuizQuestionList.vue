<script setup>
import BotMediaObject from '@/ClientTg/Components/BotMediaObject.vue'

</script>
<template>

    <div class="card card-style"
         v-if="questions.length>0&&step<questions.length"
         v-for="(question, index) in questions">
        <div v-if="step==index" class="content">

            <div v-if="!prepare">
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
                    <p class="mb-0" v-if="question.is_multiply">Вы можете выбрать несколько ответов</p>

                    <div v-if="!question.is_open">
                        <label
                            v-if="!question.is_multiply"
                            v-bind:class="{'bg-green2-dark border-green2-dark color-white':answers.indexOf(answer.id)!=-1}"
                            class="btn btn-border btn-m btn-full  rounded-l mb-1 text-uppercase font-900 border-green2-dark d-flex justify-content-center align-items-center"
                            :for="'question-'+index+'-answer-'+answerIndex"
                            v-for="(answer, answerIndex) in question.answers">
                            {{ answer.text }}
                            <input type="radio" name="quiz"
                                   class="d-none"
                                   v-model="answers[index]"
                                   :id="'question-'+index+'-answer-'+answerIndex" :value="answer.id">
                        </label>

                        <button
                            v-if="question.is_multiply"
                            v-bind:class="{'bg-red1-dark color-white':answers[index].indexOf(answer.id)!=-1}"
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

                <div class="divider divider-small my-3 bg-highlight "></div>


                <div class="d-flex justify-content-center font-24">
                    <countdown :time="time * 1000"
                               @finish="finish"
                               format="mm:ss">
                        <template #="{ resolved }">
                            <span class="countdown-item">{{ resolved.mm }}</span> :
                            <span class="countdown-item">{{ resolved.ss }}</span>
                        </template>
                    </countdown>
                </div>

                <small class="text-center w-100 d-block">У вас осталось времени</small>
                <div class="divider divider-small my-3 bg-highlight "></div>

                <a href="javascript:void(0)"

                   @click="checkAnswer(question.id,index)"
                   class="btn btn-m btn-full mb-2 rounded-m text-uppercase font-900 shadow-s bg-blue2-dark">
                    Проверить
                </a>
            </div>


            <div v-if="prepare">
                <h6 class="text-center my-2" v-if="points[index]">
                    {{ points[index].question.message || 'Смотрим результат' }}</h6>
                <div v-if="points[index]">
                    <BotMediaObject
                        v-if="points[index].question.content" class="mb-2"
                        :type="points[index].question.type"
                        :content="points[index].question.content">
                    </BotMediaObject>
                </div>

                <p class="text-center my-3" v-if="points[index].question">
                    <strong>{{ points[index].question.message || 'Отлично! Идем дальше' }}</strong></p>


                <a href="javascript:void(0)"
                   @click="next"
                   class="btn btn-m btn-full mb-2 rounded-m text-uppercase font-900 shadow-s bg-green2-dark">
                    <span v-if="step>=questions.length-1">Перейти к баллам</span>
                    <span v-else> Следующий вопрос</span>
                </a>

            </div>

        </div>


    </div>

    <div class="card card-style" v-if="step>=questions.length">
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
            <a href="javascript:void(0)"
               class="btn btn-m btn-full mt-3 rounded-s text-uppercase font-900 shadow-s bg-red1-light w-100">Завершить</a>
        </div>
    </div>
    <!--
        <BotMediaObject
            v-if="point.question.content" class="mb-2"
            :type="point.question.type"
            :content="point.question.content">
        </BotMediaObject>-->

</template>
<script>
import {mapGetters} from "vuex";
import Countdown from 'vue3-countdown'

export default {
    props: ["quizId", "time"],
    components: {Countdown},
    data() {
        return {
            points: [],
            direction: 'desc',
            order: 'updated_at',
            show: true,
            step: 0,
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
        finish() {
            this.step++;
        },
        next() {
            this.prepare = false
            this.step++;
        },
        removeOpenAnswer(index, openIndex) {
            this.answers[index].splice(openIndex, 1)
        },
        addMoreOpenAnswer(index) {
            console.log(this.answers[index])
            this.answers[index].push(null);
        },
        checkAnswer(questionId, index) {
            this.loading = true
            this.prepare = true
            this.$store.dispatch("checkQuizQuestionAnswer", {
                quizQuestionId: questionId,
                answers: JSON.stringify(this.answers[index]),

            }).then(resp => {
                this.loading = false
                this.points.push(resp)
            }).catch(() => {
                this.loading = false

            })
        },
        toggleAnswer(index, id) {
            let item = this.answers[index].indexOf(id)

            if (item == -1)
                this.answers[index].push(id)
            else
                this.answers[index].splice(item, 1)
        },
        nextQuizQuestion(index) {
            this.loadQuizQuestions(index)
        },
        selectQuizQuestion(question) {
            this.$emit("select", question)
            this.$notify("Вопрос успешно выбран");
        },
        removeQuizQuestion(id) {
            this.loading = true
            this.$store.dispatch("removeQuizQuestion", {
                quizQuestionId: id,

            }).then(resp => {
                this.loading = false
                this.loadQuizQuestions(0)
                this.$notify("Вопрос успешно удален");
            }).catch(() => {
                this.loading = false
                this.$notify("Вопрос удаления сервиса")
            })
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
                    quiz_id: this.quizId,
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
                        tmp = []
                        item.answers.forEach(sub => {
                            tmp.push(null)
                        })
                    } else
                        tmp = null

                    if (item.is_open) {
                        tmp = [null]

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
<style>
.countdown-item {
    padding: 3px 6px;
    border-radius: 3px;
    color: #fff;
    background-color: #c00;
}
</style>
