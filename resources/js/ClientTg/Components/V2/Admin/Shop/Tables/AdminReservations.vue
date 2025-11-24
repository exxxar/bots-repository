<template>

    <!-- Кнопка-триггер модального окна -->
    <button type="button" class="btn-info btn w-100 p-3 mb-2" data-bs-toggle="modal"
            data-bs-target="#admin-booking-modal">
        <i class="fa-solid fa-calendar"></i>
         Выгрузить брони
    </button>

    <!-- Модальное окно -->
    <div class="modal fade" id="admin-booking-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Заголовок модального окна</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">

                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a
                                @click="tab='export'"
                                v-bind:class="{'active':tab==='export'}"
                                class="nav-link" aria-current="page" href="javascript:void(0)">Экспорт</a>
                        </li>
                        <li class="nav-item">
                            <a @click="tab='bookings'"
                               v-bind:class="{'active':tab==='bookings'}"
                               class="nav-link" aria-current="page" href="javascript:void(0)">Список броней</a>
                        </li>
                        <li class="nav-item">
                            <a @click="tab='counts'"
                               v-bind:class="{'active':tab==='counts'}"
                               class="nav-link" aria-current="page" href="javascript:void(0)">Сводка</a>
                        </li>
                    </ul>

                    <div v-show="tab==='export'">
                        <h6 class="mb-2 mt-3 fw-bold">Выгрузить брони по дате</h6>
                        <form @submit.prevent="exportNearestBookings">
                            <div class="form-floating mb-2">

                                <input
                                    type="date"
                                    id="startDate"
                                    v-model="startDate"
                                    class="form-control"
                                />
                                <label for="startDate" class="form-label">С даты:</label>
                            </div>

                            <div class="form-floating mb-2">
                                <input
                                    type="date"
                                    id="endDate"
                                    v-model="endDate"
                                    class="form-control"
                                />
                                <label for="endDate" class="form-label">По дату:</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" :disabled="loading">
                        <span v-if="loading" class="spinner-border spinner-border-sm" role="status"
                              aria-hidden="true"></span>
                                <span v-else>Выгрузить</span>
                            </button>

                        </form>
                    </div>

                    <div v-show="tab==='bookings'">
                        <h6 class="mb-2 mt-3 fw-bold">Бронирования на ближайшую неделю
                            <span v-if="bookings.length > 0">({{ bookings.length }} ед.)</span>
                        </h6>

                        <div
                            v-if="bookings.length > 0"
                            class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th>Время</th>
                                    <th>Персон</th>
                                    <th>Номер столика</th>
                                    <th>Описание</th>
                                    <th>На кого бронь</th>
                                    <th>Номер телефона</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="reservation in bookings" :key="reservation.id">
                                    <td>{{ reservation.booked_date_at }}</td>
                                    <td>{{ reservation.booked_time_at }}</td>
                                    <td>{{ reservation.booked_info?.persons }}</td>
                                    <td>{{ reservation.number || '-' }}</td>
                                    <td>{{ reservation.booked_info?.description || '-' }}</td>
                                    <td>{{ reservation.booked_info?.name || '-' }}</td>
                                    <td>{{ reservation.booked_info?.phone || '-' }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-else
                             class="alert alert-info mb-2 mt-3">
                            Бронирований за выбранный период не найдено.
                        </div>
                    </div>


                    <div v-show="tab==='counts'">
                        <h6 class="mb-2 mt-3 fw-bold">Ближайшие бронирования (по дате)</h6>

                        <ul class="list-group" v-if="counts.length > 0">
                            <li
                                v-for="item in counts"
                                class="list-group-item d-flex justify-content-between align-items-center"
                            >
                                {{ item.date }}
                                <span class="badge bg-primary rounded-pill">{{ item.total }}</span>
                            </li>
                        </ul>
                        <div v-else
                             class="alert alert-info mb-2 mt-3">
                            Пока нет ближайших бронирований.
                        </div>
                    </div>
                    <!-- Список выгруженных броней -->


                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    data() {
        return {
            tab: 'export',
            startDate: this.getTodayDate(),
            endDate: this.getTodayDate(),

            loading: false,

            counts: [],
            bookings: [],
        };
    },
    computed: {},
    methods: {
        getTodayDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },


        exportNearestBookings() {
            return this.$store.dispatch("exportNearestBookings", {
                start_date: this.startDate,
                end_date: this.endDate
            }).then((resp) => {
                this.$notify({
                    title: "Выгрузка броней",
                    text: "Список броней успешно выгружен",
                    type: "success"
                })
            }).catch(() => {
                this.$notify({
                    title: "Выгрузка броней",
                    text: "Ошибка выгрузки броней",
                    type: "error"
                })
            })
        },

        nearestBookingList() {
            return this.$store.dispatch("nearestBookingList", {
                start_date: null,
                end_date: null
            }).then((resp) => {
                console.log(resp)
                this.counts = resp.counts || []
                this.bookings = resp.bookings.data || []
            }).catch(() => {
                this.$notify({
                    title: "Список броней",
                    text: "Ошибка загрузки списка броней",
                    type: "error"
                })
            })
        },

    },
    mounted() {
        this.nearestBookingList(); // Загружаем бронирования за сегодняшний день по умолчанию
    }
};
</script>

<style scoped>
.admin-reservations-container {
    max-width: 900px;
    margin: 30px auto;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.form-label {
    font-weight: 500;
    color: #495057;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.table-responsive {
    margin-top: 15px;
}

.table thead th {
    background-color: #e9ecef;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
    width: 150px;
    min-width: 150px;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.03);
}

.list-group-item {
    font-size: 1.05em;
    padding: 12px 15px;
}

.badge {
    font-size: 0.85em;
    padding: 0.4em 0.7em;
}
</style>
