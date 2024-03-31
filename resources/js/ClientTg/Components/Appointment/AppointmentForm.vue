<script setup>
import BotMediaList from "@/AdminPanel/Components/Constructor/BotMediaList.vue";
import BotUserList from "@/AdminPanel/Components/Constructor/BotUserList.vue";
import AppointmentScheduleList from "@/AdminPanel/Components/Constructor/Appointment/AppointmentScheduleList.vue";
import AppointmentServiceForm from "@/AdminPanel/Components/Constructor/Appointment/AppointmentServiceForm.vue";
</script>
<template>

    <div class="card card-style">
        <div class="content">

            <button
                @click="backToSelectSchedule"
                class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red2-dark">Назад
            </button>

            <form v-on:submit.prevent="submitForm">
                <div class="mb-3">

                    <label class="form-label d-flex justify-content-between mt-2" id="bot-domain">
                        <div>
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div> ФИО человека, который посетит запись
                                    </div>
                                </template>
                            </Popper>
                            Ф.И.О. посетителя
                        </div>
                        <Popper>
                            <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                            <template #content>
                                <div>Нужно
                                </div>
                            </template>
                        </Popper>

                    </label>

                    <input type="text" class="form-control"
                           placeholder="Иванов Иван Иванович"
                           aria-label="ФИО"
                           v-model="appointmentForm.name"
                           maxlength="255"
                           aria-describedby="appointment-name" required>
                </div>

                <div class="mb-3">

                    <label class="form-label d-flex justify-content-between mt-2" id="bot-domain">
                        <div>
                            <Popper>
                                <i class="fa-regular fa-circle-question mr-1"></i>
                                <template #content>
                                    <div> Номер телефона человека, который посетит запись
                                    </div>
                                </template>
                            </Popper>
                            Телефонный номер посетителя
                        </div>
                        <Popper>
                            <i class="fa-solid font-10 fa-star color-red2-dark"></i>
                            <template #content>
                                <div>Нужно
                                </div>
                            </template>
                        </Popper>

                    </label>


                    <input type="text" class="form-control"
                           placeholder="+7(000)000-00-00"
                           aria-label="номер телефона"
                           v-mask="'+7(###)###-##-##'"
                           v-model="appointmentForm.phone"
                           maxlength="255"
                           aria-describedby="appointment-phone" required>
                </div>

                <div class="mb-3">

                    <label class="form-label mt-2" id="bot-domain">

                            <p class="mb-0 line-height-xs">  Дополнительная информация о вас</p>
                            <p class="mb-0 line-height-xs"> <small class="text-gray-400" style="font-size:10px;" v-if="appointmentForm.info">
                                Длина текста {{ appointmentForm.info.length }} / 255</small></p>




                    </label>


                    <textarea type="text" class="form-control"
                              placeholder="Информация от клиента"
                              aria-label="Информация от клиента"
                              maxlength="255"
                              v-model="appointmentForm.info"
                              aria-describedby="appointment-info">
                    </textarea>

                </div>

                <button
                    type="submit" class="btn btn-m btn-full w-100 rounded-xs text-uppercase font-900 shadow-s bg-green2-dark">
                    <span v-if="appointmentForm.id==null">Записаться</span>
                    <span v-else>Обновить запись</span>
                </button>
            </form>
        </div>
    </div>



</template>

<script>


export default {
    props: ["eventId",  "appointment", "selectedScheduleTime"],
    data() {
        return {
            step: 0,
            load: false,


            days: [
                "понедельник",
                "вторник",
                "среду",
                "четверг",
                "пятницу",
                "субботу",
                "воскресенье",
            ],
            appointmentForm: {
                id: null,
                appointment_event_id: null,
                appointment_schedule_id: null,
                status: 0,
                name: null,
                phone: null,
                info: null,
            }
        }
    },
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
    },
    mounted() {

        if (this.appointment)
            this.$nextTick(() => {
                this.appointmentForm = {
                    id: this.appointment.id || null,
                    appointment_event_id: this.appointment.appointment_event_id || null,
                    appointment_schedule_id: this.appointment.appointment_schedule_id || null,
                    bot_user_id: this.appointment.bot_user_id || null,
                    status: this.appointment.status || 0,
                    name: this.appointment.name || null,
                    phone: this.appointment.phone || null,
                    info: this.appointment.info || null,
                }


            })

    },
    methods: {

        backToSelectSchedule(){
          this.$emit("back")
        },
        selectScheduleTime(scheduleTime) {
            if (scheduleTime.appointment){
                this.$notify("Данное время уже занято!");
                return;
            }

            this.selected_schedule_time = scheduleTime
            this.need_schedule_time = false
        },
        selectBotUser(botUser) {
            this.selected_bot_user = botUser
            this.need_load_users = false
        },
        clearAppointmentForm() {
            this.appointmentForm = {
                id: null,
                appointment_event_id: null,
                appointment_schedule_id: null,
                status: 0,
            }

            this.selected_schedule_time = null
            this.selected_bot_user = null
        },
        submitForm() {

            this.appointmentForm.appointment_schedule_id = this.selectedScheduleTime.id
            this.appointmentForm.appointment_event_id = this.eventId

            let data = new FormData();
            Object.keys(this.appointmentForm)
                .forEach(key => {
                    const item = this.appointmentForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            this.$store.dispatch("storeAppointment", {
                dataObject: {
                    appointmentForm: data
                }

            }).then((response) => {
                this.clearAppointmentForm()
                this.$emit("callback")

                this.$botNotification.success("Отлично!", "Вы успешно записались!")

                setTimeout(()=>{
                    this.tg.close()
                }, 2000)
                //this.$notify("Запись успешно создана");
            }).catch(err => {
                //this.$notify("Ошибка создания записи");

                this.$botNotification.warning("Упс..!", "Кажется что-то пошло не так!")
            })

        },

    }
}
</script>


