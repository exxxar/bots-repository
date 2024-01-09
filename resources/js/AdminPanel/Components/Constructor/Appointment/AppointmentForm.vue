<script setup>
import BotMediaList from "@/AdminPanel/Components/Constructor/BotMediaList.vue";
import BotUserList from "@/AdminPanel/Components/Constructor/BotUserList.vue";
import AppointmentScheduleList from "@/AdminPanel/Components/Constructor/Appointment/AppointmentScheduleList.vue";
import AppointmentServiceForm from "@/AdminPanel/Components/Constructor/Appointment/AppointmentServiceForm.vue";
</script>
<template>
    <div class="row py-3">
        <div class="col-12">
            <button
                @click="clearAppointmentForm"
                class="btn btn-primary">Новая заявка
            </button>
        </div>
    </div>
    <form v-on:submit.prevent="submitForm">


        <div class="row">
            <div class="col-12">
                <a href="javascript:void(0)"
                   class="btn btn-link"
                   @click="need_load_users=!need_load_users">
                    <span v-if="selected_bot_user==null">Выберите пользователя</span>
                    <span v-if="selected_bot_user">
                        Пользователь {{
                            selected_bot_user.name || selected_bot_user.fio_from_telegram || selected_bot_user.telegram_chat_id || '-'
                        }}
                    </span>
                </a>
            </div>
            <div class="col-12 mb-3" v-if="need_load_users">
                <BotUserList
                    :simple="true"
                    v-on:select="selectBotUser"
                ></BotUserList>
            </div>

            <div class="col-12 mb-3">
                <a href="javascript:void(0)"
                   class="btn btn-link"
                   @click="need_schedule_time=!need_schedule_time">
                    <span v-if="selected_schedule_time==null">Выберите время в расписании</span>
                    <span v-if="selected_schedule_time">
                        Запись на
                        {{ days[selected_schedule_time.day - 1] || '-' }}
                        с {{ selected_schedule_time.start_time || '-' }}
                        до {{ selected_schedule_time.end_time || '-' }}
                    </span>
                </a>
            </div>
            <div class="col-12 mb-3" v-if="need_schedule_time">
                <AppointmentScheduleList
                    :event-id="eventId"
                    :bot="bot"
                    v-on:select="selectScheduleTime"
                ></AppointmentScheduleList>
            </div>

        </div>

        <div class="row py-3">
            <div class="col-12">
                <p class="mb-0">Выбор статуса</p>
            </div>
            <div class="col-md-3"  v-for="status in statuses">
                <button type="button"
                        v-bind:class="{'btn-success text-white':appointmentForm.status == status.value}"
                        class="btn btn-outline-success w-100"
                        @click="appointmentForm.status = status.value"
                       >
                    {{status.title || 'не указано'}}
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label" id="service-title">
                    <Popper
                        content="ФИО человека, который посетит запись">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Ф.И.О. посетителя

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <input type="text" class="form-control"
                       placeholder="Иванов Иван Иванович"
                       aria-label="ФИО"
                       v-model="appointmentForm.name"
                       maxlength="255"
                       aria-describedby="appointment-name" required>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label" id="service-title">
                    <Popper
                        content="Номер телефона человека, который посетит запись">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Телефонный номер

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <input type="text" class="form-control"
                       placeholder="+7(000)000-00-00"
                       aria-label="номер телефона"
                       v-mask="'+7(###)###-##-##'"
                       v-model="appointmentForm.phone"
                       maxlength="255"
                       aria-describedby="appointment-phone" required>
            </div>

            <div class="col-12 mb-3">

                <label class="form-label " id="appointment-info">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Краткая информация от клиента
                            </div>
                        </template>
                    </Popper>
                    Описание услуги
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="appointmentForm.info">
                        Длина текста {{ appointmentForm.info.length }} / 255</small>
                </label>
                <textarea type="text" class="form-control"
                          placeholder="Информация от клиента"
                          aria-label="Информация от клиента"
                          maxlength="255"
                          v-model="appointmentForm.info"
                          aria-describedby="appointment-info" required>
                    </textarea>

            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button
                    type="submit" class="btn btn-outline-success w-100 p-3">
                    <span v-if="appointmentForm.id==null">Записаться</span>
                    <span v-else>Обновить запись</span>
                </button>
            </div>
        </div>
    </form>
</template>

<script>


export default {
    props: ["eventId", "bot", "appointment"],
    data() {
        return {
            step: 0,
            load: false,
            selected_schedule_time: null,
            selected_bot_user: null,
            need_load_users: false,
            need_schedule_time: false,
            statuses:[
                {
                    value:0,
                    title:"Новая запись"
                },
                {
                    value:1,
                    title:"Подтверждение записи"
                },
                {
                    value:2,
                    title:"Прохождение по записи"
                },
                {
                    value:3,
                    title:"Отмена записи"
                }

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
            appointmentForm: {
                id: null,
                appointment_event_id: null,
                appointment_schedule_id: null,
                bot_user_id: null,
                status: 0,
                name: null,
                phone: null,
                info: null,
            }
        }
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

                this.selected_schedule_time = this.appointment.schedule || null
                this.selected_bot_user = this.appointment.botUser || null
            })

    },
    methods: {
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
                bot_user_id: null,
                status: 0,
            }

            this.selected_schedule_time = null
            this.selected_bot_user = null
        },
        submitForm() {

            this.appointmentForm.bot_user_id = this.selected_bot_user.id
            this.appointmentForm.appointment_schedule_id = this.selected_schedule_time.id
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

            data.append('bot_id', this.bot.id);

            this.$store.dispatch("storeAppointment", {
                dataObject: {
                    appointmentForm: data
                }

            }).then((response) => {
                this.clearAppointmentForm()
                this.$emit("callback")
                this.$notify("Запись успешно создана");
            }).catch(err => {
                this.$notify("Ошибка создания записи");
            })

        },

    }
}
</script>


