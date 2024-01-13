<script setup>

import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>

    <div class="row">
        <div class="col-12">
            <table class="table" v-if="questions.length>0">
                <thead>

                <tr>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('id')">#</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('text')">Текст вопроса</th>
                    <th scope="col">Число ответов</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('round')">Раунд</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('media_content')">Контент</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('content_type')">Тип контента</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('is_multiply')">Множественный</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('is_open')">Открытый</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('updated_at')">Дата изменения</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(question, index) in questions"
                    v-bind:class="{'border-info':question.deleted_at==null,'border-danger':question.deleted_at!=null}">
                    <th scope="row">{{ question.id }}</th>
                    <td @click="selectQuizQuestion(question)">{{ question.text || 'Не указано' }}
                    </td>
                    <td>{{ (question.answers || []).length }}</td>
                    <td>{{ question.round || 'Не указано' }}</td>
                    <td>{{ question.media_content || 'Не указано' }}</td>
                    <td>{{ question.content_type || 'Не указано' }}</td>
                    <td>
                        <i class="fa-solid fa-chevron-down text-success" v-if="question.is_multiply"></i>
                        <i class="fa-solid  fa-xmark text-danger" v-else></i>
                    </td>
                    <td>
                        <i class="fa-solid fa-chevron-down text-success" v-if="question.is_open"></i>
                        <i class="fa-solid  fa-xmark text-danger" v-else></i>
                    </td>
                    <td>{{ $filters.current(question.updated_at) }}</td>
                    <td>
                        <div class="dropdown" v-if="question.id">
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li v-if="question.deleted_at==null">
                                    <a class="dropdown-item"
                                       @click="removeQuizQuestion(question.id)"
                                       href="javascript:void(0)">Удалить</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>


                </tbody>
            </table>
            <p v-else>На текущий момент нет ни одного созданного вопроса</p>
        </div>
        <div class="col-12">
            <Pagination
                v-on:pagination_page="nextQuizQuestion"
                v-if="paginate_object"
                :pagination="paginate_object"/>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["bot", "quizId"],
    data() {
        return {
            direction: 'desc',
            order: 'updated_at',
            show: true,
            is_group: false,
            loading: true,
            questions: [],
            search: null,
            paginate_object: null,
        }
    },

    computed: {
        ...mapGetters(['getQuizQuestions', 'getQuizQuestionsPaginateObject']),

    },
    mounted() {
        this.loadQuizQuestions();
    },
    methods: {
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
                    bot_id: this.bot.id || null,
                    search: this.search,
                    order: this.order,
                    direction: this.direction
                },
                page: page,
                size: 100
            }).then(resp => {
                this.loading = false
                this.questions = this.getQuizQuestions
                this.paginate_object = this.getQuizQuestionsPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
