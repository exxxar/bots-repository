<script setup>
import BotMediaList from "@/AdminPanel/Components/Constructor/BotMediaList.vue";

</script>
<template>
    <div class="row py-3">
        <div class="col-12">
            <button
                @click="clearQuestionForm"
                class="btn btn-primary">Новый вопрос
            </button>
        </div>
    </div>
    <form v-on:submit.prevent="submitForm">
        <div class="row">

            <div class="col-12 mb-3">

                <label class="form-label " id="quiz-round">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Раунд, в котором будет отображен вопрос
                            </div>
                        </template>
                    </Popper>
                    Раунд
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <input type="number" class="form-control"
                       placeholder="Раунд"
                       aria-label="Раундт"
                       step="1"
                       min="1"
                       v-model="questionForm.round"
                       aria-describedby="quiz-round" required>


            </div>


            <div class="col-12 mb-3">

                <label class="form-label " id="event-on_after_appointment">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Текст вопроса
                            </div>
                        </template>
                    </Popper>
                    Текст вопроса
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="questionForm.text">
                        Длина текста {{ questionForm.text.length }}/255</small>
                </label>
                <textarea type="text" class="form-control"
                          placeholder="Текст вопроса"
                          aria-label="Текст вопроса"
                          v-model="questionForm.text"
                          maxlength="255"
                          aria-describedby="review-text" required>
                    </textarea>

            </div>

            <div class="col-12 mb-3">
                <label class="form-label" id="quiz-images">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Изображение, видео, аудио сопровождение вопроса
                            </div>
                        </template>
                    </Popper>
                    Медиа контент к вопросу <span class="badge rounded-pill text-bg-info m-0"
                                                  v-if="questionForm.content_type">{{
                        questionForm.content_type
                    }}</span>
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <BotMediaList
                    :need-video="true"
                    :need-video-note="true"
                    :need-audio="true"
                    :need-photo="true"
                    :selected="[questionForm.media_content]"
                    v-on:select="selectMedia"></BotMediaList>
            </div>

            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="questionForm.is_multiply"
                           type="checkbox" id="is_multiply">
                    <label class="form-check-label" for="is_multiply">
                        Множественные ответы
                    </label>
                </div>
            </div>

            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="questionForm.is_open"
                           type="checkbox" id="is_open">
                    <label class="form-check-label" for="is_open">
                        Открытый вопрос
                    </label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button
                    @click="addNewAnswer"
                    type="button" class="btn btn-outline-success rounded-5">
                    Добавить ответы
                </button>
            </div>
            <div class="col-12">
                <table class="table" v-if="questionForm.answers.length>0">
                    <thead>

                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Текст вопроса</th>
                        <th scope="col">Баллы</th>
                        <th scope="col">Контент</th>
                        <th scope="col">Тип контента</th>
                        <th scope="col">Является верным</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(answer, index) in questionForm.answers"
                        v-bind:class="{'border-info':answer.deleted_at==null,'border-danger':answer.deleted_at!=null}">
                        <th scope="row">{{ answer.id || 'Новый' }}

                        </th>
                        <td>
                            <div class="form-floating mb-3">
                                <input type="text"
                                       v-model="questionForm.answers[index].text"
                                       class="form-control" :id="'answer-text-'+index"
                                       placeholder="Текст ответа">
                                <label :for="'answer-text-'+index">Текст ответа</label>
                            </div>
                        </td>
                        <td>
                            <div class="form-floating mb-3">
                                <input type="text"
                                       v-model="questionForm.answers[index].points"
                                       class="form-control" :id="'answer-points-'+index"
                                       placeholder="Текст ответа">
                                <label :for="'answer-points-'+index">Баллы</label>
                            </div>
                        </td>
                        <td>
                            <p class="cursor-pointer" @click="chooseMediaContent(index)">
                                {{ answer.media_content || 'Не указано' }}</p>
                        </td>
                        <td>{{ answer.content_type || 'Не указано' }}</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input"
                                       v-model="questionForm.answers[index].is_right_answer"
                                       type="checkbox" :id="'answer-is_right_answer-'+index">
                                <label class="form-check-label" :for="'answer-is_right_answer-'+index">
                                    Правильный ответ
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li v-if="answer.id==null">
                                        <a class="dropdown-item"
                                           @click="removeAnswerFromArray(index)"
                                           href="javascript:void(0)">Удалить</a></li>
                                    <li v-if="answer.deleted_at==null&&answer.id!=null">
                                        <a class="dropdown-item"
                                           @click="removeAnswer(answer.id)"
                                           href="javascript:void(0)">Удалить</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>


                    </tbody>
                </table>
                <p v-else>На текущий момент нет ни одного созданного ответа</p>
            </div>


        </div>

        <div class="row">
            <div class="col-12">
                <button
                    type="submit" class="btn btn-outline-success w-100 p-3">
                    <span v-if="questionForm.id==null">Сохранить вопрос</span>
                    <span v-else>Обновить вопрос</span>
                </button>
            </div>
        </div>
    </form>


    <!-- Modal -->
    <div class="modal fade" id="chooseMediaContent" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" v-if="selectedQuestionIndex!=null">
                    <BotMediaList

                        :need-video="true"
                        :need-video-note="true"
                        :need-audio="true"
                        :need-photo="true"
                        :selected="[questionForm.answers[selectedQuestionIndex||0].media_content||'']"
                        v-on:select="selectMediaForAnswer"></BotMediaList>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>


