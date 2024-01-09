<script setup>

import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>

    <div class="row">
        <div class="col-12">
            <table class="table" v-if="services.length>0">
                <thead>
                <tr>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('id')">#</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('bot_user_id')">Пользователь</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('appointment_schedule_id')">Дата
                        записи
                    </th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('status')">Статус</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('name')">Посетитель</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('phone')">Телефон посетителя</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('info')">Доп.инф</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('updated_at')">Дата изменения</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(service, index) in services"
                    @click="selectAppointment(service)"
                    v-bind:class="{'border-info':service.deleted_at==null,'border-danger':service.deleted_at!=null}">
                    <th scope="row">{{ service.id }}</th>
                    <td>{{ service.botUser.fio_from_telegram || service.botUser.telegram_chat_id || 'Не указано' }}
                    </td>
                    <td>
                        <p v-if="service.schedule"> Запись на {{ days[service.schedule.day- 1] || 'Не указано' }} с
                            {{ service.schedule.start_time || 'Не указано' }}
                            до {{ service.schedule.end_time || 'Не указано' }}</p>
                    </td>
                    <td>{{ statuses[service.status] }}</td>
                    <td>{{ $filters.current(service.updated_at) }}</td>
                    <td>{{ service.name || 'не указано' }}</td>
                    <td>{{ service.phone || 'не указано' }}</td>
                    <td>{{ service.info || 'не указано' }}</td>
                    <td>
                        <div class="dropdown" v-if="service.id">
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li v-if="service.deleted_at==null">
                                    <a class="dropdown-item"
                                       @click="removeAppointment(service.id)"
                                       href="javascript:void(0)">Удалить</a></li>
                            </ul>
                        </div>

                    </td>
                </tr>


                </tbody>
            </table>
            <p v-else>На текущий момент нет ни одного созданного сервиса</p>
        </div>
        <div class="col-12">
            <Pagination
                v-on:pagination_page="nextAppointments"
                v-if="paginate_object"
                :pagination="paginate_object"/>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["bot", "eventId"],
    data() {
        return {
            direction: 'desc',
            order: 'updated_at',
            show: true,
            is_group: false,
            loading: true,
            services: [],
            search: null,
            statuses: [
                "Новая запись",
                "Подтверждение записи",
                "Прохождение по записи",
                "Отмена записи"
            ],
            days: [
                "понедельник",
                "вторник",
                "среду",
                "четверг",
                "пятницу",
                "субботу",
                "воскресенье",
            ],
            paginate_object: null,
        }
    },

    computed: {
        ...mapGetters(['getAppointments', 'getAppointmentsPaginateObject']),

    },
    mounted() {
        this.loadAppointments();
    },
    methods: {
        nextAppointments(index) {
            this.loadAppointments(index)
        },
        selectAppointment(appointment) {
            this.$emit("select", appointment)
        },
        loadAndOrder(order) {
            this.order = order
            this.direction = this.direction === 'desc' ? 'asc' : 'desc'
            this.loadAppointments(0)
        },
        removeAppointment(id){
            this.loading = true
            this.$store.dispatch("removeAppointment", {
                dataObject: {
                    appointmentId: id,
                },
            }).then(resp => {
                this.loading = false
                this.loadAppointments(0)
                this.$notify("Запись успешно удалена");
            }).catch(() => {
                this.loading = false
                this.$notify("Ошибка удаления записи")
            })
        },
        loadAppointments(page = 0) {
            this.loading = true
            this.$store.dispatch("loadAppointments", {
                dataObject: {
                    event_id: this.eventId,
                    bot_id: this.bot.id || null,
                    search: this.search,
                    order: this.order,
                    direction: this.direction
                },
                page: page,
                size: 100
            }).then(resp => {
                this.loading = false
                this.services = this.getAppointments
                this.paginate_object = this.getAppointmentsPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
