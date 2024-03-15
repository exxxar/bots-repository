<script setup>

import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>

    <div class="row">
        <div class="col-12">
            <table class="table" v-if="queries.length>0">
                <thead>

                <tr>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('id')">#</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('command')">Команда</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('description')">Описание</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(query, index) in queries"
                    v-bind:class="{'border-info':query.deleted_at==null,'border-danger':query.deleted_at!=null}">
                    <th scope="row">{{ query.id }}</th>
                    <td @click="selectInlineQuery(query)">{{ query.command || 'Не указано' }}
                    </td>
                    <td>{{ query.description || 'Не указано' }}</td>
                    <td>
                        <div class="dropdown" v-if="query.id">
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li v-if="query.deleted_at==null">
                                    <a class="dropdown-item"
                                       @click="removeInlineQuery(query.id)"
                                       href="javascript:void(0)">Удалить</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>


                </tbody>
            </table>
            <p v-else>На текущий момент нет ни одной созданной команды</p>
        </div>
        <div class="col-12">
            <Pagination
                v-on:pagination_page="nextInlineQuery"
                v-if="paginate_object"
                :pagination="paginate_object"/>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["bot", "queryId"],
    data() {
        return {
            direction: 'desc',
            order: 'updated_at',
            show: true,
            loading: true,
            queries: [],
            search: null,
            paginate_object: null,
        }
    },

    computed: {
        ...mapGetters(['getInlineQueries', 'getInlineQueriesPaginateObject']),

    },
    mounted() {
        this.loadInlineQueries();
    },
    methods: {
        nextInlineQuery(index) {
            this.loadInlineQueries(index)
        },
        selectInlineQuery(query) {
            this.$emit("select", query)
            this.$notify("Вопрос успешно выбран");
        },
        removeInlineQuery(id) {
            this.loading = true
            this.$store.dispatch("removeInlineQuery", {
                queryId: id,

            }).then(resp => {
                this.loading = false
                this.loadInlineQueries(0)
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
        loadInlineQueries(page = 0) {
            this.loading = true
            this.$store.dispatch("loadInlineQueries", {
                dataObject: {
                    bot_id: this.bot.id || null,
                    search: this.search,
                    order: this.order,
                    direction: this.direction
                },
                page: page,
                size: 100
            }).then(resp => {
                this.loading = false
                this.queries = this.getInlineQueries
                this.paginate_object = this.getInlineQueriesPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
