<script setup>

import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>

    <div class="row py-2">
        <div class="col-12">
            <div class="input-group mb-2">

                <div class="form-floating">
                    <input type="search"
                           v-model="search"
                           class="form-control" id="floatingInput" placeholder="Поиск рассылки">
                    <label for="floatingInput">Поиск рассылки</label>
                </div>

                <button class="btn btn-outline-secondary "
                        @click="loadQueues(0)"
                        type="button"
                        id="queue-search-queue">Найти
                </button>
            </div>
        </div>
        <div class="col-12">

            <div class="w-100" style="overflow-x: scroll;">
                <table class="table w-100" v-if="queues.length>0">
                    <thead>

                    <tr>
                        <th scope="col" class="cursor-pointer" @click="loadAndOrder('id')">#</th>
                        <th scope="col"  style="min-width:200px;" class="cursor-pointer" @click="loadAndOrder('content')">Содержимое</th>
                        <th scope="col">Есть нижняя клавиатура</th>
                        <th scope="col">Есть клавиатура под текстом</th>
                        <th scope="col" class="cursor-pointer" @click="loadAndOrder('images')">Изображения к рассылке</th>
                        <th scope="col" style="min-width:150px;" class="cursor-pointer" @click="loadAndOrder('sent_at')">Отправлено в</th>
                        <th scope="col" style="min-width:150px;"  class="cursor-pointer" @click="loadAndOrder('cron_time')">Дата отправки</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(queue, index) in queues"
                        v-bind:class="{'border-info':queue.deleted_at==null,'border-danger':queue.deleted_at!=null}">
                        <th scope="row">{{ queue.id }}</th>
                        <td style="min-width:200px;" @click="selectEvent(queue)">{{ queue.content || 'Не указано' }}
                        </td>
                        <td>{{ queue.reply_keyboard ? "Есть" : "Нет"  }}</td>
                        <td>{{ queue.inline_keyboard ? "Есть" : "Нет"  }}</td>
                        <td>{{ (queue.images || [] ).length }}</td>

                        <td style="min-width:150px;">
                            <p v-if="queue.sent_at" class="mb-0"> {{ $filters.currentFull(queue.sent_at) }}</p>
                            <p v-else>Не задано</p>
                        </td>

                        <td style="min-width:150px;">{{ $filters.currentFull(queue.cron_time) }}</td>
                        <td>
                            <div class="dropdown" v-if="queue.id">
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li v-if="queue.deleted_at==null">
                                        <a class="dropdown-item"
                                           @click="removeQueue(queue.id)"
                                           href="javascript:void(0)">Удалить</a></li>
                                </ul>
                            </div>

                        </td>
                    </tr>


                    </tbody>
                </table>
            </div>


            <div v-if="queues.length===0">
                <div class="alert alert-warning" role="alert">
                    <strong>Внимание!</strong> Вы еще не добавили ни одной рассылки!
                </div>
            </div>
        </div>
        <div class="col-12" v-if="queues.length>0">
            <Pagination
                v-on:pagination_page="nextQueues"
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
            queues: [],
            search: null,
            paginate_object: null,
        }
    },

    computed: {
        ...mapGetters(['getQueues', 'getQueuesPaginateObject']),


    },
    mounted() {
        this.loadQueues();
    },
    methods: {
        removeQueue(id) {
            this.loading = true
            this.$store.dispatch("removeQueue", {
                queueId: id

            }).then(resp => {
                this.loading = false
                this.loadQueues(0)
                this.$notify("Событие успешно удалено");
            }).catch(() => {
                this.loading = false
                this.$notify("Ошибка удаления события")
            })
        },

        createQueue(){
            this.$emit("create")
        },
        nextQueues(index) {
            this.loadQueues(index)
        },
        selectEvent(queue) {

            this.$emit("select", queue)
        },
        loadAndOrder(order) {
            this.order = order
            this.direction = this.direction === 'desc' ? 'asc' : 'desc'
            this.loadQueues(0)
        },
        loadQueues(page = 0) {
            this.loading = true
            this.$store.dispatch("loadQueues", {
                dataObject: {
                    search: this.search,
                    order: this.order,
                    direction: this.direction
                },
                page: page,
                size: 20
            }).then(resp => {
                this.loading = false
                this.queues = this.getQueues
                this.paginate_object = this.getQueuesPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
