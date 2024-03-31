<script setup>

import Pagination from '@/ClientTg/Components/Pagination.vue';
</script>
<template>

    <div class="card card-style">
        <div class="content">
            <div class="input-style input-style-2 input-required">
                <input class="form-control"
                       v-model="search"
                       type="search"
                       placeholder="Поиск события">
            </div>
            <button
                class="btn btn-border btn-m btn-full  rounded-s text-uppercase font-900 border-blue2-dark color-blue2-dark bg-theme w-100"
                @click="loadAppointmentEvents(0)"
                type="button"
                id="appointment-search-event">Найти
            </button>
        </div>
        <div class="content">
            <p style="line-height:100%;">
                <strong class="font-10"> Тип сортировки:</strong>

                <a
                    class="mx-1 font-10 my-1"
                    @click="loadAndOrder('id')"
                    href="javascript:void(0)">По порядку следования
                    <i class="fa-solid fa-arrow-down-short-wide" v-if="order==='id'&&direction==='asc'"></i>
                    <i class="fa-solid fa-arrow-up-wide-short" v-if="order==='id'&&direction==='desc'"></i>,
                </a>

                <a
                    class="mx-1 font-10 my-1"
                    @click="loadAndOrder('title')"
                    href="javascript:void(0)">По заголовку
                    <i class="fa-solid fa-arrow-down-short-wide" v-if="order==='title'&&direction==='asc'"></i>
                    <i class="fa-solid fa-arrow-up-wide-short" v-if="order==='title'&&direction==='desc'"></i>,
                </a>

                <a
                    class="mx-1 font-10 my-1"
                    @click="loadAndOrder('subtitle')"
                    href="javascript:void(0)">По подзаголовку
                    <i class="fa-solid fa-arrow-down-short-wide" v-if="order==='subtitle'&&direction==='asc'"></i>
                    <i class="fa-solid fa-arrow-up-wide-short" v-if="order==='subtitle'&&direction==='desc'"></i>,
                </a>

                <a
                    class="mx-1 font-10 my-1"
                    @click="loadAndOrder('description')"
                    href="javascript:void(0)">По описанию
                    <i class="fa-solid fa-arrow-down-short-wide" v-if="order==='description'&&direction==='asc'"></i>
                    <i class="fa-solid fa-arrow-up-wide-short" v-if="order==='description'&&direction==='desc'"></i>,
                </a>

                <a
                    class="mx-1 font-10 my-1"
                    @click="loadAndOrder('updated_at')"
                    href="javascript:void(0)">По дате добавления
                    <i class="fa-solid fa-arrow-down-short-wide" v-if="order==='updated_at'&&direction==='asc'"></i>
                    <i class="fa-solid fa-arrow-up-wide-short" v-if="order==='updated_at'&&direction==='desc'"></i>
                </a>
            </p>
        </div>
    </div>

    <div data-card-height="140"
         v-if="events.length>0" v-for="(event, index) in events"
         class="card card-style rounded-m shadow-xl bg-18" style="height: 140px;">
        <div class="card-top mt-4 ml-3">
            <h2 class="color-white">{{ event.title || 'Не указано' }}</h2>
            <h6 class="color-white mb-0 font-600 font-10">{{ event.subtitle || 'Не указано' }}</h6>
            <p class="color-white font-10 opacity-70 mt-2 mb-n1"><i class="fa-solid fa-money-bill-wave pr-1"></i> от
                {{ event.min_price || 0 }} ₽</p>
            <p class="color-white font-10 opacity-70"><i class="fa fa-map-marker-alt"></i>
                {{ event.address || 'Адрес не указан' }}</p>

        </div>
        <div class="card-center mr-3">
            <a href="javascript:void(0)"
               @click="selectEvent(event)"
               class="float-right bg-highlight btn btn-xs text-uppercase font-900 rounded-xl font-11">Записаться</a>
        </div>
        <div class="card-overlay bg-black opacity-80"></div>
    </div>


    <div v-else class="card card-style bg-red2-dark rounded-m shadow-xl ">
        <div class="content">
            <h4 class="color-white">Список событий</h4>
            <p class="color-white">
                На текущий момент нет событий в вашем списке. Попробуйте изменить параметры поиска.
            </p>
        </div>
    </div>


    <Pagination
        v-on:pagination_page="nextAppointmentEvents"
        v-if="paginate_object"
        :pagination="paginate_object"/>

</template>
<script>
import {mapGetters} from "vuex";

export default {
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

        nextAppointmentEvents(index) {
            this.loadAppointmentEvents(index)
        },
        selectEvent(event) {
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
