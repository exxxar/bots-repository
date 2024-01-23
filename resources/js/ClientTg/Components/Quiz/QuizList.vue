<script setup>

import Pagination from '@/ClientTg/Components/Pagination.vue';
</script>
<template>

    <div class="row py-2">

<!--
       <video
            width="480"
            controls
            poster="/images/load.gif">
            <source
                src="/file-by-file-id/nextitgroup_bot/DQACAgIAAxkBAAIjpWV5uwGwbULL5-fsrTEhth9QUlLsAAIWOwACuxyASz_Q4sIZLZqXMwQ"
                type="video/mp4" />
        </video>
-->

<!--        <div class="col-12">
            <div class="input-group mb-3">
                <input type="search" class="form-control "
                       placeholder="Поиск квиза"
                       aria-label="Поиск квиза"
                       v-model="search"
                       aria-describedby="quiz-search-quiz">

            </div>

            <button class="btn btn-outline-secondary w-100"
                    @click="loadQuizzes(0)"
                    type="button"
                    id="quiz-search-quiz">Найти
            </button>
        </div>-->
        <div class="col-12 py-2" v-if="quizzes.length>0">

            <div class="card w-100 shadow-xl rounded-sm" v-for="(quiz, index) in quizzes">
                <img v-lazy="'/file-by-file-id/'+quiz.image" class="card-img-top rounded-m p-2" alt="...">
                <div class="card-body">
                    <h5 class="card-title">{{ quiz.title || 'нет заголовка' }}</h5>
                    <p class="card-text">{{ quiz.description || 'нет заголовка' }}</p>

                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><i class="fa-solid fa-shuffle"></i> {{types[quiz.display_type]}} </li>
                    <li class="list-group-item"><i class="fa-solid fa-stopwatch-20"></i> на вопрос {{quiz.time_limit}} сек</li>
                    <li class="list-group-item"><i class="fa-regular fa-circle-question"></i> Число вопросов {{ (quiz.questions || [] ).length }}</li>
                    <li class="list-group-item" v-if="!quiz.polling_mode"><i class="fa-solid fa-person-circle-question"></i> Число команд {{ (quiz.commands || [] ).length }}</li>
                    <li class="list-group-item" v-if="!quiz.polling_mode"><i class="fa-solid fa-link"></i>
                        <a href="javascript:void(0)" @click="openLinkModal(quiz.id)">Поделиться ссылкой</a>
                    </li>
                </ul>
                <div class="card-body">
                    <a href="javascript:void(0)"
                       @click="startQuiz(quiz)"
                       class="btn btn-m btn-full mb-3 rounded-xl text-uppercase font-900 shadow-s bg-blue1-dark">Начать</a>
                </div>
            </div>


        </div>
        <div class="col-12" v-if="quizzes.length>0">
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
    data() {
        return {
            direction: 'desc',
            order: 'updated_at',
            show: true,
            types: [
                "По порядку",
                "Перемешать всё",
                "Перемешать ответы",
                "Перемешать вопросы"
            ],
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
        openLinkModal(id){
          this.$cashback.qr("007quiz"+id)
        },
        startQuiz(item){
            this.$emit("start", item)
        },
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

        createQuiz(){
            this.$emit("create")
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
