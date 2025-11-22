<template>

    <!-- Кнопка-триггер модального окна -->
    <button type="button" class="btn-primary btn w-100 p-3 mb-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Выгрузить брони
    </button>

    <!-- Модальное окно -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Заголовок модального окна</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">

                        <h2 class="mb-4 text-center">Управление бронированием столиков</h2>

                    <div class="border border-light p-3 mb-2">
                        <h6 class="mb-2 fw-bold">Выгрузить брони по дате</h6>
                        <form @submit.prevent="loadReservations">

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


                        <!-- Список выгруженных броней -->
                        <div v-if="filteredReservations.length > 0 && !loading" class="border border-light p-3 mb-2">
                            <h6 class="mb-2 fw-bold">Бронирования за выбранный период ({{ filteredReservations.length }})</h6>
                            <div class="table-responsive">
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
                                    <tr v-for="reservation in filteredReservations" :key="reservation.id">
                                        <td>{{ reservation.date }}</td>
                                        <td>{{ reservation.time }}</td>
                                        <td>{{ reservation.persons }}</td>
                                        <td>{{ reservation.number || '-' }}</td>
                                        <td>{{ reservation.description || '-' }}</td>
                                        <td>{{ reservation.name || '-' }}</td>
                                        <td>{{ reservation.phone || '-' }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div v-else-if="!loading && !error && loaded && filteredReservations.length === 0"
                             class="alert alert-info mb-2">
                            Бронирований за выбранный период не найдено.
                        </div>


                    <div class="border border-light p-3">
                        <h6 class="mb-2 fw-bold">Ближайшие бронирования (по дате)</h6>

                        <ul class="list-group">
                            <li v-if="Object.keys(upcomingReservationsByDate).length === 0" class="list-group-item text-muted">
                                Пока нет ближайших бронирований.
                            </li>
                            <li
                                v-for="(count, date) in upcomingReservationsByDate"
                                :key="date"
                                class="list-group-item d-flex justify-content-between align-items-center"
                            >
                                {{ formatDateForDisplay(date) }}
                                <span class="badge bg-primary rounded-pill">{{ count }}</span>
                            </li>
                        </ul>
                    </div>


                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    data() {
        return {
            startDate: this.getTodayDate(),
            endDate: this.getTodayDate(),
            allReservations: [], // Имитация базы данных бронирований
            filteredReservations: [],
            loading: false,
            error: null,
            loaded: false, // Флаг, указывающий, была ли попытка загрузки
        };
    },
    computed: {
        upcomingReservationsByDate() {
            const today = new Date(this.getTodayDate()); // Сегодняшняя дата для сравнения
            const upcoming = {};

// Фильтруем бронирования, которые еще не прошли
            const futureReservations = this.allReservations.filter(res => {
                const resDate = new Date(res.date);
                return resDate >= today; // Включаем сегодняшние и будущие брони
            });

// Сортируем по дате для "ближайших"
            futureReservations.sort((a, b) => new Date(a.date) - new Date(b.date));

// Группируем по дате и считаем количество
            futureReservations.forEach(res => {
                if (upcoming[res.date]) {
                    upcoming[res.date]++;
                } else {
                    upcoming[res.date] = 1;
                }
            });
            return upcoming;
        },
    },
    methods: {
        getTodayDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },
        formatDateForDisplay(dateString) {
// Форматируем дату для более читаемого вида
            const options = {year: 'numeric', month: 'long', day: 'numeric'};
            return new Date(dateString).toLocaleDateString('ru-RU', options);
        },
        async loadReservations() {
            this.loading = true;
            this.error = null;
            this.filteredReservations = [];
            this.loaded = false;

// Базовая валидация дат
            if (!this.startDate || !this.endDate) {
                this.error = 'Пожалуйста, выберите обе даты для диапазона.';
                this.loading = false;
                return;
            }
            if (new Date(this.startDate) > new Date(this.endDate)) {
                this.error = 'Дата начала не может быть позже даты окончания.';
                this.loading = false;
                return;
            }

// Имитация задержки запроса к API
            await new Promise(resolve => setTimeout(resolve, 500));

            try {
                // Здесь обычно был бы HTTP-запрос к вашему бэкенду
                // const response = await fetch(`/api/reservations?startDate=${this.startDate}&endDate=${this.endDate}`);
                // if (!response.ok) {
                //   throw new Error('Ошибка при загрузке бронирований');
                // }
                // const data = await response.json();
                // this.filteredReservations = data;

                // Вместо API-запроса, фильтруем из нашей имитации базы данных
                this.filteredReservations = this.allReservations.filter(res => {
                    const resDate = new Date(res.date);
                    const start = new Date(this.startDate);
                    const end = new Date(this.endDate);
                    // Учитываем, что конечная дата должна включать весь день
                    end.setHours(23, 59, 59, 999);
                    return resDate >= start && resDate <= end;
                }).sort((a, b) => {
                    // Сортируем отфильтрованные брони по дате и времени
                    const dateA = new Date(`${a.date}T${a.time}`);
                    const dateB = new Date(`${b.date}T${b.time}`);
                    return dateA - dateB;
                });

            } catch (err) {
                this.error = `Не удалось загрузить бронирования: ${err.message}`;
                console.error('Ошибка загрузки бронирований:', err);
            } finally {
                this.loading = false;
                this.loaded = true;
            }
        },
        generateSampleReservations() {
            const today = new Date();
            const reservations = [];

// Прошлые бронирования (для демонстрации фильтрации)
            reservations.push({id: 1, date: '2025-11-20', time: '18:00', persons: 4, description: 'День рождения'});
            reservations.push({id: 2, date: '2025-11-20', time: '20:30', persons: 2, description: ''});
            reservations.push({id: 3, date: '2025-11-19', time: '19:00', persons: 3, description: 'Корпоратив'});

// Сегодняшние бронирования
            const todayString = this.getTodayDate();
            reservations.push({id: 4, date: todayString, time: '14:00', persons: 2, description: 'Бизнес-ланч'});
            reservations.push({id: 5, date: todayString, time: '19:00', persons: 5, description: 'Юбилей'});
            reservations.push({id: 6, date: todayString, time: '21:00', persons: 3, description: ''});

// Будущие бронирования (для "ближайших")
            const tomorrow = new Date(today);
            tomorrow.setDate(today.getDate() + 1);
            const tomorrowString = `${tomorrow.getFullYear()}-${String(tomorrow.getMonth() + 1).padStart(2, '0')}-${String(tomorrow.getDate()).padStart(2, '0')}`;
            reservations.push({
                id: 7,
                date: tomorrowString,
                time: '18:30',
                persons: 2,
                description: 'Романтический ужин'
            });
            reservations.push({id: 8, date: tomorrowString, time: '20:00', persons: 6, description: ''});

            const dayAfterTomorrow = new Date(today);
            dayAfterTomorrow.setDate(today.getDate() + 2);
            const dayAfterTomorrowString = `${dayAfterTomorrow.getFullYear()}-${String(dayAfterTomorrow.getMonth() + 1).padStart(2, '0')}-${String(dayAfterTomorrow.getDate()).padStart(2, '0')}`;
            reservations.push({
                id: 9,
                date: dayAfterTomorrowString,
                time: '19:30',
                persons: 4,
                description: 'Семейный ужин'
            });

            const nextWeek = new Date(today);
            nextWeek.setDate(today.getDate() + 7);
            const nextWeekString = `${nextWeek.getFullYear()}-${String(nextWeek.getMonth() + 1).padStart(2, '0')}-${String(nextWeek.getDate()).padStart(2, '0')}`;
            reservations.push({id: 10, date: nextWeekString, time: '20:00', persons: 2, description: 'Выпускной'});
            reservations.push({id: 11, date: nextWeekString, time: '17:00', persons: 8, description: 'День рождения'});

            this.allReservations = reservations;
        }
    },
    mounted() {
        this.generateSampleReservations(); // Заполняем имитацией данных при загрузке компонента
        this.loadReservations(); // Загружаем бронирования за сегодняшний день по умолчанию
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
