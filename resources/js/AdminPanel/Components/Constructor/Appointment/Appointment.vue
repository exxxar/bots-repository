<script setup>
import AppointmentForm from "@/AdminPanel/Components/Constructor/Appointment/AppointmentForm.vue";
import AppointmentsTable from "@/AdminPanel/Components/Constructor/Appointment/AppointmentsTable.vue";
import AppointmentEventTable from "@/AdminPanel/Components/Constructor/Appointment/AppointmentEventTable.vue";
import AppointmentServicesTable from "@/AdminPanel/Components/Constructor/Appointment/AppointmentServicesTable.vue";
import AppointmentEventForm from "@/AdminPanel/Components/Constructor/Appointment/AppointmentEventForm.vue";
import AppointmentServiceForm from "@/AdminPanel/Components/Constructor/Appointment/AppointmentServiceForm.vue";
import AppointmentScheduleForm from "@/AdminPanel/Components/Constructor/Appointment/AppointmentScheduleForm.vue";
import AppointmentReviewForm from "@/AdminPanel/Components/Constructor/Appointment/AppointmentReviewForm.vue";
import AppointmentReviewsTable from "@/AdminPanel/Components/Constructor/Appointment/AppointmentReviewsTable.vue";
</script>
<template>


    <div v-if="part===0" class="py-2">
        <div class="row">
            <div class="col-12">
                <button type="button"
                        @click="createEvent"
                        class="btn btn-primary">
                    Создать новое событие
                </button>
            </div>
        </div>

        <AppointmentEventTable
            v-if="!loadTable"
            v-on:select="selectEvent"
            :bot="bot"></AppointmentEventTable>
    </div>

    <div v-if="part===2" class="py-2">
        <div class="row">
            <div class="col-12">
                <button type="button"
                        @click="part=0"
                        class="btn btn-outline-primary">
                    Назад
                </button>
            </div>
        </div>

        <AppointmentEventForm
            v-if="!loadForm"
            v-on:callback="callbackForm"
            :bot="bot"/>
    </div>

    <div v-if="part===1" class="py-2">
        <div class="row">
            <div class="col-12">
                <button type="button"
                        @click="part=0"
                        class="btn btn-outline-primary">
                    Назад
                </button>
            </div>
        </div>

        <ul class="nav nav-tabs justify-content-center">
            <li class="nav-item" @click="tab=0">
                <a class="nav-link"
                   v-bind:class="{'active':tab===0}"
                   aria-current="page"
                   href="javascript:void(0)">Информация о событии</a>
            </li>
            <li class="nav-item" @click="tab=1">
                <a class="nav-link"
                   v-bind:class="{'active':tab===1}"
                   href="javascript:void(0)">Сервисы в событии</a>
            </li>
            <li class="nav-item" @click="tab=2">
                <a class="nav-link"
                   v-bind:class="{'active':tab===2}"
                   href="javascript:void(0)">График</a>
            </li>
            <li class="nav-item" @click="tab=3">
                <a class="nav-link"
                   v-bind:class="{'active':tab===3}"
                   href="javascript:void(0)">Записи на событие</a>
            </li>
            <li class="nav-item" @click="tab=4">
                <a class="nav-link"
                   v-bind:class="{'active':tab===4}"
                   href="javascript:void(0)">Отзывы о событии</a>
            </li>

        </ul>

        <div class="row py-3" v-if="tab===0">
            <div class="col-12">
                <AppointmentEventForm
                    v-if="!loadForm"
                    v-on:callback="callbackForm"
                    :event="selectedEvent"
                    :bot="bot"/>
            </div>
        </div>

        <div class="row py-3" v-if="tab===1">

            <div class="col-12">
                <AppointmentServiceForm
                    v-if="!loadService"
                    :service="selectedService"
                    :event-id="selectedEvent.id"
                    :bot="bot">

                </AppointmentServiceForm>
            </div>
            <div class="col-12 py-3">
                <h4>Доступные сервисы в событии</h4>
                <AppointmentServicesTable

                    :event-id="selectedEvent.id"
                    v-on:select="selectService"
                    :bot="bot">

                </AppointmentServicesTable>
            </div>
        </div>

        <div class="row" v-if="tab===2">
            <div class="col-12">
                <AppointmentScheduleForm
                    :event-id="selectedEvent.id"
                    :bot="bot">

                </AppointmentScheduleForm>
            </div>
        </div>

        <div class="row" v-if="tab===3">
            <div class="col-12">
                <AppointmentForm
                    v-if="!loadAppointmentForm"
                    :bot="bot"
                    v-on:callback="callbackAppointment"
                    :event-id="selectedEvent.id"
                    :appointment="selectedAppointment"></AppointmentForm>
            </div>
            <div class="col-12 py-3">
                <h4>Актуальные записи на событие</h4>
                <AppointmentsTable
                    v-if="!loadAppointments"
                    :event-id="selectedEvent.id"
                    v-on:select="selectAppointment"
                    :bot="bot">
                </AppointmentsTable>
            </div>
        </div>


        <div class="row" v-if="tab===4">
            <div class="col-12">
                <AppointmentReviewForm
                    v-if="!loadReviewForm"
                    :review="selectedReview"
                    :bot="bot"
                    v-on:callback="callbackReview()"
                    :event-id="selectedEvent.id">
                </AppointmentReviewForm>
            </div>
            <div class="col-12 py-3">
                <h4>Отзывы к событию</h4>
                <AppointmentReviewsTable
                    :bot="bot"
                    v-if="!loadReviews"
                    v-on:select="selectReview"
                    :event-id="selectedEvent.id">
                </AppointmentReviewsTable>
            </div>
        </div>
    </div>


</template>

<script>
import {mapGetters} from "vuex";

export default {
    props: ["bot"],
    data() {
        return {
            part: 0,
            tab: 0,
            loadTable: false,
            loadService: false,
            loadReviewForm: false,
            loadAppointments: false,
            loadReviews: false,
            loadAppointmentForm: false,
            loadForm: false,
            selectedEvent: null,
            selectedService: null,
            selectedAppointment: null,
            selectedReview: null,
        }
    },

    computed: {
        ...mapGetters(['getAppointmentEvents', 'getAppointmentEventsPaginateObject']),

    },
    mounted() {

    },
    methods: {
        callbackForm() {
            this.loadTable = true
            this.$nextTick(() => {
                this.loadTable = false
            })
        },
        callbackAppointment(){
            this.loadAppointments = true
            this.$nextTick(() => {
                this.loadAppointments = false
            })
        },
        callbackReview(){
            this.loadReviews = true
            this.$nextTick(() => {
                this.loadReviews = false
            })
        },
        createEvent() {
            this.part = 2
        },
        selectAppointment(appointment) {
            this.loadAppointmentForm = true
            this.selectedAppointment = appointment
            this.$nextTick(() => {
                this.loadAppointmentForm = false
            })
        },
        selectService(service) {
            this.loadService = true
            this.selectedService = service
            this.$nextTick(() => {
                this.loadService = false
            })
        },
        selectReview(review) {
            this.loadReviewForm = true
            this.selectedReview = review
            this.$nextTick(() => {
                this.loadReviewForm = false
            })
        },
        selectEvent(event) {
            this.loadForm = true
            this.$nextTick(() => {
                this.loadForm = false
                this.selectedEvent = event
                this.part = 1
                this.tab = 0

            })

        }
    }
}
</script>
