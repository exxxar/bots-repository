<script setup>
import BotMediaList from "@/AdminPanel/Components/Constructor/BotMediaList.vue";
import InlineInjectButtons from "@/AdminPanel/Components/Constructor/Helpers/InlineInjectButtons.vue";
</script>
<template>
    <form v-on:submit.prevent="submitForm" class="py-3">
        <div class="row ">
            <div class="col-12 mb-3">
                <label class="form-label" id="quiz-title">
                    <Popper
                        content="Название вашего квиза">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Название квиза

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <input type="text" class="form-control"
                       placeholder="Название"
                       aria-label="Название"
                       v-model="quizForm.title"
                       maxlength="255"
                       aria-describedby="quiz-title" required>
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
                                Изображение, которое видит пользователь при выборе квиза
                            </div>
                        </template>
                    </Popper>
                    Изображение к квизу
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <BotMediaList
                    :need-photo="true"
                    :selected="quizForm.image"
                    v-on:select="selectPhoto"></BotMediaList>
            </div>

            <div class="col-12 mb-3">

                <label class="form-label " id="quiz-description">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Краткая информация о квизе
                            </div>
                        </template>
                    </Popper>
                    Описание события
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="quizForm.description">
                        Длина текста {{ quizForm.description.length }}/255</small>
                </label>
                <textarea class="form-control"
                          placeholder="Описание мероприятия"
                          aria-label="Описание мероприятия"
                          maxlength="255"
                          v-model="quizForm.description"
                          aria-describedby="quiz-description" required>
                    </textarea>

            </div>

            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="quizForm.polling_mode"
                           type="checkbox" id="polling_mode">
                    <label class="form-check-label" for="polling_mode">
                        Квиз в режиме опроса (без команд и таймеров)
                    </label>
                </div>
            </div>

            <div class="col-12 mb-3" v-if="!quizForm.polling_mode">

                <label class="form-label " id="quiz-time_limit">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Время на ответ
                            </div>
                        </template>
                    </Popper>
                    Лимит времени на ответ в секундах
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <input type="number" class="form-control"
                       placeholder="Лимит времени на ответ"
                       aria-label="Лимит времени на ответ"
                       step="0.1"
                       min="0"
                       v-model="quizForm.time_limit"
                       aria-describedby="quiz-time_limit" required>


            </div>


            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="quizForm.show_answers"
                           type="checkbox" id="is-group">
                    <label class="form-check-label" for="is-group">
                        Показывать ответы по окончанию раунда
                    </label>
                </div>
            </div>

            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="quizForm.is_active"
                           type="checkbox" id="is_active">
                    <label class="form-check-label" for="is_active">
                        Квиз виден пользователям
                    </label>
                </div>
            </div>

            <div class="col-12 mb-3" v-if="!quizForm.polling_mode">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="quizForm.round_mode"
                           type="checkbox" id="round_mode">
                    <label class="form-check-label" for="round_mode">
                        Использовать систему раундов
                    </label>
                </div>
            </div>



            <div class="col-12 mb-3">

                <label class="form-label " id="quiz-time_limit">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Число попыток прохождения квиза
                            </div>
                        </template>
                    </Popper>
                    Количество попыток
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <input type="number" class="form-control"
                       placeholder="Число попыток"
                       aria-label="Число попыток"
                       step="1"
                       min="1"
                       v-model="quizForm.try_count"
                       aria-describedby="quiz-try_count" required>


            </div>

            <div class="col-12 mb-3">

                <label class="form-label " id="quiz-time_limit">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Сколько % нужно ответить чтоб пройти
                            </div>
                        </template>
                    </Popper>
                    % для прохождения
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <input type="number" class="form-control"
                       placeholder="Процент для победы"
                       aria-label="Процент для победы"
                       step="1"
                       min="0"
                       v-model="quizForm.success_percent"
                       aria-describedby="quiz-success_percent" required>


            </div>

        </div>
        <div class="row">
            <div class="col-12 py-2">
                <button class="btn btn-outline-info rounded-5"
                        type="button"
                        @click="addMessage('success_message',null)">Добавить еще вариант текста
                </button>
            </div>
            <div class="col-12 mb-3" v-for="(message, index) in quizForm.success_message">

                <div class="row">
                    <div class="col-10">
                        <div class="d-flex justify-content-between">
                            <label class="form-label " id="quiz-success_message">
                                <Popper>
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                    <template #content>
                                        <div>
                                            Текст в случае успешного прохождения квиза
                                        </div>
                                    </template>
                                </Popper>
                                Текст при победе
                                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                                <small class="text-gray-400 ml-3" style="font-size:10px;"
                                       v-if="quizForm.success_message[index]">
                                    Длина текста {{ quizForm.success_message[index].length }}/255</small>
                            </label>

                            <InlineInjectButtons
                                :param="'success_message'"
                                v-on:callback="attachTo"></InlineInjectButtons>
                        </div>

                        <textarea class="form-control"
                                  placeholder="Текст при победе"
                                  aria-label="Текст при победе"
                                  maxlength="255"
                                  v-model="quizForm.success_message[index]"
                                  aria-describedby="quiz-success_message" required>
                    </textarea>

                    </div>
                    <div class="col-2 d-flex align-items-center justify-content-center">
                        <button class="btn btn-outline-danger"
                                :disabled="quizForm.success_message.length<=1"
                                @click="removeMessage('success_message', index)">Удалить
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-12 py-2">
                <button class="btn btn-outline-info rounded-5"
                        type="button"
                        @click="addMessage('failure_message',null)">Добавить еще вариант текста
                </button>
            </div>
            <div class="col-12 mb-3" v-for="(message, index) in quizForm.failure_message">

                <div class="row">
                    <div class="col-10">
                        <div class="d-flex justify-content-between">
                            <label class="form-label " :id="'quiz-failure_message'+index">
                                <Popper>
                                    <i class="fa-regular fa-circle-question mr-1"></i>
                                    <template #content>
                                        <div>
                                            Текст в случае неудачного прохождения квиза
                                        </div>
                                    </template>
                                </Popper>
                                Текст при проигрыше
                                <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                                <small class="text-gray-400 ml-3" style="font-size:10px;"
                                       v-if="quizForm.failure_message[index]">
                                    Длина текста {{ quizForm.failure_message[index].length }}/255</small>
                            </label>
                            <InlineInjectButtons
                                :param="'failure_message'"
                                v-on:callback="attachTo($event.target.value, index)"></InlineInjectButtons>
                        </div>


                        <textarea class="form-control"
                                  placeholder="Текст при проигрыше"
                                  aria-label="Текст при проигрыше"
                                  maxlength="255"
                                  v-model="quizForm.failure_message[index]"
                                  aria-describedby="quiz-success_message" required>
                    </textarea>
                    </div>
                    <div class="col-2 d-flex align-items-center justify-content-center">
                        <button class="btn btn-outline-danger"
                                :disabled="quizForm.failure_message.length<=1"
                                @click="removeMessage('failure_message', index)">Удалить
                        </button>
                    </div>
                </div>


            </div>


        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <div class="form-floating">
                    <select class="form-select"
                            v-model="quizForm.display_type"
                            id="quiz-display-type" aria-label="quiz-display-type">
                        <option :value="index" v-for="(type, index) in types" :selected="index==quizForm.display_type">
                            {{ type }}
                        </option>
                    </select>
                    <label for="quiz-display-type">Тип вывода вопросов</label>
                </div>
            </div>
        </div>

