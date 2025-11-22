<template>




    <div class="alert alert-light mb-2" v-if="reservation.table">
        <p class="mb-0">Вы бронируете столик <strong class="fw-bold">#{{ reservation.table.number }}</strong> -
            {{ reservation.table.description }}</p>
    </div>

    <form @submit.prevent="submitReservation">
        <div class="form-floating mb-2">

            <input
                type="date"
                id="reservationDate"
                v-model="reservation.date"
                required
                class="form-control"
            />
            <label for="reservationDate">Дата бронирования</label>
        </div>

        <div class="form-floating mb-2">

            <input
                type="time"
                id="reservationTime"
                v-model="reservation.time"
                required
                class="form-control"
            />
            <label for="reservationTime">Время бронирования</label>
        </div>

        <div class="form-floating mb-2">

            <input
                type="number"
                id="numberOfPersons"
                v-model.number="reservation.persons"
                min="1"
                required
                class="form-control"
            />
            <label for="numberOfPersons">Число персон</label>
        </div>


        <div class="form-floating mb-2">

            <input
                type="text"
                id="userName"
                v-model.number="reservation.name"
                min="1"
                required
                class="form-control"
            />
            <label for="userName">На чьё имя бронировать</label>
        </div>


        <div class="form-floating mb-2">

            <input
                type="text"
                id="userPhone"
                v-model.number="reservation.phone"
                min="1"
                required
                class="form-control"
            />
            <label for="userPhone">Номер телефона</label>
        </div>

        <div class="form-floating mb-2">

                <textarea
                    id="reservationDescription"
                    v-model="reservation.description"
                    rows="4"
                    class="form-control"
                ></textarea>
            <label for="reservationDescription">Описание к брони (пожелания):</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 p-3">Забронировать</button>
    </form>


</template>

<script>
export default {
    props: ["tableInfo"],
    data() {
        return {
            reservation: {
                date: '',
                time: '',
                name: '',
                phone: '',
                persons: 1,
                description: '',
                table: null,
            },
            successMessage: '',
            errorMessage: ''
        };
    },
    computed: {
        self() {
            return window.self || null
        }
    },
    methods: {
        validateForm() {
// Проверка на заполненность обязательных полей
            if (!this.reservation.date || !this.reservation.time || !this.reservation.persons) {
                this.errorMessage = 'Пожалуйста, заполните все обязательные поля (Дата, Время, Число персон).';
                return false;
            }
// Проверка на минимальное количество персон
            if (this.reservation.persons < 1) {
                this.errorMessage = 'Число персон должно быть не менее 1.';
                return false;
            }

// Дополнительная валидация (например, дата не в прошлом)
            const selectedDateTime = new Date(`${this.reservation.date}T${this.reservation.time}`);
            const now = new Date();
            if (selectedDateTime < now) {
                this.errorMessage = 'Дата и время бронирования не могут быть в прошлом.';
                return false;
            }

            this.errorMessage = '';
            return true;
        },
        submitReservation() {
            if (!this.validateForm()) {
                this.successMessage = '';
                return;
            }

// Здесь вы можете отправить данные бронирования на сервер
// Например, через fetch API или axios
            console.log('Данные бронирования:', this.reservation);

// Имитация отправки данных
            this.successMessage = 'Ваш столик успешно забронирован!';
            this.errorMessage = '';

// Сброс формы после успешной отправки (опционально)
            this.reservation = {
                date: '',
                time: '',
                persons: 1,
                description: ''
            };
        }
    },
    mounted() {

        if (this.tableInfo) {
            this.$nextTick(() => {
                this.reservation.table = this.tableInfo
                this.reservation.persons = this.tableInfo.seats || 1
                this.reservation.description = 'Хочу забронировать этот столик'
                this.reservation.name = this.self.name ||null
                this.reservation.phone = this.self.phone || null
            })

        }

// Устанавливаем минимальную дату для поля 'date' на сегодня
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        document.getElementById('reservationDate').setAttribute('min', `${year}-${month}-${day}`);
    }
};
</script>

<style scoped>
.reservation-form-container {
    max-width: 500px;
    margin: 20px auto;
    padding: 25px;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    background-color: #ffffff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
}

h2 {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 25px;
    font-size: 1.8em;
    font-weight: 600;
}

.reservation-form {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 8px;
    font-weight: 500;
    color: #555;
    font-size: 0.95em;
}

.form-control {
    padding: 12px 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 1em;
    color: #444;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    outline: none;
}

textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

.submit-button {
    background-color: #28a745;
    color: white;
    padding: 14px 25px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.1em;
    font-weight: 600;
    transition: background-color 0.2s ease, transform 0.1s ease;
    margin-top: 15px;
}

.submit-button:hover {
    background-color: #218838;
    transform: translateY(-1px);
}

.submit-button:active {
    background-color: #1e7e34;
    transform: translateY(0);
}

.success-message {
    margin-top: 20px;
    padding: 15px;
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    border-radius: 6px;
    text-align: center;
    font-size: 1em;
}

.error-message {
    margin-top: 20px;
    padding: 15px;
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    border-radius: 6px;
    text-align: center;
    font-size: 1em;
}
</style>
