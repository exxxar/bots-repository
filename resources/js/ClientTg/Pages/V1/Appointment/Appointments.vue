<script setup>
import AppointmentEventTable from "@/ClientTg/Components/V1/Appointment/AppointmentEventTable.vue";
import AppointmentForm from "@/ClientTg/Components/V1/Appointment/AppointmentForm.vue";
import AppointmentDetails from "@/ClientTg/Components/V1/Appointment/AppointmentDetails.vue";
</script>
<template>

    <div  v-if="step===0">


    </div>

    <div v-if="step===1">

        <AppointmentEventTable v-on:select="selectEvent"></AppointmentEventTable>

    </div>

    <div v-if="step===2">
        <AppointmentDetails
            v-on:next="nextToForm"
            v-on:back="backToSelectEvent"
            :event="selectedEvent"></AppointmentDetails>
    </div>


    <div  v-if="step===3">
        <AppointmentForm
            v-on:back="backToSelectSchedule"
            :event-id="selectedEvent.id"
            :selected-schedule-time="selectedSchedule"></AppointmentForm>

    </div>

<!--    <CallbackForm/>-->
</template>
<script>


export default {
    name: "App",

    data() {
        return {
            step: 1,
            loading: false,
            selectedEvent:null,
            selectedSchedule:null,
        };
    },
    mounted() {

    },
    methods: {
        nextToForm(schedule){
          this.selectedSchedule = schedule
          this.step = 3
        },
        backToSelectEvent(){
            this.step = 1
            this.selectedEvent = null
        },
        backToSelectSchedule(){
            this.selectedSchedule = null
            this.step = 2
        },
        selectEvent(event){
            this.selectedEvent = event
            this.step = 2
        },

    }
}
;
</script>
<style>


</style>
