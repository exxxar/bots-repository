<script setup>

import Pagination from '@/ClientTg/Components/Pagination.vue';
</script>
<template>


    <div class="card card-style">
        <div class="content mb-2">
            <slot name="header"></slot>

            <div class="list-group list-custom-large" v-if="commands.length>0">
                <a href="#" v-for="(command, index) in commands">
                    <i class="fa-solid fa-check font-18 color-green1-dark"></i>
                    <span>{{ command.title || 'Не указано' }}</span>
                    <strong>{{ command.description || 'Не указано' }}</strong>
                    <i class="fa fa-angle-right"></i>
                </a>

            </div>

            <Pagination
                class="mb-0"
                v-on:pagination_page="nextQuizCommand"
                v-if="paginate_object&&commands.length>0"
                :pagination="paginate_object"/>

            <p v-else>Еще нет созданных команд</p>
        </div>
    </div>


<!--    <div v-if="commands.length>0">

        <div class="card card-style bg-28" data-card-height="130" style="height: 130px;"
             v-for="(command, index) in commands">
            <div class="card-center">
                <h3 class="color-black font-700 text-center mb-0">Find your Style</h3>
                <p class="color-black text-center opacity-40 mt-n1 mb-0">
                    <i class="fa-solid fa-user-tie ml-3"></i> {{ (command.players || []).length }} участников
                    {{ command.description || 'Не указано' }}</p>
            </div>
            <div class="card-overlay bg-white opacity-90"></div>
        </div>

        <div class="card card-style mb-0" v-for="(command, index) in commands">
            <h4 href="#" class="footer-title pt-2">{{ command.title || 'Не указано' }}</h4>
            <p class="text-center font-12 mt-n1 mb-0 opacity-70">
                &lt;!&ndash;                    <i class="fa-solid fa-hashtag"></i> {{ command.id }}<br>&ndash;&gt;
                <i class="fa-solid fa-user-tie ml-3"></i> {{ (command.players || []).length }} участников
            </p>
            <p class="boxed-text-l mb-0">
                {{ command.description || 'Не указано' }}
            </p>

            <div class="content" v-if="command.players.length>0">
                <div class="list-group list-custom-small" v-for="player in command.players">
                    <a href="javascript:void(0)">
                        <i class="fa-solid fa-user-tie color-facebook"></i>
                        <span> {{ player.fio_from_telegram || player.telegram_chat_id }}</span>
                        <i class="fa fa-link"></i>
                    </a>
                </div>
            </div>

            <div class="footer-copyright p-2 mb-1 d-flex justify-content-center flex-wrap">
                <a href="javascript:void(0)"
                   class="float-right bg-highlight btn btn-xs text-uppercase font-900 rounded-xl font-12 w-100">
                    В команду
                </a>
                <a href="javascript:void(0)"
                   v-if="command.captain_id == self.id"
                   class="float-right btn btn-link font-10 w-100 py-3">
                    Удалить
                </a>
            </div>
            <div class="divider divider-small my-3 bg-highlight "></div>
        </div>

    </div>
    <div v-if="commands.length>0">
        <Pagination
            class="mb-0"
            v-on:pagination_page="nextQuizCommand"
            v-if="paginate_object"
            :pagination="paginate_object"/>
        <div v-else>
            <p>Еще нет созданных команд</p>
        </div>
    </div>-->

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["quizId"],
    data() {
        return {
            direction: 'desc',
            order: 'updated_at',
            show: true,
            is_group: false,
            loading: true,
            commands: [],
            search: null,
            paginate_object: null,
        }
    },

    computed: {
        ...mapGetters(['getQuizCommands', 'getQuizCommandsPaginateObject']),
        self() {
            return window.self
        },

    },
    mounted() {
        this.loadQuizCommands();
    },
    methods: {
        nextQuizCommand(index) {
            this.loadQuizCommands(index)
        },
        selectQuizCommand(command) {
            this.$emit("select", command)
            this.$notify("Вопрос успешно выбран");
        },
        loadAndOrder(order) {
            this.order = order
            this.direction = this.direction === 'desc' ? 'asc' : 'desc'
            this.loadQuizCommands(0)
        },
        loadQuizCommands(page = 0) {
            this.loading = true
            this.$store.dispatch("loadQuizCommands", {
                dataObject: {
                    quiz_id: this.quizId,
                    search: this.search,
                    order: this.order,
                    direction: this.direction
                },
                page: page,
                size: 100
            }).then(resp => {
                this.loading = false
                this.commands = this.getQuizCommands


                this.paginate_object = this.getQuizCommandsPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
