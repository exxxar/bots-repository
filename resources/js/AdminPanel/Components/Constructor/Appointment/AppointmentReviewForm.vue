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
                @click="clearReviewForm"
                class="btn btn-primary">Новый отзыв</button>
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
                        Пользователь {{ selected_bot_user.name || selected_bot_user.fio_from_telegram || selected_bot_user.telegram_chat_id || '-' }}
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

            <div class="col-12 mb-3">
                <p>
                    Рейтинг: {{ reviewForm.rating || 0 }}
                </p>
                <vue3starRatings v-model="reviewForm.rating"/>
            </div>
            <div class="col-12 mb-3">

                <label class="form-label " id="event-on_after_appointment">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Текст, который пользователь оставляет после прохождения услуги
                            </div>
                        </template>
                    </Popper>
                    Текст отзыва
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="reviewForm.text">
                        Длина текста {{ reviewForm.text.length }}/255</small>
                </label>
                <textarea type="text" class="form-control"
                          placeholder="Текст отзыва"
                          aria-label="Текст отзыва"
                          v-model="reviewForm.text"
                          maxlength="255"
                          aria-describedby="review-text" required>
                    </textarea>

            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button
                    type="submit" class="btn btn-outline-success w-100 p-3">
                    <span v-if="reviewForm.id==null">Оставить отзыв</span>
                    <span v-else>Обновить отзыв</span>
                </button>
            </div>
        </div>
    </form>
</template>

<script>
import vue3starRatings from "vue3-star-ratings";

export default {
    props: ["eventId", "bot", "review"],
    components: {
        vue3starRatings
    },
    data() {
        return {
            step: 0,
            load: false,
            selected_schedule_time: null,
            selected_bot_user: null,
            need_load_users: false,
            need_schedule_time: false,
            days: [
                "понедельник",
                "вторник",
                "среду",
                "четверг",
                "пятницу",
                "субботу",
                "воскресенье",
            ],
            reviewForm: {
                id: null,
                appointment_event_id: null,
                appointment_schedule_id: null,
                bot_user_id: null,
                rating: null,
                text: null,

            }
        }
    },
    watch: {
        reviewForm: {
            handler(val) {
                this.need_reset = true
            },
            deep: true
        }
    },
    mounted() {

        if (this.review)
            this.$nextTick(() => {
                this.reviewForm = {
                    id: this.review.id || null,
                    appointment_event_id: this.review.appointment_event_id || null,
                    appointment_schedule_id: this.review.appointment_schedule_id || null,
                    bot_user_id: this.review.bot_user_id || null,
                    rating: this.review.rating || null,
                    text: this.review.text || null,
                }

                this.selected_schedule_time = this.review.schedule || null
                this.selected_bot_user = this.review.botUser || null
            })

    },
    methods: {
        selectScheduleTime(scheduleTime) {
            this.selected_schedule_time = scheduleTime
            this.need_schedule_time = false
        },
        selectBotUser(botUser) {
            this.selected_bot_user = botUser
            this.need_load_users = false
        },
        clearReviewForm(){
            this.reviewForm = {
                id: null,
                appointment_event_id: null,
                appointment_schedule_id: null,
                bot_user_id: null,
                rating: null,
                text: null,
            }

            this.selected_schedule_time =  null
            this.selected_bot_user =  null
        },
        submitForm() {

            this.reviewForm.bot_user_id = this.selected_bot_user.id
            this.reviewForm.appointment_schedule_id = this.selected_schedule_time.id
            this.reviewForm.appointment_event_id = this.eventId

            let data = new FormData();
            Object.keys(this.reviewForm)
                .forEach(key => {
                    const item = this.reviewForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append('bot_id', this.bot.id);

            this.$store.dispatch("storeAppointmentReview", {
                dataObject:{
                    appointmentReviewForm: data
                }

            }).then((response) => {
                this.$emit("callback", response.data)
                this.$notify("Отзыв успешно создан");
                this.clearReviewForm()
            }).catch(err => {
                this.$notify("Ошибка создания отзыва");
            })

        },

    }
}
</script>


