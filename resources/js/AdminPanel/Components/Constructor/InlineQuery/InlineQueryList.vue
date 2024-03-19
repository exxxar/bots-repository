<script setup>

import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>

    <div class="row">

        <div class="col-12">
            <div class="input-group mb-3">
                <input type="search" class="form-control"
                       placeholder="Поиск страницы"
                       aria-label="Поиск бота"
                       v-model="search"
                       aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary"
                        @click="loadInlineQueries(0)"
                        type="button"
                        id="button-addon2">Найти
                </button>
            </div>
        </div>


        <div class="col-12">

            <div class="form-check">
                <input class="form-check-input"
                       @click="loadAndOrder('id')"
                       type="radio" name="flexRadioDefault" id="loadAndOrder-id">
                <label class="form-check-label" for="loadAndOrder-id">
                   По порядку
                    <i class="fa-solid fa-chevron-up" v-if="direction==='asc'&&order==='id'"></i>
                    <i class="fa-solid fa-chevron-down" v-if="direction==='desc'&&order==='id'"></i>
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input"
                       @click="loadAndOrder('command')"
                       type="radio" name="flexRadioDefault" id="loadAndOrder-command">
                <label class="form-check-label" for="loadAndOrder-command">
                    По команде
                    <i class="fa-solid fa-chevron-up" v-if="direction==='asc'&&order==='command'"></i>
                    <i class="fa-solid fa-chevron-down" v-if="direction==='desc'&&order==='command'"></i>
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input"
                       @click="loadAndOrder('updated_at')"
                       type="radio" name="flexRadioDefault" id="loadAndOrder-updated_at">
                <label class="form-check-label" for="loadAndOrder-updated_at">
                    По дате обновления
                    <i class="fa-solid fa-chevron-up" v-if="direction==='asc'&&order==='updated_at'"></i>
                    <i class="fa-solid fa-chevron-down" v-if="direction==='desc'&&order==='updated_at'"></i>
                </label>
            </div>

            <div class="card mb-2 cursor-pointer"
                 @click="selectInlineQuery(query)"
                 v-if="queries.length>0"
                 v-bind:class="{'border-info':query.deleted_at==null,'border-danger':query.deleted_at!=null}"
                 v-for="(query, index) in queries">
                <div class="card-body d-flex justify-content-between">

                    <p>{{ query.command || 'Не указано' }}</p>
                    <span
                        style="min-width:50px;"
                        class="badge bg-primary d-flex justify-content-center align-items-center">{{ query.items.length }}</span>


                </div>
            </div>


            <p v-else>На текущий момент нет ни одной созданной команды</p>
        </div>
        <div class="col-12 mt-2">
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
    data() {
        return {
            direction: 'desc',
            order: 'updated_at',
            show: true,
            loading: true,
            current_page: 0,
            queries: [],
            search: null,
            paginate_object: null,
        }
    },

    computed: {
        ...mapGetters(['getCurrentBot','getInlineQueries', 'getInlineQueriesPaginateObject']),

    },
    mounted() {


        this.loadCurrentBot().then(() => {
            if (this.bot)
                this.current_page = localStorage.getItem(`cashman_inline_query_list_${this.bot.id}_page_index`) || 0

            this.loadInlineQueries();
        })

    },
    methods: {
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot,

            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        nextInlineQuery(index) {
            this.loadInlineQueries(index)
        },
        selectInlineQuery(query) {
            this.$emit("select", query)
        },
        loadAndOrder(order) {
            this.order = order
            this.direction = this.direction === 'desc' ? 'asc' : 'desc'
            this.loadInlineQueries(0)
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
