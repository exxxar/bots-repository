<script setup>

import Pagination from '@/ClientTg/Components/Pagination.vue';
</script>
<template>

    <div class="card card-style">
        <div class="content">
            <VueDatePicker
                inline
                auto-apply
                locale="ru"
                :enable-time-picker="false"
                v-model="date"></VueDatePicker>

            <p class="font-700 font-12 text-center my-2" v-if="schedule.length>0">Есть <strong class="color-red1-dark">{{schedule.length}} </strong> ячейки для записи</p>
            <p class="font-700 font-12 text-center my-2" v-else>Нет доступных ячеек для записи</p>
            <p style="line-height:100%;">
                <strong class="font-10"> Тип сортировки:</strong>
                <a
                    class="mx-1 font-10 my-1"
                    @click="loadAndOrder('day')"
                    href="javascript:void(0)">По дню недели
                    <i class="fa-solid fa-arrow-down-short-wide" v-if="order==='day'&&direction==='asc'"></i>
                    <i class="fa-solid fa-arrow-up-wide-short" v-if="order==='day'&&direction==='desc'"></i>
                </a>

                <a
                    class="mx-1 font-10 my-1"
                    @click="loadAndOrder('start_time')"
                    href="javascript:void(0)">По времени начала
                    <i class="fa-solid fa-arrow-down-short-wide" v-if="order==='start_time'&&direction==='asc'"></i>
                    <i class="fa-solid fa-arrow-up-wide-short" v-if="order==='start_time'&&direction==='desc'"></i>
                </a>

                <a
                    class="mx-1 font-10 my-1"
                    @click="loadAndOrder('end_time')"
                    href="javascript:void(0)">По времени окончания
                    <i class="fa-solid fa-arrow-down-short-wide" v-if="order==='end_time'&&direction==='asc'"></i>
                    <i class="fa-solid fa-arrow-up-wide-short" v-if="order==='end_time'&&direction==='desc'"></i>
                </a>
            </p>
        </div>
    </div>


    <div class="card card-style border"
         v-if="schedule.length>0"
         v-for="item in schedule">
        <div class="content">


            <table class="table  text-center rounded-sm shadow-l" style="overflow: hidden;">

                <tbody>
                <tr
                    v-bind:class="{'bg-gray2-dark':item.appointment!=null,'bg-highlight':item.appointment==null}"
                    class="color-gray1-dark">
                    <th scope="row">День</th>
                    <td>{{ days[item.day - 1] }}</td>

                </tr>
                <tr
                    v-bind:class="{'bg-gray2-dark':item.appointment!=null,'bg-highlight':item.appointment==null}"
                    class="color-gray1-dark">
                    <th scope="row">Время</th>
                    <td>{{ item.start_time || '-' }} - {{ item.end_time || '-' }}</td>

                </tr>
                <tr
                    v-bind:class="{'bg-gray2-dark':item.appointment!=null,'bg-highlight':item.appointment==null}"
                    class="color-gray1-dark">
                    <th scope="row">Дата</th>
                    <td>{{ item.year || '-' }} {{ months[(item.month || 0)] }} {{  item.day_number }}</td>

                </tr>


                </tbody>
            </table>

            <button
                :disabled="item.appointment!=null"
                @click="selectTime(item)" class="btn btn-m btn-full  rounded-xl text-uppercase font-900 shadow-s bg-red1-light w-100">
               <span v-if="item.appointment!=null">
                   Данное время уже занято
               </span>
                <span v-else>
                   Выбрать время
               </span>
            </button>



        </div>
    </div>

    <div  v-else class="card card-style bg-red2-dark rounded-m shadow-xl ">
        <div class="content">
            <h4 class="color-white">Расписание</h4>
            <p class="color-white">
                В данный временной период нет ни одного доступного времени для записи. Попробуйте выбрать другой временной период. Для выбора временного периода нажми на любой день интересующей вас недели.
            </p>
        </div>
    </div>
    <Pagination
        v-on:pagination_page="nextAppointmentSchedule"
        v-if="paginate_object"
        :pagination="paginate_object"/>

</template>
<script>
import {mapGetters} from "vuex";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
export default {
    props: [ "eventId"],
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
