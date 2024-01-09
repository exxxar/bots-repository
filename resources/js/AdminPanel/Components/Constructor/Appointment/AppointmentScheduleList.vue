<script setup>

import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>


    <div class="row">
        <div class="col-md-4">
            <VueDatePicker v-model="date"></VueDatePicker>
        </div>
        <div class="col-12">
            <p>
                Тип сортировки:

                <a
                    class="mx-2"
                    @click="loadAndOrder('day')"
                    href="javascript:void(0)">По дню недели
                    <i class="fa-solid fa-arrow-down-short-wide" v-if="order==='day'&&direction==='asc'"></i>
                    <i class="fa-solid fa-arrow-up-wide-short" v-if="order==='day'&&direction==='desc'"></i>
                </a>

                <a
                    class="mx-2"
                    @click="loadAndOrder('start_time')"
                    href="javascript:void(0)">По времени начала
                    <i class="fa-solid fa-arrow-down-short-wide" v-if="order==='start_time'&&direction==='asc'"></i>
                    <i class="fa-solid fa-arrow-up-wide-short" v-if="order==='start_time'&&direction==='desc'"></i>
                </a>

                <a
                    class="mx-2"
                    @click="loadAndOrder('end_time')"
                    href="javascript:void(0)">По времени окончания
                    <i class="fa-solid fa-arrow-down-short-wide" v-if="order==='end_time'&&direction==='asc'"></i>
                    <i class="fa-solid fa-arrow-up-wide-short" v-if="order==='end_time'&&direction==='desc'"></i>
                </a>
            </p>

        </div>


        <div class="col-md-4 mb-2" v-for="item in schedule">
            <div class="card btn "
                 v-bind:class="{'btn-outline-secondary bg-secondary':item.appointment!=null,'btn-outline-success':item.appointment==null}"
                 @click="selectTime(item)">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-0"><strong>{{ days[item.day - 1] }}</strong></p>
                            <p class="mb-0">Начало сеанса</p>
                            <p class="mb-0"><strong>{{ item.start_time || '-' }}</strong></p>
                            <p class="mb-0">Окончание сеанса</p>
                            <p class="mb-0"><strong>{{ item.end_time || '-' }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0"><strong>{{ item.year || '-' }}</strong></p>
                            <p class="mb-0"><strong>{{ months[(item.month || 0)] }}</strong></p>
                            <p class="mb-0"><strong>{{  item.day_number }}</strong></p>
                            <p class="mb-0"><strong>Период</strong></p>
                            <p class="mb-0"><strong>c {{ item.week_start || '-' }}</strong> по <strong>{{ item.week_end || '-' }}</strong></p>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <Pagination
                v-on:pagination_page="nextAppointmentSchedule"
                v-if="paginate_object"
                :pagination="paginate_object"/>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
export default {
    props: ["bot", "eventId"],
    components:{
        VueDatePicker
    },
    data() {
        return {
            date:null,
            loading: true,
            schedule: [],
            order: null,
            direction: null,
            months:[
                'Январь',
                'Февраль',
                'Март',
                'Апрель',
                'Май',
                'Июнь',
                'Июль',
                'Август',
                'Сентябрь',
                'октябрь',
                'Ноябрь',
                'Декабрь',
            ],
            days: [
                "Понедельник",
                "Вторник",
                "Среда",
                "Четверг",
                "Пятница",
                "Суббота",
                "Воскресенье",
            ],
            paginate_object: null,
        }
    },
    watch:{
        date: {
            handler: function(newValue) {
                this.loadAppointmentSchedules()
            },
            deep: true
        }
    },
    computed: {
        ...mapGetters(['getAppointmentSchedules', 'getAppointmentSchedulesPaginateObject']),

    },
    mounted() {
        this.date = new Date();
        this.loadAppointmentSchedules()
    },
    methods: {
        selectTime(time) {

            this.$emit("select", time)
        },
        loadAndOrder(order) {
            this.order = order
            this.direction = this.direction === 'desc' ? 'asc' : 'desc'
            this.loadAppointmentSchedules(0)
        },
        nextAppointmentSchedule(index) {
            this.loadAppointmentSchedules(index)
        },

        loadAppointmentSchedules(page = 0) {
            this.loading = true
            this.$store.dispatch("loadAppointmentSchedules", {
                dataObject: {
                    date: this.date || null,
                    event_id: this.eventId,
                    bot_id: this.bot.id || null,
                    order: this.order,
                    direction: this.direction
                },
                page: page,
                size: 20,
            }).then(resp => {
                this.loading = false
                this.schedule = this.getAppointmentSchedules
                this.paginate_object = this.getAppointmentSchedulesPaginateObject
            }).catch(() => {
                this.loading = false
            })
        },
    }
}
</script>
