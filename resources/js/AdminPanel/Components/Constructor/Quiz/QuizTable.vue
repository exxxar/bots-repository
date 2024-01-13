<script setup>

import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>

    <div class="row py-2">
        <div class="col-12">
            <div class="input-group mb-3">
                <input type="search" class="form-control "
                       placeholder="Поиск события"
                       aria-label="Поиск события"
                       v-model="search"
                       aria-describedby="quiz-search-quiz">
                <button class="btn btn-outline-secondary "
                        @click="loadQuizzes(0)"
                        type="button"
                        id="quiz-search-quiz">Найти
                </button>
            </div>
        </div>
        <div class="col-12">
            <table class="table" v-if="quizzes.length>0">
                <thead>

                <tr>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('id')">#</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('title')">Название</th>
                    <th scope="col">Число вопросов</th>
                    <th scope="col">Число команд</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('image')">Изображение к квизу</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('description')">Описание</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('completed_at')">Квиз пройден</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('start_at')">Дата и время начала</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('end_at')">Дата и время завершения</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('display_type')">Тип отображения</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('show_answers')">Показывать ответы по
                        окончанию раунда
                    </th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('updated_at')">Дата изменения</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(quiz, index) in quizzes"
                    v-bind:class="{'border-info':quiz.deleted_at==null,'border-danger':quiz.deleted_at!=null}">
                    <th scope="row">{{ quiz.id }}</th>
                    <td @click="selectEvent(quiz)">{{ quiz.title || 'Не указано' }}
                    </td>
                    <td>{{ (quiz.questions || [] ).length }}</td>
                    <td>{{ (quiz.commands || [] ).length }}</td>
                    <td>{{ quiz.image || 'Не указано' }}</td>
                    <td>{{ quiz.description || 'Не указано' }}</td>
                    <td>
                        <p v-if="quiz.completed_at" class="mb-0"> {{ $filters.currentFull(quiz.completed_at) }}</p>
                        <p v-else>Не задано</p>

                    </td>
                    <td>
                        <p v-if="quiz.start_at" class="mb-0"> {{ $filters.currentFull(quiz.start_at) }}</p>
                        <p v-else>Не задано</p>
                    </td>
                    <td>
                        <p v-if="quiz.end_at" class="mb-0"> {{ $filters.currentFull(quiz.end_at) }}</p>
                        <p v-else>Не задано</p>
                    </td>
                    <td>
                        {{ quiz.display_type }}
                    </td>
                    <td>
                        {{ quiz.time_limit }}
                    </td>

                    <td>
                        <i class="fa-solid fa-chevron-down text-success" v-if="quiz.show_answers"></i>
                        <i class="fa-solid  fa-xmark text-danger" v-else></i>
                    </td>
                    <td>{{ $filters.current(quiz.updated_at) }}</td>
                    <td>
                        <div class="dropdown" v-if="quiz.id">
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li v-if="quiz.deleted_at==null">
                                    <a class="dropdown-item"
                                       @click="removeQuiz(quiz.id)"
                                       href="javascript:void(0)">Удалить</a></li>
                            </ul>
                        </div>

                    </td>
                </tr>


                </tbody>
            </table>
            <p v-else>На текущий момент нет событий в вашем списке</p>
        </div>
        <div class="col-12">
            <Pagination
                v-on:pagination_page="nextQuizzes"
                v-if="paginate_object"
                :pagination="paginate_object"/>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["bot"],
    data() {
        return {
            direction: 'desc',
            order: 'updated_at',
            show: true,
            loading: true,
            quizzes: [],
            search: null,
            paginate_object: null,
        }
    },

    computed: {
        ...mapGetters(['getQuizzes', 'getQuizzesPaginateObject']),

    },
    mounted() {
        this.loadQuizzes();
    },
    methods: {
        removeQuiz(id) {
            this.loading = true
            this.$store.dispatch("removeQuiz", {
                quizId: id

            }).then(resp => {
                this.loading = false
                this.loadQuizzes(0)
                this.$notify("Событие успешно удалено");
            }).catch(() => {
                this.loading = false
                this.$notify("Ошибка удаления события")
            })
        },


        nextQuizzes(index) {
            this.loadQuizzes(index)
        },
        selectEvent(quiz) {
            this.$emit("select", quiz)
        },
        loadAndOrder(order) {
            this.order = order
            this.direction = this.direction === 'desc' ? 'asc' : 'desc'
            this.loadQuizzes(0)
        },
        loadQuizzes(page = 0) {
            this.loading = true
            this.$store.dispatch("loadQuizzes", {
                dataObject: {
                    bot_id: this.bot.id || null,
                    search: this.search,
                    order: this.order,
                    direction: this.direction
                },
                page: page,
                size: 20
            }).then(resp => {
                this.loading = false
                this.quizzes = this.getQuizzes
                this.paginate_object = this.getQuizzesPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
