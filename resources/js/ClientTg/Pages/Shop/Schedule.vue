<script setup>
import CallbackForm from "ClientTg@/Components/Shop/CallbackForm.vue";
import ReturnToBot from "ClientTg@/Components/Shop/Helpers/ReturnToBot.vue";
import PlayerForm from "ClientTg@/Components/Shop/PlayerForm.vue";
</script>

<template>



    <div class="card card-style">
        <div
             class="caption shadow-xl "
             v-bind:class="{'bg-red2-dark is-business-closed':!is_work,'bg-green1-dark is-business-opened':is_work}"
             style="height: 120px;">



            <div class="card-top">

                <a id="business-hours-mail" href="#goto" class="float-right btn btn-xs rounded-xl text-uppercase font-900 mt-5 mr-3 bg-white color-black">Написать нам</a>
            </div>
            <div class="card-top mt-4 ml-3 pl-1" v-if="!is_work">
                <h1 class="color-white mt-1 font-20 font-700">Извините! мы закрыты!</h1>
                <p class="color-white opacity-90 mt-n2 mb-0">{{closed_comment||'не указано'}}</p>
            </div>
            <div class="card-top mt-4 ml-3 pl-1" v-if="is_work">
                <h1 class="color-white mt-1 font-20 font-700">Мы открыты!</h1>
                <p class="color-white opacity-90 mt-n2 mb-0">{{opened_comment||'не указано'}}</p>
            </div>
            <div class="caption-overlay show-business-opened bg-green1-dark " v-bind:class="{'disabled':!is_work}"></div>
            <div class="caption-overlay show-business-closed bg-red2-dark " v-bind:class="{'disabled':is_work}"></div>
        </div >
        <div class="content">
            <div class="working-hours">
                <p><strong>День недели</strong></p>
                <p><strong>Начало работы</strong></p>
                <p><strong>Конец работы</strong></p>
            </div>

            <div class="working-hours"
                 v-bind:class="{'bg-green1-dark':current_day==index&&is_work,'bg-red2-dark':current_day==index&&!is_work}"
                 v-for="(day, index) in schedule">
                <div v-if="day.closed">
                    <p  class="text-center" v-bind:class="{'text-white':current_day==index}">{{day.day}}</p>
                    <p  class="text-center opacity-00"  v-bind:class="{'text-white':current_day==index}">-</p>
                    <p  class="text-center"  v-bind:class="{'text-white':current_day==index}">Не работает</p>

                </div>
                <div v-else>
                    <p  class="text-center" v-bind:class="{'text-white':current_day==index}">{{day.day}}</p>
                    <p class="text-center" v-bind:class="{'text-white':current_day==index}">{{day.start_at}}</p>
                    <p class="text-center" v-bind:class="{'text-white':current_day==index}">{{day.end_at}}</p>
                </div>

            </div>


        </div>
    </div>


    <div class="show-business-opened mb-4 " v-bind:class="{'disabled':!is_work}">
        <div class="ml-3 mr-3 alert alert-small rounded-s shadow-xl bg-green1-dark" role="alert">
            <span><i class="fa fa-check"></i></span>
            <strong>{{opened_comment||'Не указано'}}</strong>
            <button type="button" class="close color-white opacity-60 font-16" data-dismiss="alert" aria-label="Close">×</button>
        </div>
    </div>

    <div class="show-business-closed mb-4 " v-bind:class="{'disabled':is_work}">
        <div class="ml-3 mr-3 alert alert-small rounded-s shadow-xl bg-red2-dark" role="alert">
            <span><i class="fa fa-times-circle"></i></span>
            <strong> {{closed_comment||'Не указано'}}</strong>
            <button type="button" class="close color-white opacity-60 font-16" data-dismiss="alert" aria-label="Close">×</button>
        </div>
    </div>
    <div class="divider divider-margins"></div>

    <CallbackForm id="goto" name="goto"/>

</template>
<script>

export default {
    name: "App",
    data() {
        return {
            current_day: 1,
            schedule: null,
            is_work:false,
            opened_comment:null,
           closed_comment :null,
        };
    },

    mounted() {
        this.loadServiceData().then(() => {

        })


    }
    ,
    methods: {
        loadServiceData() {
            return this.$store.dispatch("scheduleLoadData").then((response) => {
                this.schedule = response.schedule
                this.current_day = response.current_day
                this.is_work = response.is_work
                this.opened_comment = response.opened_comment
                this.closed_comment = response.closed_comment

                console.log(this.schedule)
            })
        },
    },
}

</script>
