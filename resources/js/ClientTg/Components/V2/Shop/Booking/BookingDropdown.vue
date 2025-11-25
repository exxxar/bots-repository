<template>
    <div class="dropdown my-2" v-if="my_bookings.length > 0">
        <button
            class="btn btn-success dropdown-toggle w-100 p-3"
            type="button"
            id="bookingsDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false"
        >
            <span v-if="selectedTable">Выбран столик #{{ selectedTable.number }}</span>
            <span v-else>Мои брони ({{ my_bookings.length }})</span>
        </button>

        <ul class="dropdown-menu w-100" aria-labelledby="bookingsDropdown">
            <li
                @click="selectATable(null)"
                class="dropdown-item">
                Не выбран
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li
                @click="selectATable(item)"
                v-for="item in my_bookings" :key="item.id" class="dropdown-item">
                <div class="fw-bold">
                    Бронь столика №{{ item.number }}
                </div>
                <small class="text-muted">
                    Дата {{ item.booked_date_at }} в {{ item.booked_time_at }}
                </small>
                <p class="fst-italic mb-1">{{ item.booked_info?.description || '-' }}</p>

                <div class="d-flex justify-content-between align-items-center">
                    <button
                        @click="openRemoveModal(item)"
                        type="button"
                        class="btn btn-sm btn-danger"
                    >
                        Отменить
                    </button>
                    <span class="badge bg-primary rounded-pill">
            {{ item.booked_info?.persons || 1 }} чел.
          </span>
                </div>
            </li>
        </ul>


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


</template>
<script>
export default {
    data() {
        return {
            selectedTable: null,
            my_bookings: []
        }
    },
    mounted() {
        this.myUpcomingBookings()
    },
    methods: {

        selectATable(item) {
            this.selectedTable = null
            this.$nextTick(() => {
                this.selectedTable = item
                localStorage.setItem("cashman_current_active_table", JSON.stringify(item))

                this.$emit("select", this.selectedTable)
            })
        },
        myUpcomingBookings() {
            return this.$store.dispatch("myUpcomingBookings").then((resp) => {
                this.my_bookings = resp.data || []

                let storedTable = localStorage.getItem("cashman_current_active_table") || null

                if (storedTable)
                    storedTable = JSON.parse(storedTable)

                this.selectedTable = this.my_bookings.find(item => item.id === storedTable?.id) || null
            })
        },
        openRemoveModal(booking) {
            this.selectedTable = null
            this.$nextTick(() => {
                this.selectedTable = booking
                const myModalElement = document.getElementById('remove-booking-modal');
                const myModal = new bootstrap.Modal(myModalElement);
                myModal.show();


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

                localStorage.removeItem("cashman_current_active_table")

                this.myUpcomingBookings();

                const modalEl = document.getElementById('remove-booking-modal')
                const modal = bootstrap.Modal.getInstance(modalEl).hide();
                modal.hide();

            })
        },
    }
}
</script>
