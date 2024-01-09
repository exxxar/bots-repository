<script setup>

import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>

    <div class="row">
        <div class="col-12">
            <table class="table" v-if="reviews.length>0">
                <thead>


                <tr>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('id')">#</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('bot_user_id')">Пользователь</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('rating')">Рейтинг</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('text')">Текст</th>
                    <th scope="col" class="cursor-pointer" @click="loadAndOrder('updated_at')">Дата изменения</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(review, index) in reviews"
                    @click="selectReview(review)"
                    v-bind:class="{'border-info':review.deleted_at==null,'border-danger':review.deleted_at!=null}">
                    <th scope="row">{{ review.id }}</th>
                    <td >{{ review.botUser.fio_from_telegram || review.botUser.telegram_chat_id || 'Не указано' }}
                    </td>
                    <td>
                        <p class="d-flex align-items-center">
                            <vue3starRatings :disableClick="true" v-model="review.rating"/>
                            <span class="ml-2">{{ review.rating || 0 }}</span>
                        </p>

                    </td>
                    <td>{{ review.text || 'Не указано' }}</td>
                    <td>{{ $filters.current(review.updated_at) }}</td>
                    <td>
                        <div class="dropdown" v-if="review.id">
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li v-if="review.deleted_at==null">
                                    <a class="dropdown-item"
                                       @click="removeAppointmentReview(review.id)"
                                       href="javascript:void(0)">Удалить</a></li>
                            </ul>
                        </div>

                    </td>
                </tr>


                </tbody>
            </table>
            <p v-else>На текущий момент нет ни одного созданного отзыва</p>
        </div>
        <div class="col-12">
            <Pagination
                v-on:pagination_page="nextAppointmentReviews"
                v-if="paginate_object"
                :pagination="paginate_object"/>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";
import vue3starRatings from "vue3-star-ratings";
export default {
    props: ["bot", "eventId"],
    components:{
        vue3starRatings
    },
    data() {
        return {
            direction: 'desc',
            order: 'updated_at',
            show: true,
            is_group: false,
            loading: true,
            reviews: [],
            search: null,
            paginate_object: null,
        }
    },

    computed: {
        ...mapGetters(['getAppointmentReviews', 'getAppointmentReviewsPaginateObject']),

    },
    mounted() {
        this.loadAppointmentReviews();
    },
    methods: {
        nextAppointmentReviews(index) {
            this.loadAppointmentReviews(index)
        },
        selectReview(review){
          this.$emit("select", review)
        },
        loadAndOrder(order) {
            this.order = order
            this.direction = this.direction === 'desc' ? 'asc' : 'desc'
            this.loadAppointmentReviews(0)
        },
        removeAppointmentReview(id){
            this.loading = true
            this.$store.dispatch("removeAppointmentReview", {
                dataObject: {
                    appointmentReviewId: id,
                },
            }).then(resp => {

                this.loadAppointmentReviews(0)
                this.$notify("Сервис успешно удален");
                this.loading = false
            }).catch(() => {
                this.loading = false
                this.$notify("Ошибка удаления сервиса")
            })
        },
        loadAppointmentReviews(page = 0) {
            this.loading = true
            this.$store.dispatch("loadAppointmentReviews", {
                dataObject: {
                    event_id: this.eventId,
                    bot_id: this.bot.id || null,
                    search: this.search,
                    order: this.order,
                    direction: this.direction
                },
                page: page,
                size: 100
            }).then(resp => {
                this.loading = false
                this.reviews = this.getAppointmentReviews
                this.paginate_object = this.getAppointmentReviewsPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
