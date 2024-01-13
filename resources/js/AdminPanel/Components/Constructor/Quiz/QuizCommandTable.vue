<script setup>

import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>

    <div class="row">
        <div class="col-12">
            <table class="table" v-if="commands.length>0">
                <thead>

                <tr>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('id')">#</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('title')">Название</th>
                    <th scope="col">Число участников</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('description')">Описание</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(command, index) in commands"
                    v-bind:class="{'border-info':command.deleted_at==null,'border-danger':command.deleted_at!=null}">
                    <th scope="row">{{ command.id }}</th>
                    <td @click="selectQuizCommand(command)">{{ command.title || 'Не указано' }}
                    </td>
                    <td>{{ (command.players || []).length }}</td>
                    <td>{{ command.description || 'Не указано' }}</td>
                    <td>
                        <div class="dropdown" v-if="command.id">
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li v-if="command.deleted_at==null">
                                    <a class="dropdown-item"
                                       @click="removeQuizCommand(command.id)"
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
                v-on:pagination_page="nextQuizCommand"
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
            commands: [],
            search: null,
            paginate_object: null,
        }
    },

    computed: {
        ...mapGetters(['getQuizCommands', 'getQuizCommandsPaginateObject']),

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
        removeQuizCommand(id) {
            this.loading = true
            this.$store.dispatch("removeQuizCommand", {
                quizCommandId: id,

            }).then(resp => {
                this.loading = false
                this.loadQuizCommands(0)
                this.$notify("Вопрос успешно удален");
            }).catch(() => {
                this.loading = false
                this.$notify("Вопрос удаления сервиса")
            })
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
                    bot_id: this.bot.id || null,
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
