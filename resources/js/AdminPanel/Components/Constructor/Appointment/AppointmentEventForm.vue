<script setup>
import BotMediaList from "@/AdminPanel/Components/Constructor/BotMediaList.vue";
import AppointmentServiceForm from "@/AdminPanel/Components/Constructor/Appointment/AppointmentServiceForm.vue";
</script>
<template>
    <form v-on:submit.prevent="submitForm">
        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label" id="event-title">
                    <Popper
                        content="Название вашего события (или специалиста), на которое планируется осуществить запись">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Название события

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <input type="text" class="form-control"
                       placeholder="Название"
                       aria-label="Название"
                       v-model="eventForm.title"
                       maxlength="255"
                       aria-describedby="event-title" required>
            </div>

            <div class="col-12 mb-3">

                <label class="form-label"
                       id="event-subtitle">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>Подзаголовок события или доп. инфа под названием события
                            </div>
                        </template>
                    </Popper>
                    Подзаголовок события
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <input type="text" class="form-control"
                       placeholder="Подзаголовок"
                       aria-label="Подзаголовок"
                       v-model="eventForm.subtitle"
                       maxlength="255"
                       aria-describedby="event-subtitle" required>

            </div>

            <div class="col-12 mb-3">

                <label class="form-label " id="event-description">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Краткая информация о происходящем мероприятии
                            </div>
                        </template>
                    </Popper>
                    Описание события
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="eventForm.description">
                        Длина текста {{ eventForm.description.length }}</small>
                </label>
                <textarea type="text" class="form-control"
                          placeholder="Описание мероприятия"
                          aria-label="Описание мероприятия"
                          v-model="eventForm.description"
                          aria-describedby="event-description" required>
                    </textarea>

            </div>

            <div class="col-12 mb-3">

                <label class="form-label " id="event-on_start_appointment">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Текст, который будет отображен клиенту, когда тот записался
                            </div>
                        </template>
                    </Popper>
                    Текст при записи на событие
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="eventForm.on_start_appointment">
                        Длина текста {{ eventForm.on_start_appointment.length }}</small>
                </label>
                <textarea type="text" class="form-control"
                          placeholder="Текст при записи на событие"
                          aria-label="Текст при записи на событие"
                          v-model="eventForm.on_start_appointment"
                          aria-describedby="event-on_start_appointment" required>
                    </textarea>

            </div>

            <div class="col-12 mb-3">

                <label class="form-label " id="event-on_cancel_appointment">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Текст, который будет отображен клиенту, когда тот отменил запись
                            </div>
                        </template>
                    </Popper>
                    Текст при отмене записи на событие
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="eventForm.on_cancel_appointment">
                        Длина текста {{ eventForm.on_cancel_appointment.length }}</small>
                </label>
                <textarea type="text" class="form-control"
                          placeholder="Текст при отмене записи на событие"
                          aria-label="Текст при отмене записи на событие"
                          v-model="eventForm.on_cancel_appointment"
                          aria-describedby="event-on_cancel_appointment" required>
                    </textarea>

            </div>

            <div class="col-12 mb-3">

                <label class="form-label " id="event-on_after_appointment">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Текст, который будет отображен клиенту, когда тот прошел событие успешно
                            </div>
                        </template>
                    </Popper>
                    Текст после прохождения события
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="eventForm.on_after_appointment">
                        Длина текста {{ eventForm.on_after_appointment.length }}</small>
                </label>
                <textarea type="text" class="form-control"
                          placeholder="Текст после прохождения события"
                          aria-label="Текст после прохождения события"
                          v-model="eventForm.on_after_appointment"
                          aria-describedby="event-on_after_appointment" required>
                    </textarea>

            </div>

            <div class="col-12 mb-3">

                <label class="form-label " id="event-on_repeat_appointment">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Текст, который будет отображен клиенту, когда тот записывается 2й и более раз
                            </div>
                        </template>
                    </Popper>
                    Текст при повторной записи на событие
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="eventForm.on_repeat_appointment">
                        Длина текста {{ eventForm.on_repeat_appointment.length }}</small>
                </label>
                <textarea type="text" class="form-control"
                          placeholder=" Текст при повторной записи на событие"
                          aria-label=" Текст при повторной записи на событие"
                          v-model="eventForm.on_repeat_appointment"
                          aria-describedby="event-on_repeat_appointment" required>
                    </textarea>

            </div>

            <div class="col-12 mb-3">
                <label class="form-label" id="event-images">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Изображение, которое видит пользователь при выборе события
                            </div>
                        </template>
                    </Popper>
                   Изображение к событию
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <BotMediaList
                    :need-photo="true"
                    :selected="eventForm.images"
                    v-on:select="selectPhotos"></BotMediaList>
            </div>

            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="eventForm.is_group"
                           type="checkbox" id="is-group">
                    <label class="form-check-label" for="is-group">
                        Является групповым мероприятием
                    </label>
                </div>
            </div>

            <div
                v-if="eventForm.is_group"
                class="col-md-6 col-12 mb-3">
                <label class="form-label" id="event-min_people">
                    <Popper
                        content="Минимально необходимое число людей, для начала 1 мероприятия">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                    Минимальное число посетителей в группе

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <input type="number" class="form-control"
                       placeholder="Минимальное число посетителей"
                       aria-label="Название"
                       v-model="eventForm.min_people"

                       aria-describedby="event-min_people" required>
            </div>

            <div
                v-if="eventForm.is_group"
                class="col-md-6 col-12 mb-3">
                <label class="form-label" id="event-max_people">
                    <Popper
                        content="Количество людей, которые могут посетить мероприятие за 1 сеанс">
                        <i class="fa-regular fa-circle-question mr-1"></i>
                    </Popper>
                 Максимальное число посетителей в группе

                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>


                </label>
                <input type="number" class="form-control"
                       placeholder="Максимальное число посетителей"
                       aria-label="Название"
                       v-model="eventForm.max_people"

                       aria-describedby="event-max_people" required>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <div class="form-check">
                    <input class="form-check-input"
                           v-model="need_services"
                           type="checkbox" id="need-services">
                    <label class="form-check-label" for="need-services">
                        Нужно добавить сервисы
                    </label>
                </div>
            </div>
            <div class="col-12 mb-3" v-if="need_services">
                <AppointmentServiceForm
                    :bot="bot"/>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button
                    type="submit" class="btn btn-outline-success w-100 p-3">
                    <span v-if="eventForm.id==null">Создать событие</span>
                    <span v-else>Обновить событие</span>
                </button>
            </div>
        </div>
    </form>