export default {
    props: ["quizId", "bot", "question"],

    data() {
        return {
            step: 0,
            load: false,
            selectedQuestionIndex: null,
            questionForm: {
                id: null,
                text: null,
                round: null,
                media_content: null,
                content_type: null,
                is_multiply: false,
                is_open: false,
                answers: [],

            }
        }
    },
    watch: {
        questionForm: {
            handler(val) {
                this.need_reset = true
            },
            deep: true
        }
    },
    mounted() {

        if (this.question)
            this.$nextTick(() => {
                this.questionForm = {
                    id: this.question.id || null,
                    text: this.question.text || null,
                    round: this.question.round || null,
                    media_content: this.question.media_content || null,
                    content_type: this.question.content_type || null,
                    is_multiply: this.question.is_multiply || false,
                    is_open: this.question.is_open || false,
                    answers: this.question.answers || [],
                }
            })

    },
    methods: {
        chooseMediaContent(index) {


            this.selectedQuestionIndex = null
            this.$nextTick(() => {
                this.selectedQuestionIndex = index
            })

            const mc = new bootstrap.Modal(document.getElementById('chooseMediaContent'), {})
            mc.show()

        },
        selectMediaForAnswer(item) {
            this.questionForm.answers[this.selectedQuestionIndex].media_content = item.file_id
            this.questionForm.answers[this.selectedQuestionIndex].content_type = item.type
        },
        selectMedia(item) {
            this.questionForm.media_content = item.file_id
            this.questionForm.content_type = item.type
        },
        clearQuestionForm() {
            this.questionForm = {
                id: null,
                text: null,
                round: null,
                media_content: null,
                content_type: null,
                is_multiply: false,
                is_open: false,
                answers: []
            }
        },
        addNewAnswer() {
            this.questionForm.answers.push({
                text: null,
                media_content: null,
                content_type: null,
                is_right_answer: false,
                points: 0,
            })
        },
        removeAnswerFromArray(index) {
            this.questionForm.answers.splice(index, 1)
        },
        removeAnswer(id) {
            this.$store.dispatch("removeQuizAnswer", {
                quizAnswerId: id
            }).then((response) => {
                this.$notify("Вопрос успешно удален");

                let index = this.questionForm.answers.findIndex(item => item.id == id)
                this.questionForm.answers.splice(index, 1)
            }).catch(err => {
                this.$notify("Ошибка удаления вопроса");
            })
        },
        submitForm() {

            let data = new FormData();
            Object.keys(this.questionForm)
                .forEach(key => {
                    const item = this.questionForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append('bot_id', this.bot.id);
            data.append('quiz_id', this.quizId);

            this.$store.dispatch("storeQuizQuestion", {
                quizQuestionForm: data
            }).then((response) => {
                this.$emit("callback", response.data)
                this.$notify("Вопрос успешно создан");
                this.clearQuestionForm()

            }).catch(err => {
                this.$notify("Ошибка создания вопроса");
            })

        },

    }
}
</script>


