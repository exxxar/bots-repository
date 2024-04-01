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
                       aria-describedby="appointment-search-event">
                <button class="btn btn-outline-secondary "
                        @click="loadAppointmentEvents(0)"
                        type="button"
                        id="appointment-search-event">Найти
                </button>
            </div>
        </div>
        <div class="col-12">
            <table class="table" v-if="events.length>0">
                <thead>


                <tr>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('id')">#</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('title')">Название</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('subtitle')">Подзаголовок</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('address')">Адрес</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('description')">Описание</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('is_group')">Групповое</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('updated_at')">Дата изменения</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(event, index) in events"

                    v-bind:class="{'border-info':event.deleted_at==null,'border-danger':event.deleted_at!=null}">
                    <th scope="row">{{ event.id }}</th>
                    <td @click="selectEvent(event)">{{ event.title || 'Не указано' }}
                    </td>
                    <td>{{ event.subtitle || 'Не указано' }}</td>
                    <td>{{ event.address || 'Не указано' }}</td>
                    <td>{{ event.description || 'Не указано' }}</td>
                    <td>
                        <i class="fa-solid fa-chevron-down text-success" v-if="event.is_group"></i>
                        <i class="fa-solid  fa-xmark text-danger" v-else></i>
                    </td>
                    <td>{{ $filters.current(event.updated_at) }}</td>
                    <td>
                        <div class="dropdown" v-if="event.id">
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li v-if="event.deleted_at==null">
                                    <a class="dropdown-item"
                                       @click="removeAppointmentEvent(event.id)"
                                       href="javascript:void(0)">Удалить</a></li>

                                <li v-if="event.deleted_at==null">
                                    <a class="dropdown-item"
                                       @click="duplicateAppointmentEvent(event.id)"
                                       href="javascript:void(0)">Дублировать</a></li>
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
                v-on:pagination_page="nextAppointmentEvents"
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
            is_group: false,
            loading: true,
            events: [],
            search: null,
            paginate_object: null,
        }
    },

    computed: {
        ...mapGetters(['getAppointmentEvents', 'getAppointmentEventsPaginateObject']),

    },
    mounted() {
        this.loadAppointmentEvents();
    },
    methods: {
        duplicateAppointmentEvent(id){
            this.loading = true
            this.$store.dispatch("duplicateAppointmentEvent", {
                dataObject: {
                    appointmentEventId: id,
                    bot_id: this.bot.id
                },
            }).then(resp => {
                this.loading = false
                this.loadAppointmentEvents(0)
                this.$notify("Событие успешно продублировано");
            }).catch(() => {
                this.loading = false
                this.$notify("Ошибка дублирования события")
            })
        },
        removeAppointmentEvent(id){
            this.loading = true
            this.$store.dispatch("removeAppointmentEvent", {
                dataObject: {
                    appointmentEventId: id,
                },
            }).then(resp => {
                this.loading = false
                this.loadAppointmentEvents(0)
                this.$notify("Событие успешно удалено");
            }).catch(() => {
                this.loading = false
                this.$notify("Ошибка удаления события")
            })
        },

        nextAppointmentEvents(index) {
            this.loadAppointmentEvents(index)
        },
        selectEvent(event){
          this.$emit("select", event)
        },
        loadAndOrder(order) {
            this.order = order
            this.direction = this.direction === 'desc' ? 'asc' : 'desc'
            this.loadAppointmentEvents(0)
        },
        loadAppointmentEvents(page = 0) {
            this.loading = true
            this.$store.dispatch("loadAppointmentEvents", {
                dataObject: {
                    bot_id: this.bot.id || null,
                    search: this.search,
                    is_group: this.is_group,
                    order: this.order,
                    direction: this.direction
                },
                page: page,
                size: 20
            }).then(resp => {
                this.loading = false
                this.events = this.getAppointmentEvents
                this.paginate_object = this.getAppointmentEventsPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
