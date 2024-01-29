<script setup>
import QuizItemSimple from "@/ClientTg/Components/Quiz/QuizItemSimple.vue";
import Pagination from '@/ClientTg/Components/Pagination.vue';
</script>
<template>

    <div class="row mb-0">

        <div class="col-12">
            <h5 class="text-center">Квизы для вас</h5>
        </div>
        <div class="col-12 py-2" v-if="quizzes.length>0">
            <QuizItemSimple
                v-on:select="openQuiz"
                :quiz="item" v-for="(item, index) in quizzes"></QuizItemSimple>
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

        openQuiz(item){
            this.$emit("open", item)
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
