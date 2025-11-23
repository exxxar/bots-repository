<script setup>
import BookingForm from "@/ClientTg/Components/V2/Shop/Booking/BookingForm.vue";
import AdminReservations from "@/ClientTg/Components/V2/Admin/Shop/Tables/AdminReservations.vue";
</script>

<template>
    <div class="p-2" v-if="bot">

        <!-- Список выбранных столиков -->
        <div class="p-2" v-if="sortedSelectedTables.length>0">

            <template v-if="self.is_admin">
                <AdminReservations></AdminReservations>
            </template>

            <h6 class="fw-bold">Столики на выбор</h6>
            <div

                class="row row-cols-2 "
            >
                <div class="col mb-2"
                     @click="openModal(table)"
                     v-for="(table, index) in sortedSelectedTables"
                     :key="index">

                    <div class="border d-flex flex-column px-3">
                        <img v-lazy="'/images/tables/'+table.image" alt="table" class="table-img-small"/>
                        <p class="fw-bold mb-2">Столик №{{ table.number }}</p>
                        <p>{{ table.description }}</p>
                    </div>

                </div>


            </div>
        </div>
        <p class="alert alert-light" v-else>
            Нет столиков для бронирования
        </p>

        <ol class="list-group list-group-numbered" v-if="my_bookings.length>0">
            <li
                v-for="item in my_bookings"
                class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold mb-0">
                        Бронь столика №{{ item.number }}
                    </div>
                    <p class="mb-0">Дата {{ item.booked_date_at }} в {{ item.booked_time_at }}</p>

                    <p class="mb-2 fst-italic">{{ item.booked_info?.description || '-' }}</p>

                    <button
                        @click="openRemoveModal(item)"
                        type="button" class="btn btn-danger w-100">Отменить бронирование
                    </button>
                </div>
                <span class="badge bg-primary rounded-pill">{{ item.booked_info.persons || 1 }} чел.</span>
            </li>
        </ol>
        <p class="alert alert-light" v-else>
            У вас нет актуальных забронированных столиков
        </p>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="remove-booking-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Удаление брони</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <template v-if="selectedTable">
                        <p class="mb-2">Вы точно хотите удалить бронь на <strong
                            class="fw-bold text-primary">{{ selectedTable.booked_date_at || '-' }}</strong> для столика
                            <strong
                                class="fw-bold text-primary">{{ selectedTable.number || '-' }}</strong>?</p>

                        <div class="w-100 d-flex justify-content-center">
                            <button
                                @click="cancelBookingTable"
                                style="margin-right:10px;"
                                class="btn btn-danger" type="button">Да, удалить
                            </button>
                            <button
                                data-bs-dismiss="modal"
                                class="btn btn-secondary" type="button">Нет, отменить
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>


    <!-- Определение Модального Окна -->
    <div class="modal fade" id="booking-modal" tabindex="-1" aria-labelledby="mySimpleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mySimpleModalLabel">Бронирование столика</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <template v-if="selectedTable">
                        <BookingForm
                            v-on:success="closeModal"
                            v-on:failure="closeModal"
                            :table-info="selectedTable"></BookingForm>
                    </template>
                </div>

            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "Booking",
    data() {
        return {
            my_bookings: [],
            selectedTable: null
        };
    },
    computed: {
        self() {
            return window.self || null
        },
        bot() {
            return window.currentBot
        },
        sortedSelectedTables() {
            let settings = this.bot.settings

            if (!settings.tables_variants)
                return []
            return settings.tables_variants
                .sort((a, b) => a.seats - b.seats);
        }
    },
    mounted() {
        this.myUpcomingBookings()
    },

    methods: {
        openRemoveModal(booking) {
            this.selectedTable = null
            this.$nextTick(() => {
                this.selectedTable = booking
                const myModalElement = document.getElementById('remove-booking-modal');
                const myModal = new bootstrap.Modal(myModalElement);
                myModal.show();


            })
        },
        closeModal() {
            this.selectedTable = null
            this.$nextTick(() => {
                const modalEl = document.getElementById('booking-modal')
                const modal = bootstrap.Modal.getInstance(modalEl).hide();
                modal.hide();


            })
        },
        openModal(table) {
            this.selectedTable = null
            this.$nextTick(() => {
                this.selectedTable = table
                const myModalElement = document.getElementById('booking-modal');
                const myModal = new bootstrap.Modal(myModalElement);
                myModal.show();


            })
        },
        myUpcomingBookings() {
            return this.$store.dispatch("myUpcomingBookings").then((resp) => {
                this.my_bookings = resp.data || []
            })
        },
        cancelBookingTable() {
            if (!this.selectedTable)
                return

            return this.$store.dispatch("cancelBookingTable", {
                bookingId: this.selectedTable.id
            }).then((resp) => {

                this.$notify({
                    title: "Бронирование",
                    text: "Бронь столика успешно убрана!",
                    type: "success"
                })

                this.myUpcomingBookings();

                const modalEl = document.getElementById('remove-booking-modal')
                const modal = bootstrap.Modal.getInstance(modalEl).hide();
                modal.hide();

            })
        },
    },
};
</script>

<style scoped>
.hall-constructor {

}

.table-img-small {
    width: 100%;
    object-fit: cover;
}

.table-img {
    width: 100%;
    object-fit: cover;
    height: 150px;
}

</style>
