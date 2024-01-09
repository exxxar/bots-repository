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
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('title')">Название</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('subtitle')">Подзаголовок</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('description')">Описание</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('is_group')">Групповое</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('updated_at')">Дата изменения</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(service, index) in services"
                    v-bind:class="{'border-info':service.deleted_at==null,'border-danger':service.deleted_at!=null}">
                    <th scope="row">{{ service.id }}</th>
                    <td @click="selectService(service)">{{ service.title || 'Не указано' }}
                    </td>
                    <td>{{ service.subtitle || 'Не указано' }}</td>
                    <td>{{ service.description || 'Не указано' }}</td>
                    <td>
                        <i class="fa-solid fa-chevron-down text-success" v-if="service.is_group"></i>
                        <i class="fa-solid  fa-xmark text-danger" v-else></i>
                    </td>
                    <td>{{ $filters.current(service.updated_at) }}</td>
                    <td>
                        <div class="dropdown" v-if="service.id">
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li v-if="service.deleted_at==null">
                                    <a class="dropdown-item"
                                       @click="removeAppointmentService(service.id)"
                                       href="javascript:void(0)">Удалить</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>


                </tbody>
            </table>
            <p v-else>На текущий момент нет ни одного созданого сервиса</p>
        </div>
        <div class="col-12">
            <Pagination
                v-on:pagination_page="nextAppointmentService"
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
            paginate_object: null,
        }
    },

    computed: {
        ...mapGetters(['getAppointmentServices', 'getAppointmentServicesPaginateObject']),

    },
    mounted() {
        this.loadAppointmentServices();
    },
    methods: {
        nextAppointmentService(index) {
            this.loadAppointmentServices(index)
        },
        selectService(service){
          this.$emit("select", service)
        },
        removeAppointmentService(id){
            this.loading = true
            this.$store.dispatch("removeAppointmentService", {
                dataObject: {
                    appointmentServiceId: id,
                },
            }).then(resp => {
                this.loading = false
                this.loadAppointmentServices(0)
                this.$notify("Сервис успешно удален");
            }).catch(() => {
                this.loading = false
                this.$notify("Ошибка удаления сервиса")
            })
        },
        loadAndOrder(order) {
            this.order = order
            this.direction = this.direction === 'desc' ? 'asc' : 'desc'
            this.loadAppointmentServices(0)
        },
        loadAppointmentServices(page = 0) {
            this.loading = true
            this.$store.dispatch("loadAppointmentServices", {
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
                this.services = this.getAppointmentServices
                this.paginate_object = this.getAppointmentServicesPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
