<template>

    <a href="javascript:void(0)"
       @click="isEdit=!isEdit"
       class="btn btn-outline-light text-primary w-100 p-3 mb-2">
        <span v-if="!isEdit"><i class="fa-solid fa-user-pen mr-2"></i> Редактировать пользователя</span>
        <span v-else><i class="fa-solid fa-chevron-up mr-2"></i> Завершить редактирование</span>
    </a>

    <div v-if="!isEdit">
        <h6>Основные данные пользователя</h6>
        <table class="table">
            <thead>
            <tr class="bg-gray1-dark">
                <th scope="col" class="color-theme">Ключ</th>
                <th scope="col" class="color-theme">Значение</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row">ID в системе</th>
                <td class="font-weight-bold">{{ botUser.id || 'Не указано' }}</td>

            </tr>

            <tr>
                <th scope="row">Дата регистрации</th>
                <td class="font-weight-bold">{{ $filters.current(botUser.created_at) }}</td>

            </tr>

            <tr>
                <th scope="row">Телеграм ID</th>
                <td class="font-weight-bold">{{ botUser.telegram_chat_id || 'Не указано' }}</td>

            </tr>
            <tr v-bind:class="{'bg-red1-light':botUser.name==null}">
                <th scope="row">Имя</th>
                <td class="font-weight-bold">{{ botUser.name || 'Не указано' }}</td>

            </tr>
            <tr>
                <th scope="row">Имя из телеграмма</th>
                <td class="font-weight-bold">{{ botUser.fio_from_telegram || 'Не указано' }}</td>

            </tr>

            <tr>
                <th scope="row">Домен пользователя</th>
                <td class="font-weight-bold">{{ botUser.username || 'Не указано' }}</td>

            </tr>

            <tr v-bind:class="{'bg-red1-light':botUser.phone==null}">
                <th scope="row">Номер телефона</th>
                <td class="font-weight-bold">{{ botUser.phone || 'Не указано' }}</td>

            </tr>
            <tr v-bind:class="{'bg-red1-light':botUser.age==null}">
                <th scope="row">Возраст</th>
                <td class="font-weight-bold">{{ botUser.age || 'Не указано' }}</td>

            </tr>
            <tr>
                <th scope="row">Пол</th>
                <td class="font-weight-bold">{{ botUser.sex ? 'Мужчина' : 'Женщина' }}</td>

            </tr>

            <tr v-bind:class="{'bg-red1-light':botUser.country==null}">
                <th scope="row">Страна</th>
                <td class="font-weight-bold">{{ botUser.country || 'Не указано' }}</td>

            </tr>

            <tr v-bind:class="{'bg-red1-light':botUser.city==null}">
                <th scope="row">Город</th>
                <td class="font-weight-bold">{{ botUser.city || 'Не указано' }}</td>

            </tr>

            <tr v-bind:class="{'bg-red1-light':botUser.address==null}">
                <th scope="row">Адрес</th>
                <td class="font-weight-bold">{{ botUser.address || 'Не указано' }}</td>

            </tr>

            </tbody>
        </table>
        <h6>Статусы пользователя</h6>
        <table class="table">
            <thead>
            <tr class="bg-gray1-dark">
                <th scope="col" class="color-theme">Ключ</th>
                <th scope="col" class="color-theme">Значение</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row">VIP</th>
                <td class="font-weight-bold">{{ botUser.is_vip ? 'Да' : 'Нет' }}</td>

            </tr>

            <tr>
                <th scope="row">Администратор</th>
                <td class="font-weight-bold">{{ botUser.is_admin ? 'Да' : 'Нет' }}</td>

            </tr>
            <tr>
                <th scope="row">Доставщик</th>
                <td class="font-weight-bold">{{ botUser.is_deliveryman ? 'Да' : 'Нет' }}</td>

            </tr>
            <tr>
                <th scope="row">На работе</th>
                <td class="font-weight-bold">{{ botUser.is_work ? 'Да' : 'Нет' }}</td>

            </tr>

            <tr>
                <th scope="row">В заведении</th>
                <td class="font-weight-bold">{{ botUser.user_in_location ? 'Да' : 'Нет' }}</td>

            </tr>

            <tr v-if="botUser.blocked_at">
                <th scope="row">Пользователь заблокирован</th>
                <td class="font-weight-bold">
                    <p class="mb-0"> {{ $filters.current(botUser.blocked_at) }}</p>
                    <p class="mb-0"><em>{{ botUser.blocked_message ?? 'Без сообщения' }}</em></p>
                </td>
            </tr>

            </tbody>
        </table>
    </div>


    <form v-on:submit.prevent="submit" v-if="isEdit">

        <div class="form-floating mb-2">

            <input type="text" class="form-control"
                   v-model="botUserForm.name"
                   @invalid="alert('Вы не указали имя!')"
                   placeholder="Иванов Иван Иванович" required>
            <label for="">Ф.И.О пользователя</label>
        </div>


        <div class="form-floating mb-2">

            <input type="text" class="form-control"
                   v-model="botUserForm.phone"
                   v-mask="'+7(###)###-##-##'"
                   @invalid="alert('Вы не указали телефон!')"
                   placeholder="+7(XXX) XXX-XX-XX" required>
            <label for="">Телефон</label>
        </div>

        <div class="form-floating mb-2">

            <input type="text" class="form-control"
                   v-model="botUserForm.email"
                   placeholder="example@gmail.com">
            <label for="">Почта</label>
        </div>

        <div class="form-floating mb-2">

            <input type="text" class="form-control"
                   v-model="botUserForm.address"
                   placeholder="ул. Петрова, 123, кв 45">
            <label for="">Адрес</label>
        </div>

        <div class="form-floating mb-2">

            <input type="date" class="form-control"
                   v-model="botUserForm.birthday"
                   placeholder="12/01/2023">
            <label for="">Дата рождения</label>
        </div>

        <div class="form-floating mb-2">

            <input type="text" class="form-control"
                   v-model="botUserForm.country"
                   placeholder="Россия">
            <label for="">Страна</label>
        </div>


        <div class="form-floating mb-2">

            <input type="text" class="form-control"
                   v-model="botUserForm.city"
                   placeholder="Краснодар">
            <label for="">Город</label>
        </div>
        <div class="row mb-0">
            <p class="col-12 my-2">Администратор</p>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_admin = true"
                    v-bind:class="{'btn-info':botUserForm.is_admin,'btn-outline-secondary':!botUserForm.is_admin}"
                    class="w-100 btn">
                    <i class="fa-solid fa-check mr-1"></i> Да
                </button>
            </div>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_admin = false"
                    v-bind:class="{'btn-info':!botUserForm.is_admin,'btn-outline-secondary':botUserForm.is_admin}"
                    class="w-100 btn">
                    <i class="fa-solid fa-xmark mr-1"></i> Нет
                </button>
            </div>
        </div>

        <div class="row mb-0">
            <p class="col-12 my-2">VIP</p>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_vip = true"
                    v-bind:class="{'btn-info':botUserForm.is_vip,'btn-outline-secondary':!botUserForm.is_vip}"
                    class="w-100 btn">
                    <i class="fa-solid fa-check mr-1"></i> Да
                </button>
            </div>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_vip = false"
                    v-bind:class="{'btn-info':!botUserForm.is_vip,'btn-outline-secondary':botUserForm.is_vip}"
                    class="w-100 btn">
                    <i class="fa-solid fa-xmark mr-1"></i> Нет
                </button>
            </div>
        </div>

        <div class="row mb-0">
            <p class="col-12 my-2">Доставщик</p>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_deliveryman = true"
                    v-bind:class="{'btn-info':botUserForm.is_deliveryman,'btn-outline-secondary':!botUserForm.is_deliveryman}"
                    class="w-100 btn">
                    <i class="fa-solid fa-check mr-1"></i> Да
                </button>
            </div>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_deliveryman = false"
                    v-bind:class="{'btn-info':!botUserForm.is_deliveryman,'btn-outline-secondary':botUserForm.is_deliveryman}"
                    class="w-100 btn">
                    <i class="fa-solid fa-xmark mr-1"></i> Нет
                </button>
            </div>
        </div>

        <div class="row mb-0">
            <p class="col-12 my-2">Менеджер</p>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_manager = true"
                    v-bind:class="{'btn-info':botUserForm.is_manager,'btn-outline-secondary':!botUserForm.is_manager}"
                    class="w-100 btn">
                    <i class="fa-solid fa-check mr-1"></i> Да
                </button>
            </div>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_manager = false"
                    v-bind:class="{'btn-info':!botUserForm.is_manager,'btn-outline-secondary':botUserForm.is_manager}"
                    class="w-100 btn">
                    <i class="fa-solid fa-xmark mr-1"></i> Нет
                </button>
            </div>
        </div>

        <div class="row mb-0">
            <p class="col-12 my-2">Работает</p>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_work = true"
                    v-bind:class="{'btn-info':botUserForm.is_work,'btn-outline-secondary':!botUserForm.is_work}"
                    class="w-100 btn">
                    <i class="fa-solid fa-check mr-1"></i> Да
                </button>
            </div>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_work = false"
                    v-bind:class="{'btn-info':!botUserForm.is_work,'btn-outline-secondary':botUserForm.is_work}"
                    class="w-100 btn">
                    <i class="fa-solid fa-xmark mr-1"></i> Нет
                </button>
            </div>
        </div>

        <div class="row mb-0">
            <p class="col-12 my-2">В заведении</p>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.user_in_location = true"
                    v-bind:class="{'btn-info':botUserForm.user_in_location,'btn-outline-secondary':!botUserForm.user_in_location}"
                    class="w-100 btn">
                    <i class="fa-solid fa-check mr-1"></i> Да
                </button>
            </div>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.user_in_location = false"
                    v-bind:class="{'btn-info':!botUserForm.user_in_location,'btn-outline-secondary':botUserForm.user_in_location}"
                    class="w-100 btn">
                    <i class="fa-solid fa-xmark mr-1"></i> Нет
                </button>
            </div>
        </div>

        <div class="row mb-0">
            <p class="col-12 my-2">Заблокирован</p>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_blocked = true"
                    v-bind:class="{'btn-info':botUserForm.is_blocked,'btn-outline-secondary':!botUserForm.is_blocked}"
                    class="w-100 btn">
                    <i class="fa-solid fa-check mr-1"></i> Да
                </button>
            </div>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_blocked = false"
                    v-bind:class="{'btn-info':!botUserForm.is_blocked,'btn-outline-secondary':botUserForm.is_blocked}"
                    class="w-100 btn">
                    <i class="fa-solid fa-xmark mr-1"></i> Нет
                </button>
            </div>
            <div class="col-12" v-if="botUserForm.is_blocked">
                <label for="">Сообщение блокировки</label>
                <input type="text" class="form-control"
                       v-model="botUserForm.blocked_message"
                       placeholder="Увы и ах.. ">
            </div>
        </div>

        <div class="row mb-0">
            <p class="col-12 my-2">Пол</p>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.sex = true"
                    v-bind:class="{'btn-info':botUserForm.sex,'btn-outline-secondary':!botUserForm.sex}"
                    class="w-100 btn">
                    <i class="fa-solid fa-mars mr-1"></i> Муж
                </button>
            </div>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.sex = false"
                    v-bind:class="{'btn-info':!botUserForm.sex,'btn-outline-secondary':botUserForm.sex}"
                    class="w-100 btn">
                    <i class="fa-solid fa-venus mr-1"></i> Жен
                </button>
            </div>
        </div>

        <div v-if="messages.length>0"
             v-for="(message, index) in messages"
             class="alert alert-small rounded-s shadow-xl bg-red2-dark w-100" role="alert">

            <p class="custom-alert-text">{{ message || 'Ошибка' }}</p>
            <button type="button"
                    @click="removeMessage(index)"
                    class="close color-white opacity-60 font-16">×
            </button>
        </div>

        <button type="submit"
                class="btn btn-primary p-3 my-3 w-100">
            Сохранить
        </button>
    </form>

