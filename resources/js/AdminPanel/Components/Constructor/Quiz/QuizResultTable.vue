<script setup>

import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>

    <div class="row">
        <div class="col-12">
            <table class="table" v-if="results.length>0">
                <thead>

                <tr>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('id')">#</th>
                    <th scope="col">Название квиза</th>
                    <th scope="col">Название команды</th>
                    <th scope="col">Число участников</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('points')">Баллы</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('times')">Время</th>
                    <th scope="col">Результат</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(result, index) in results"
                    v-bind:class="{'border-info':result.deleted_at==null,'border-danger':result.deleted_at!=null}">
                    <th scope="row">{{ result.id }}</th>
                    <td @click="selectQuizResult(result)">{{ result.quiz.title || 'Не указано' }}
                    </td>
                    <td><span v-if="result.command">{{ result.command.title || 'Не указано' }}</span></td>
                    <td>{{ (result.command.players || []).length }}</td>
                    <td>{{ result.points || 'Не указано' }}</td>
                    <td>{{ result.times || 'Не указано' }}</td>
                    <td>-</td>
                    <td>
                        <div class="dropdown" v-if="result.id">
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li v-if="result.deleted_at==null">
                                    <a class="dropdown-item"
                                       @click="removeQuizResult(result.id)"
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
                v-on:pagination_page="nextQuizResult"
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
            results: [],
            search: null,
            paginate_object: null,
        }
    },

    computed: {
        ...mapGetters(['getQuizResults', 'getQuizResultsPaginateObject']),

    },
    mounted() {
        this.loadQuizResults();
    },
    methods: {
        nextQuizResult(index) {
            this.loadQuizResults(index)
        },
        selectQuizResult(result) {
            this.$emit("select", result)
            this.$notify("Вопрос успешно выбран");
        },
        removeQuizResult(id) {
            this.loading = true
            this.$store.dispatch("removeQuizResult", {
                quizResultId: id,

            }).then(resp => {
                this.loading = false
                this.loadQuizResults(0)
                this.$notify("Вопрос успешно удален");
            }).catch(() => {
                this.loading = false
                this.$notify("Вопрос удаления сервиса")
            })
        },
        loadAndOrder(order) {
            this.order = order
            this.direction = this.direction === 'desc' ? 'asc' : 'desc'
            this.loadQuizResults(0)
        },
        loadQuizResults(page = 0) {
            this.loading = true
            this.$store.dispatch("loadQuizResults", {
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
                this.results = this.getQuizResults


                this.paginate_object = this.getQuizResultsPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