</template>

<script>
export default {
    props: ["event", "bot"],
    data() {
        return {
            step: 0,
            load: false,
            need_reset: false,
            need_services:false,
            eventForm: {
                id:null,
                title: null,
                subtitle: null,
                description: null,
                images: [],
                services: [],
                times: [],
                is_group: false,
                max_people: 0,
                min_people: 0,
                on_start_appointment: null,
                on_cancel_appointment: null,
                on_after_appointment: null,
                on_repeat_appointment: null,
            }
        }
    },
    watch: {
        eventForm: {
            handler(val) {
                this.need_reset = true
            },
            deep: true
        }
    },
    mounted() {

        if (this.event)
            this.$nextTick(() => {
                this.eventForm = {
                    id: this.event.id || null,
                    title: this.event.title || null,
                    subtitle: this.event.subtitle || null,
                    description: this.event.description || null,
                    images: this.event.images || [],
                    is_group: this.event.is_group || false,
                    max_people: this.event.max_people || 0,
                    min_people: this.event.min_people || 0,
                    on_start_appointment: this.event.on_start_appointment || null,
                    on_cancel_appointment: this.event.on_cancel_appointment || null,
                    on_after_appointment: this.event.on_after_appointment || null,
                    on_repeat_appointment: this.event.on_repeat_appointment || null,
                }

            })

    },
    methods: {
        selectPhotos(item){
            if (!this.eventForm.images)
                this.eventForm.images = []

            let index = this.eventForm.images.indexOf(item.file_id)

            if (index !== -1)
                this.eventForm.images.splice(index, 1)
            else
                this.eventForm.images.push(item.file_id)
        },
        resetForm() {

        },

        submitForm() {
            let data = new FormData();
            Object.keys(this.eventForm)
                .forEach(key => {
                    const item = this.eventForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append('bot_id', this.bot.id);

            this.$store.dispatch(this.eventForm.id === null ?
                    "addAppointmentEvent" :
                    "updateAppointmentEvent",
                {
                    appointmentEventForm: data
                }).then((response) => {
                this.$emit("callback", response.data)

                this.$notify("Событие успешно создано");
            }).catch(err => {
                this.$notify("Ошибка создания события");
            })

        },

    }
}
</script>