<!--        <div class="row" v-if="!quizForm.polling_mode">
            <div class="col-12 mb-3">

                <label class="form-label " id="quiz-time_limit">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Дата и время начала квиза
                            </div>
                        </template>
                    </Popper>
                    Дата и время начала начала
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <input type="datetime-local" class="form-control"
                       placeholder="Дата и время начала квиза "
                       aria-label="Дата и время начала квиза"
                       maxlength="255"
                       v-model="quizForm.start_at"
                       aria-describedby="quiz-start_at" required>


            </div>

            <div class="col-12 mb-3">

                <label class="form-label " id="quiz-time_limit">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Дата и время окончания квиза
                            </div>

                        </template>
                    </Popper>
                    Дата и время окончания квиза
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <input type="datetime-local" class="form-control"
                       placeholder="Дата и время окончания квиза"
                       aria-label="Дата и время окончания квиза"
                       maxlength="255"
                       v-model="quizForm.end_at"
                       aria-describedby="quiz-end_at" required>


            </div>

            <div class="col-12 mb-3">

                <label class="form-label " id="quiz-time_limit">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Дата и время окончания квиза. Квиз создается на 1 игру. Данное поле заполняется
                                автоматически при прохождении квиза командой.
                            </div>
                        </template>
                    </Popper>
                    Дата и время прохождения квиза
                </label>
                <input type="datetime-local" class="form-control"
                       placeholder="Дата и время прохождения квиза"
                       aria-label="Дата и время прохождения квиза"
                       maxlength="255"
                       v-model="quizForm.completed_at"
                       aria-describedby="quiz-completed_at">


            </div>
        </div>-->

        <div class="row">
            <div class="col-12">
                <button
                    type="submit" class="btn btn-outline-success w-100 p-3">
                    <span v-if="quizForm.id==null">Создать квиз</span>
                    <span v-else>Обновить квиз</span>
                </button>
            </div>
        </div>
    </form>
