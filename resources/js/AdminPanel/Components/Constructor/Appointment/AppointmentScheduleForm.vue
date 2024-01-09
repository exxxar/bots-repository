<script setup>

import Pagination from '@/AdminPanel/Components/Pagination.vue';


</script>
<template>
    <div class="row pt-3">
        <div class="col-md-4">
            <div class="form-floating mb-3">
                <input type="number" class="form-control"
                       v-model="step"
                       min="0"
                       id="config-step" placeholder="30 минут">
                <label for="config-step">Шаг, минуты</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating mb-3">
                <input type="number"
                       v-model="start"
                       min="0"
                       max="23"
                       class="form-control" id="config-start-time"
                       placeholder="Часы">
                <label for="config-start-time">Начало, часы</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating mb-3">
                <input type="number"
                       v-model="end"
                       :min="start"
                       max="23"
                       class="form-control" id="config-end-time" placeholder="Часы">
                <label for="config-end-time">Конец, часы</label>
            </div>
        </div>
    </div>
    <hr>
    <div class="row" v-if="schedule.length>0||added_schedule.length>0" >
        <div class="col-12 d-flex justify-content-end pb-3">
            <button
                @click="storeSchedule"
                class="btn btn-primary">Сохранить текущий график
            </button>
        </div>
        <div class="row">
            <div class="col-md-4 text-center"><a
                @click="loadAndOrder('day')"
                href="javascript:void(0)">День
                <i class="fa-solid fa-arrow-down-short-wide" v-if="order==='day'&&direction==='asc'"></i>
                <i class="fa-solid fa-arrow-up-wide-short" v-if="order==='day'&&direction==='desc'"></i>
            </a></div>
            <div class="col-md-4 text-center"><a
                @click="loadAndOrder('start_time')"
                href="javascript:void(0)">Начало
                <i class="fa-solid fa-arrow-down-short-wide" v-if="order==='start_time'&&direction==='asc'"></i>
                <i class="fa-solid fa-arrow-up-wide-short" v-if="order==='start_time'&&direction==='desc'"></i>
            </a></div>
            <div class="col-md-4 text-center"><a
                @click="loadAndOrder('end_time')"
                href="javascript:void(0)">Конец
                <i class="fa-solid fa-arrow-down-short-wide" v-if="order==='end_time'&&direction==='asc'"></i>
                <i class="fa-solid fa-arrow-up-wide-short" v-if="order==='end_time'&&direction==='desc'"></i>
            </a></div>
        </div>
    </div>
    <div class="row py-2"
         v-bind:class="{'bg-warning':row.id==null}"
         v-for="(row, index) in [...schedule,...added_schedule]">
        <div class="col-md-4">

            <div class="form-floating">
                <input type="text"
                       v-model="days[row.day-1]"
                       class="form-control" :id="'day-of-week-'+index" placeholder="День недели">
                <label :for="'day-of-week-'+index">День недели</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="text"
                       v-model="row.start_time"
                       class="form-control" :id="'day-of-start-at-'+index" placeholder="Время начало">
                <label :for="'day-of-start-at-'+index">Начало</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating">
                <input type="text"
                       v-model="row.end_time"
                       class="form-control" :id="'day-of-end-at-'+index" placeholder="Время окончания">
                <label :for="'day-of-end-at-'+index">Окончание</label>
            </div>
        </div>

    </div>
    <div class="row mt-3">
        <div class="col-12">
            <Pagination
                v-on:pagination_page="nextAppointmentSchedule"
                v-if="paginate_object"
                :pagination="paginate_object"/>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button class="btn btn-primary" @click="schedule = []">Очистить таблицу</button>
        </div>
        <div class="col-12">
            <p class="text-center">
                Год: <strong>{{currentYear}}</strong>
                Месяц:  <strong>{{months[currentMonth]}}</strong>
                Неделя:  <strong>{{currentWeek}}</strong>
            </p>


        </div>
    </div>

    <div class="row d-flex justify-content-center">
        <div class="col-md-4">
            <VueDatePicker v-model="date"></VueDatePicker>
        </div>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col" v-for="day in days">{{ day }}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(item, index) in times"
            v-bind:class="{'table-light':inScheduleRow(index)}"
        >
            <th scope="row">{{ item }}</th>
            <td v-for="dayIndex in 7">
                <button
                    v-bind:class="{'btn-primary text-white':inSchedule(index,dayIndex)}"
                    @click="setScheduleItem(index,dayIndex)"
                    class="btn btn-outline-primary">
                    <i class="fa-solid fa-check" v-if="inSchedule(index,dayIndex)"></i>
                    <i class="fa-solid fa-xmark" v-else></i>

                </button>
            </td>

        </tr>

        </tbody>
    </table>
