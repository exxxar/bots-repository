<script setup>
import BotMediaList from "@/AdminPanel/Components/Constructor/BotMediaList.vue";
import BotMediaVariant from "@/AdminPanel/Components/Constructor/BotMediaVariant.vue";
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
                <p v-if="rounds.length>0">
                    Ранее созданные раунды:
                    <span
                        @click="questionForm.round = round"
                        class="badge bg-primary mr-1 cursor-pointer" v-for="round in rounds">{{ round }}</span>
                </p>
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
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="need_media"
                           type="checkbox" id="need_media">
                    <label class="form-check-label" for="need_media">
                        Нужен медиа-контент
                    </label>
                </div>
            </div>


            <div class="col-12 mb-3" v-if="need_media">
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
                <h6>Вставьте ссылку на контент...</h6>
                <BotMediaVariant
                    v-model:type="questionForm.content_type"
                    v-model:value="questionForm.media_content"
                >
                </BotMediaVariant>
                <h6>...или выберите из доступных</h6>

                <button
                    type="button"
                    @click="chooseBaseMediaContent"
                    class="btn btn-outline-info rounded-lg">Выбрать медиа-файл
                </button>


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

            <div class="col-12 mb-3">

                <div class="d-flex justify-content-between">
                    <label class="form-label " id="quiz-description">
                        <Popper>
                            <i class="fa-regular fa-circle-question mr-1"></i>
                            <template #content>
                                <div>
                                    Текст при выборе правильного ответа
                                </div>
                            </template>
                        </Popper>
                        Текст при выборе правильного ответа
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                        <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="questionForm.success_message">
                            Длина текста {{ questionForm.success_message.length }}/255</small>
                    </label>
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                            Кнопки
                        </button>
                        <ul class="dropdown-menu">
                            <li
                                @click="attachTo('success_message',item.value)"
                                v-for="item in htmlInjections"
                            ><a class="dropdown-item"
                                href="javascript:void(0)">{{ item.title || 'Не указано' }}</a></li>

                        </ul>
                    </div>
                </div>
                <textarea class="form-control"
                          placeholder="Текст при правильном ответе"
                          aria-label="Текст при правильном ответе"
                          maxlength="255"
                          v-model="questionForm.success_message"
                          aria-describedby="quiz-question-success_message" required>
                    </textarea>

            </div>

            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="need_media_for_success"
                           type="checkbox" id="need_media_for_success">
                    <label class="form-check-label" for="need_media_for_success">
                        Нужен медиа-контент при правильном ответе
                    </label>
                </div>
            </div>

            <div class="col-12 mb-3" v-if="need_media_for_success">
                <h6>Вставьте ссылку на контент...</h6>

                <BotMediaVariant
                    v-model:type="questionForm.success_media_content_type"
                    v-model:value="questionForm.success_media_content"
                >
                </BotMediaVariant>

                <h6>...или выберите из доступных</h6>

                <button
                    type="button"
                    @click="chooseSuccessMediaContent"
                    class="btn btn-outline-info rounded-lg">Выбрать медиа-файл
                </button>

            </div>

            <div class="col-12 mb-3">

                <div class="d-flex justify-content-between">
                    <label class="form-label " id="quiz-description">
                        <Popper>
                            <i class="fa-regular fa-circle-question mr-1"></i>
                            <template #content>
                                <div>
                                    Текст в выбора неправильного ответа
                                </div>
                            </template>
                        </Popper>
                        Текст при выборе неправильного ответа
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                        <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="questionForm.failure_message">
                            Длина текста {{ questionForm.failure_message.length }}/255</small>
                    </label>
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                            Кнопки
                        </button>
                        <ul class="dropdown-menu">
                            <li
                                @click="attachTo('failure_message',item.value)"
                                v-for="item in htmlInjections"
                            ><a class="dropdown-item"
                                href="javascript:void(0)">{{ item.title || 'Не указано' }}</a></li>

                        </ul>
                    </div>
                </div>


                <textarea class="form-control"
                          placeholder="Текст при неправильном ответе"
                          aria-label="Текст при неправильном ответе"
                          maxlength="255"
                          v-model="questionForm.failure_message"
                          aria-describedby="quiz-question-success_message" required>
                    </textarea>

            </div>

            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="need_media_for_failed"
                           type="checkbox" id="need_media_for_failed">
                    <label class="form-check-label" for="need_media_for_failed">
                        Нужен медиа-контент при неправильном ответе
                    </label>
                </div>
            </div>
            <div class="col-12 mb-3" v-if="need_media_for_failed">
                <h6>Вставьте ссылку на контент...</h6>

                <BotMediaVariant
                    v-model:type="questionForm.failure_media_content_type"
                    v-model:value="questionForm.failure_media_content"
                >
                </BotMediaVariant>

                <h6>...или выберите из доступных</h6>

                <button
                    type="button"
                    @click="chooseFailureMediaContent"
                    class="btn btn-outline-info rounded-lg">Выбрать медиа-файл
                </button>


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

    <!-- Modal -->
    <div class="modal fade" id="chooseSuccessMediaContent" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <BotMediaList

                        :need-video="true"
                        :need-video-note="true"
                        :need-audio="true"
                        :need-photo="true"
                        :selected="[questionForm.success_media_content]"
                        v-on:select="selectMediaForSuccess"></BotMediaList>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="chooseFailureMediaContent" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <BotMediaList

                        :need-video="true"
                        :need-video-note="true"
                        :need-audio="true"
                        :need-photo="true"
                        :selected="[questionForm.failure_media_content]"
                        v-on:select="selectMediaForFailure"></BotMediaList>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="chooseBaseMediaContent" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <BotMediaList
                        :need-video="true"
                        :need-video-note="true"
                        :need-audio="true"
                        :need-photo="true"
                        :selected="[questionForm.media_content]"
                        v-on:select="selectMedia"></BotMediaList>
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

            need_media: false,
            need_media_for_success: false,
            need_media_for_failed: false,
            rounds: [],
            htmlInjections: [
                {
                    title: "Кнопка синяя",
                    value: "<a href='http://test.com/test' class='w-100 btn btn-primary'>Текст кнопки</a>"
                },
                {
                    title: "Кнопка без цвета",
                    value: "<a href='http://test.com/test' class='w-100 btn btn-link'>Текст кнопки</a>"
                },
                {
                    title: "Кнопка с синей обводкой",
                    value: "<a href='http://test.com/test' class='w-100 btn btn-outline-primary'>Текст кнопки</a>"
                }
            ],
            questionForm: {
                id: null,
                text: null,
                round: null,
                media_content: null,
                content_type: null,
                is_multiply: false,
                is_open: false,
                answers: [],
                success_message: null,
                failure_message: null,
                success_media_content: null,
                failure_media_content: null,
                success_media_content_type: null,
                failure_media_content_type: null,

            }
        }
    },
    watch: {
        questionForm: {
            handler(val) {
                this.need_reset = true
            },
            deep: true
        },
        'need_media': function (newVal, oldVal) {
            if (!this.need_page_images) {
                this.questionForm.media_content = null
                this.questionForm.content_type = null
            }

        },
        'need_media_for_success': function (newVal, oldVal) {
            if (!this.need_page_images) {
                this.questionForm.success_media_content = null
                this.questionForm.success_media_content_type = null
            }

        },
        'need_media_for_failed': function (newVal, oldVal) {
            if (!this.need_page_images) {
                this.questionForm.failure_media_content = null
                this.questionForm.failure_media_content_type = null
            }

        },
    },

    mounted() {


        this.loadQuizRounds()
        if (this.question) {
            if (this.question.media_content != null)
                this.need_media = true

            if (this.question.success_media_content != null)
                this.need_media_for_success = true

            if (this.question.failure_media_content != null)
                this.need_media_for_failed = true

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
                    success_message: this.question.success_message || null,
                    failure_message: this.question.failure_message || null,
                    success_media_content: this.question.success_media_content || null,
                    failure_media_content: this.question.failure_media_content || null,
                    success_media_content_type: this.question.success_media_content_type || null,
                    failure_media_content_type: this.question.failure_media_content_type || null,
                }


            })


        }


    },
    methods: {
        attachTo(param, value) {
            if (this.questionForm[param] == null)
                this.questionForm[param] = value
            else
                this.questionForm[param] += value;
        },
        loadQuizRounds() {
            this.$store.dispatch("loadQuizRounds", {
                dataObject: {
                    bot_id: this.bot.id || null,
                    quiz_id: this.quizId || null,
                }
            }).then(resp => {
                this.rounds = resp
            })
        },
        chooseBaseMediaContent(index) {
            const mc = new bootstrap.Modal(document.getElementById('chooseBaseMediaContent'), {})
            mc.show()
        },
        chooseSuccessMediaContent(index) {
            const mc = new bootstrap.Modal(document.getElementById('chooseSuccessMediaContent'), {})
            mc.show()
        },
        chooseFailureMediaContent(index) {
            const mc = new bootstrap.Modal(document.getElementById('chooseFailureMediaContent'), {})
            mc.show()
        },
        chooseMediaContent(index) {


            this.selectedQuestionIndex = null
            this.$nextTick(() => {
                this.selectedQuestionIndex = index
            })

            const mc = new bootstrap.Modal(document.getElementById('chooseMediaContent'), {})
            mc.show()

        },

        selectMediaForFailure(item) {
            this.questionForm.failure_media_content = item.file_id
            this.questionForm.failure_media_content_type = item.type
        },
        selectMediaForSuccess(item) {
            this.questionForm.success_media_content = item.file_id
            this.questionForm.success_media_content_type = item.type
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
                answers: [],
                success_message: null,
                failure_message: null,
                success_media_content: null,
                failure_media_content: null,
                success_media_content_type: null,
                failure_media_content_type: null,


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
                this.loadQuizRounds()
            }).catch(err => {
                this.$notify("Ошибка создания вопроса");
            })

        },

    }
}
</script>