</template>
<script>
export default {
    props: ["botUser"],
    data() {
        return {
            isEdit: false,
            messages: [],
            botUserForm: {
                id: null,
                is_vip: false,
                is_admin: false,
                is_work: false,
                is_manager: false,
                is_deliveryman: false,
                user_in_location: false,
                name: null,
                phone: null,
                email: null,
                birthday: null,
                age: null,
                city: null,
                country: null,
                address: null,
                sex: null,
                is_blocked: null,
                blocked_message: null,
            }
        }
    },


    watch: {
        'isEdit': function () {
            this.botUserForm.id = this.botUser.id
            this.botUserForm.is_vip = this.botUser.is_vip
            this.botUserForm.is_admin = this.botUser.is_admin
            this.botUserForm.is_work = this.botUser.is_work
            this.botUserForm.is_manager = this.botUser.is_manager
            this.botUserForm.is_deliveryman = this.botUser.is_deliveryman
            this.botUserForm.user_in_location = this.botUser.user_in_location
            this.botUserForm.name = this.botUser.name || this.botUser.username || this.botUser.id
            this.botUserForm.phone = this.botUser.phone
            this.botUserForm.email = this.botUser.email
            this.botUserForm.birthday = this.botUser.birthday || null
            this.botUserForm.city = this.botUser.city || null
            this.botUserForm.country = this.botUser.country || null
            this.botUserForm.address = this.botUser.address || null
            this.botUserForm.sex = this.botUser.sex || false
            this.botUserForm.is_blocked = this.botUser.blocked_at != null
            this.botUserForm.blocked_message = this.botUser.blocked_message || null
        }
    },

    methods: {
        alert(msg) {
            this.messages.push(msg)
        },
        removeMessage(index) {
            this.messages.splice(index, 1)
        },
        submit() {
            this.$store.dispatch('updateBotUser', {
                botUserForm: this.botUserForm
            }).then(() => {
                this.isEdit = false

                this.messages = []
                this.botUserForm = {
                    id: null,
                    is_vip: false,
                    is_admin: false,
                    is_work: false,
                    is_manager: false,
                    is_deliveryman: false,
                    user_in_location: false,
                    name: null,
                    phone: null,
                    email: null,
                    birthday: null,
                    age: null,
                    city: null,
                    country: null,
                    address: null,
                    sex: null,
                    is_blocked: false
                }

                this.$emit("update")

                this.$notify({
                    title: "Редактирование данных",
                    text: "Данные успешно обновлены!",
                    type: "success"
                })
            }).catch(() => {
                this.$notify({
                    title: "Редактирование данных",
                    text: "Ошибка обновления данных",
                    type: "error"
                })
            })
        }
    }
}
</script>