</template>

<script>
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import {mapGetters} from "vuex";

export default {
    components: { VueDatePicker },
    props: ["eventId", "bot"],
    data() {
        return {
            date:null,
            load: false,
            step: 30,
            start: 8,
            end: 18,
            order:null,
            direction:null,
            paginate_object: null,
            years:[
                2024, 2025
            ],
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
            schedule: [],
            added_schedule:[],
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
        currentWeek(){
            return (new Date()).getWeek();
        },
        currentMonthWeek(){
          return (new Date()).getMonthWeek()
        },
        currentMonth(){
            return (new Date()).getMonth();
        },
        currentYear(){
            return (new Date()).getFullYear();
        },
        times() {
            let x = this.step; //minutes interval
            let times = []; // time array
            let tt = 0; // start time
            let index = 0;
            for (let i = 0; tt < 24 * 60; i++) {
                let hh = Math.floor(tt / 60); // getting hours of day in 0-24 format
                if (hh >= this.start && hh <= this.end) {
                    let mm = (tt % 60); // getting minutes of the hour in 0-55 format
                    times[index] = ("0" + (hh)).slice(-2) + ':' + ("0" + mm).slice(-2); // pushing data in array in [00:00 - 12:00 AM/PM format]
                    index++;
                }
                tt = tt + x;
            }
            return times
        }
    },

    mounted() {
        this.date = new Date();
        this.loadAppointmentSchedules()
    },
    methods: {
        removeAppointmentSchedule(id){
            this.loading = true
            this.$store.dispatch("removeAppointmentSchedule", {
                dataObject: {
                    appointmentScheduleId: id,
                },
            }).then(resp => {
                this.loading = false
                this.loadAppointmentSchedules(0)
                this.$notify("Время успешно удалено");
            }).catch(() => {
                this.loading = false
                this.$notify("Ошибка удаления времени")
            })
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
                page:page,
                size:20,
            }).then(resp => {
                this.loading = false
                this.schedule = this.getAppointmentSchedules
                this.paginate_object = this.getAppointmentSchedulesPaginateObject
            }).catch(() => {
                this.loading = false
            })
        },
        storeSchedule() {
            this.$store.dispatch("storeAppointmentSchedule", {
                dataObject: {
                    schedule: [...this.schedule,...this.added_schedule],
                    bot_id: this.bot.id,
                    appointment_event_id: this.eventId
                }
            }).then(() => {
                this.$notify("Расписание успешно сохранено")
                this.loadAppointmentSchedules()
                this.added_schedule = []
            }).catch(()=>{
                this.$notify("Ошибка сохраения расписания")
            })
        },
        inScheduleRow(timeIndex) {
            return (this.schedule.findIndex(item => item.start_time == this.times[timeIndex]) != -1) ||
                (this.added_schedule.findIndex(item => item.start_time == this.times[timeIndex]) != -1)
        },
        inSchedule(timeIndex, dayIndex) {
            return (this.schedule.findIndex(item => item.day == dayIndex && item.start_time == this.times[timeIndex]) != -1) ||
                this.added_schedule.findIndex(item => item.day == dayIndex && item.start_time == this.times[timeIndex]) != -1
        },
        setScheduleItem(timeIndex, dayIndex) {
            let index = this.schedule.findIndex(item => item.day == dayIndex && item.start_time == this.times[timeIndex])

            if (index !== -1) {
                this.schedule.splice(index, 1)
                return;
            }

            index = this.added_schedule.findIndex(item => item.day == dayIndex && item.start_time == this.times[timeIndex])

            if (index !== -1) {
                this.added_schedule.splice(index, 1)
                return;
            }

            /* if (this.times.length === timeIndex + 1)
                 this.end++;*/

            this.added_schedule.push({
                day: dayIndex,
                start_time: this.times[timeIndex] || null,
                end_time: this.times[timeIndex + 1] || null,
                week: this.date == null? this.currentWeek  :   (new Date(this.date)).getWeek(),
                month: this.date == null? this.currentMonth  :   (new Date(this.date)).getMonth(),
                year: this.date == null? this.currentYear  :   (new Date(this.date)).getFullYear(),
            })
        }
    }
}
</script>


