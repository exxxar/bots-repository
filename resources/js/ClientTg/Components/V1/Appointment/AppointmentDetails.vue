<script setup>

import AppointmentScheduleList from "@/ClientTg/Components/V1/Appointment/AppointmentScheduleList.vue";

</script>
<template>

    <div class="card card-style">
        <div class="content">
            <button
                @click="back"
                class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red2-dark">Назад
            </button>

            <img v-lazy="'/file-by-file-id/'+event.images[0]"
                 v-if="event.images.length>0"
                 class="card-img-top rounded-m p-2" alt="...">

            <div class="divider mb-3 mt-1"></div>
            <div class="p-3" v-if="event.images.length>1">
                <div class="row text-center row-cols-3 mb-n4">
                    <a class="col mb-4 default-link"
                       v-for="img in event.images"
                       data-lightbox="gallery-1" :href="'/file-by-file-id/'+img">
                        <img class="img-fluid rounded-xs preload-img" alt="img"
                             v-lazy="'/file-by-file-id/'+img">
                    </a>

                </div>
            </div>
            <div class="divider mb-3 mt-1" v-if="event.images.length>1"></div>

            <h2 class="font-700  mb-2">{{ event.title || 'Не указано' }}</h2>
            <h6 class="font-700  mb-2 font-12">{{ event.subtitle || 'Не указано' }}</h6>
            <p class="mt-n1 mb-3">
                <span class="d-block font-700 font-12">Описание услуги</span>
                <em>{{ event.description || 'Не указано' }}</em>
            </p>
            <p class="font-10 opacity-70 mb-2"><i class="fa fa-map-marker-alt"></i>
                {{ event.address || 'Адрес не указан' }}</p>

            <p class="mb-2">
                <span class="d-block font-700 font-12">Сервисы</span>
            </p>

            <div class="list-group"
                 v-if="event.services.length>0">

                <div

                    class="border border-gray1-dark rounded-s shadow-xs p-3 d-flex align-items-center mb-2"
                    v-for="(service, index) in event.services">
                    <i class="fa-solid fa-clock-rotate-left font-20 color-blue2-dark"    @click="toggleService(index)"></i>
                    <div class="ml-2 d-flex flex-column">
                        <span class="mb-0 line-height-xs font-700"  @click="toggleService(index)">{{ service.title || 'Не указано' }}</span>
                        <em class="mb-0 line-height-xs font-10" @click="toggleService(index)">{{ service.description || 'Не указано ' }}</em>

                        <div v-if="service.show_full">
                            <a href="#" class="chip chip-small bg-dark2-dark">
                                <i class="fa-solid fa-layer-group bg-green1-dark"></i>
                                <span class="color-white">{{service.category || 'Не указано'}}</span>
                            </a>

                            <a href="#" class="chip chip-small bg-gray2-dark" v-if="service.need_prepayment">
                                <i class="fa-solid fa-hand-holding-dollar bg-green1-dark"></i>
                                <span class="color-white">Нужна предоплата</span>
                            </a>

                            <p class="font-12">
                                Цена услуги
                                <span v-if="(service.discount_price||0)===0" class="font-700">{{service.price}} ₽</span>
                                <span class="font-700" v-else>{{service.discount_price}} ₽ </span>
                            </p>


                            <div class="p-3" v-if="service.images.length>0">
                                <div class="row text-center row-cols-3 mb-n4">
                                    <a class="col mb-4 default-link"
                                       v-for="img in service.images"
                                       data-lightbox="gallery-1" :href="'/file-by-file-id/'+img">
                                        <img class="img-fluid rounded-xs preload-img" alt="img"
                                             v-lazy="'/file-by-file-id/'+img">
                                    </a>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
        </div>


    </div>


    <AppointmentScheduleList
        v-if="event"
        v-on:select="selectSchedule"
        :event-id="event.id"></AppointmentScheduleList>
</template>
<script>
export default {
    props: ["event"],
    data() {
        return {
            selectedSchedule: null,
        }
    },
    methods: {
        back() {
            this.$emit("back")
        },
        toggleService(index) {
            this.event.services[index].show_full = !(this.event.services[index].show_full || false)
        },
        selectSchedule(schedule) {
            this.selectedSchedule = schedule
            this.$emit("next", schedule)
            this.$botNotification.success("Расписание", "Отлично! Дата выбрана, остался последний шаг")
        }
    }
}
</script>