</template>

<script>
export default {
    props: ["quiz", "bot"],
    data() {
        return {
            step: 0,
            load: false,
            need_reset: false,
            need_services: false,
            need_media: false,
            types: [
                "По порядку",
                "Перемешать всё",
                "Перемешать ответы",
                "Перемешать вопросы"
            ],
            quizForm: {
                id: null,
                title: null,
                image: null,
                description: null,
                completed_at: null,
                start_at: null,
                end_at: null,
                display_type: 0,
                time_limit: 30,
                show_answers: false,
                polling_mode: false,
                round_mode: true,
                is_active: false,
                try_count: 1,
                success_percent: 50,
                success_message: ["Задание успешно пройдено!"],
                failure_message: ["К сожалению вы не прошли задание!"],

            }
        }
    },
    watch: {
        quizForm: {
            handler(val) {
                this.need_reset = true
            },
            deep: true
        },
        'quizForm.polling_mode': {
            handler(val) {
                this.quizForm.round_mode = false
            },
            deep: true
        }
    },
    mounted() {

        if (this.quiz)
            this.$nextTick(() => {
                this.quizForm = {
                    id: this.quiz.id || null,
                    title: this.quiz.title || null,
                    image: this.quiz.image || null,
                    description: this.quiz.description || null,
                    completed_at: this.quiz.completed_at ? this.$filters.currentFull(this.quiz.completed_at) : null,
                    start_at: this.quiz.start_at ? this.$filters.currentFull(this.quiz.start_at) : null,
                    end_at: this.quiz.end_at ? this.$filters.currentFull(this.quiz.end_at) : null,
                    display_type: this.quiz.display_type || 0,
                    time_limit: this.quiz.time_limit || 30,
                    show_answers: this.quiz.show_answers || false,
                    polling_mode: this.quiz.polling_mode || false,
                    round_mode: this.quiz.round_mode || false,
                    is_active: this.quiz.is_active || false,
                    try_count: this.quiz.try_count || 1,
                    success_percent: this.quiz.success_percent || 50,
                    success_message: this.quiz.success_message || ["Задание успешно пройдено!"],
                    failure_message: this.quiz.failure_message || ["К сожалению вы не прошли задание!"],
                }

            })

    },
    methods: {
        attachTo(item, index) {
            if (this.quizForm[item.param][index] == null)
                this.quizForm[item.param][index] = item.value
            else
                this.quizForm[item.param][index] += item.value;
        },
        selectPhoto(item) {
            this.quizForm.image = item.file_id
        },
        removeMessage(field, index) {
            this.quizForm[field || 'success_message'].splice(index, 1)
        },
        addMessage(field, message) {
            this.quizForm[field || 'success_message'].push(message)
        },
        submitForm() {
            let data = new FormData();
            Object.keys(this.quizForm)
                .forEach(key => {
                    const item = this.quizForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append('bot_id', this.bot.id);

            this.$store.dispatch("storeQuiz",
                {
                    quizForm: data
                }).then((response) => {

                this.quizForm = {
                    id: null,
                    title: null,
                    image: null,
                    description: null,
                    completed_at: null,
                    start_at: null,
                    end_at: null,
                    display_type: 0,
                    time_limit: 30,
                    show_answers: false,
                    polling_mode: false,
                    round_mode: false,
                    is_active: false,
                    try_count: 1,
                    success_percent: 50,
                    success_message: ["Задание успешно пройдено!"],
                    failure_message: ["К сожалению вы не прошли задание!"],
                }

                this.$emit("callback", response.data)
                this.$notify("Событие успешно создано");
            }).catch(err => {
                this.$notify("Ошибка создания события");
            })

        },

    }
}
</script>


