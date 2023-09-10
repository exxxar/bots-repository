<template>

    <a href="javascript:void(0)"
       @click="isEdit=!isEdit"
       class="btn btn-m btn-full mb-2 rounded-s text-uppercase font-900 shadow-s bg-brown2-dark">
        <span v-if="!isEdit"><i class="fa-solid fa-user-pen mr-2"></i> Редактировать пользователя</span>
        <span v-else><i class="fa-solid fa-chevron-up mr-2"></i> Завершить редактирование</span>
    </a>
    <table class="table table-borderless  rounded-sm shadow-l"
           v-if="!isEdit"
           style="overflow: hidden;">
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
            <th scope="row">Телеграм ID</th>
            <td class="font-weight-bold">{{ botUser.telegram_chat_id || 'Не указано' }}</td>

        </tr>
        <tr v-bind:class="{'bg-red1-light':botUser.name==null}">
            <th scope="row">Имя </th>
            <td class="font-weight-bold">{{ botUser.name || 'Не указано' }}</td>

        </tr>
        <tr>
            <th scope="row">Имя из телеграмма</th>
            <td class="font-weight-bold">{{ botUser.fio_from_telegram || 'Не указано' }}</td>

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

        <tr>
            <th scope="row">VIP</th>
            <td class="font-weight-bold">{{ botUser.is_vip ? 'Да' : 'Нет' }}</td>

        </tr>

        <tr>
            <th scope="row">Администратор</th>
            <td class="font-weight-bold">{{ botUser.is_admin ? 'Да' : 'Нет' }}</td>

        </tr>
        <tr>
            <th scope="row">На работе</th>
            <td class="font-weight-bold">{{ botUser.is_work ? 'Да' : 'Нет' }}</td>

        </tr>

        <tr>
            <th scope="row">В заведении</th>
            <td class="font-weight-bold">{{ botUser.user_in_location ? 'Да' : 'Нет' }}</td>

        </tr>

        </tbody>
    </table>
    <form v-on:submit.prevent="submit" v-if="isEdit">
        <div class="form-group mb-2">
            <label for="">Ф.И.О пользователя</label>
            <input type="text" class="form-control"
                   v-model="botUserForm.name"
                   placeholder="Иванов Иван Иванович">
        </div>

        <div class="form-group mb-2">
            <label for="">Телефон</label>
            <input type="text" class="form-control"
                   v-model="botUserForm.phone"
                   v-mask="'+7(###)###-##-##'"
                   placeholder="+7(XXX) XXX-XX-XX">
        </div>

        <div class="form-group mb-2">
            <label for="">Почта</label>
            <input type="text" class="form-control"
                   v-model="botUserForm.email"
                   placeholder="example@gmail.com">
        </div>

        <div class="form-group mb-2">
            <label for="">Адрес</label>
            <input type="text" class="form-control"
                   v-model="botUserForm.address"
                   placeholder="ул. Петрова, 123, кв 45">
        </div>

        <div class="form-group mb-2">
            <label for="">Дата рождения</label>
            <input type="date" class="form-control"
                   v-model="botUserForm.birthday"
                   placeholder="12/01/2023">
        </div>

        <div class="form-group mb-2">
            <label for="">Страна</label>
            <input type="text" class="form-control"
                   v-model="botUserForm.country"
                   placeholder="Россия">
        </div>


        <div class="form-group mb-2">
            <label for="">Город</label>
            <input type="text" class="form-control"
                   v-model="botUserForm.city"
                   placeholder="Краснодар">
        </div>
        <div class="row mb-0">
            <p class="col-12 mb-0">Администратор</p>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_admin = true"
                    v-bind:class="{'bg-blue1-dark text-white':botUserForm.is_admin,'bg-theme border-green1-dark color-green1-dark':!botUserForm.is_admin}"
                    class="w-100 btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900">
                    <i class="fa-solid fa-check mr-1"></i> Да
                </button>
            </div>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_admin = false"
                    v-bind:class="{'bg-blue1-dark text-white':!botUserForm.is_admin,'bg-theme border-green1-dark color-green1-dark':botUserForm.is_admin}"
                    class="w-100 btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900">
                    <i class="fa-solid fa-xmark mr-1"></i> Нет
                </button>
            </div>
        </div>

        <div class="row mb-0">
            <p class="col-12 mb-0">VIP</p>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_vip = true"
                    v-bind:class="{'bg-blue1-dark text-white':botUserForm.is_vip,'bg-theme border-green1-dark color-green1-dark':!botUserForm.is_vip}"
                    class="w-100 btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900">
                    <i class="fa-solid fa-check mr-1"></i> Да
                </button>
            </div>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_vip = false"
                    v-bind:class="{'bg-blue1-dark text-white':!botUserForm.is_vip,'bg-theme border-green1-dark color-green1-dark':botUserForm.is_vip}"
                    class="w-100 btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900">
                    <i class="fa-solid fa-xmark mr-1"></i> Нет
                </button>
            </div>
        </div>

        <div class="row mb-0">
            <p class="col-12 mb-0">Работает</p>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_work = true"
                    v-bind:class="{'bg-blue1-dark text-white':botUserForm.is_work,'bg-theme border-green1-dark color-green1-dark':!botUserForm.is_work}"
                    class="w-100 btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900">
                    <i class="fa-solid fa-check mr-1"></i> Да
                </button>
            </div>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.is_work = false"
                    v-bind:class="{'bg-blue1-dark text-white':!botUserForm.is_work,'bg-theme border-green1-dark color-green1-dark':botUserForm.is_work}"
                    class="w-100 btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900">
                    <i class="fa-solid fa-xmark mr-1"></i> Нет
                </button>
            </div>
        </div>

        <div class="row mb-0">
            <p class="col-12 mb-0">В заведении</p>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.user_in_location = true"
                    v-bind:class="{'bg-blue1-dark text-white':botUserForm.user_in_location,'bg-theme border-green1-dark color-green1-dark':!botUserForm.user_in_location}"
                    class="w-100 btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900">
                    <i class="fa-solid fa-check mr-1"></i> Да
                </button>
            </div>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.user_in_location = false"
                    v-bind:class="{'bg-blue1-dark text-white':!botUserForm.user_in_location,'bg-theme border-green1-dark color-green1-dark':botUserForm.user_in_location}"
                    class="w-100 btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900">
                    <i class="fa-solid fa-xmark mr-1"></i> Нет
                </button>
            </div>
        </div>

        <div class="row mb-0">
            <p class="col-12 mb-0">Пол</p>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.sex = true"
                    v-bind:class="{'bg-blue1-dark text-white':botUserForm.sex,'bg-theme border-green1-dark color-green1-dark':!botUserForm.sex}"
                    class="w-100 btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900">
                    <i class="fa-solid fa-mars mr-1"></i> Муж
                </button>
            </div>
            <div class="col-6">
                <button
                    type="button"
                    @click="botUserForm.sex = false"
                    v-bind:class="{'bg-blue1-dark text-white':!botUserForm.sex,'bg-theme border-green1-dark color-green1-dark':botUserForm.sex}"
                    class="w-100 btn btn-border btn-m btn-full mb-3 rounded-sm text-uppercase font-900">
                    <i class="fa-solid fa-venus mr-1"></i> Жен
                </button>
            </div>
        </div>

        <button type="submit"
                class="btn btn-m btn-full mb-1 rounded-s text-uppercase font-900 shadow-s bg-green2-dark w-100">
            Сохранить
        </button>
        <div class="divider divider-small my-3 bg-highlight "></div>
    </form>

</template>
<script>
export default {
    props: ["botUser"],
    data() {
        return {
            isEdit: false,
            botUserForm: {
                id:null,
                is_vip: false,
                is_admin: false,
                is_work: false,
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
            }
        }
    },
    mounted() {
        if (this.botUser)
            this.$nextTick(() => {
                this.botUserForm = {
                    id: this.botUser.id || null,
                    is_vip: this.botUser.is_vip || false,
                    is_admin: this.botUser.is_admin || false,
                    is_work: this.botUser.is_work || false,
                    user_in_location:this.botUser.user_in_location || false,
                    name: this.botUser.name || null,
                    phone: this.botUser.phone || null,
                    email: this.botUser.email || null,
                    birthday: this.botUser.birthday || null,
                    city: this.botUser.city || null,
                    country: this.botUser.country || null,
                    address: this.botUser.address || null,
                    sex: this.botUser.sex || false,
                }
            })
    },
    methods: {
        submit() {
            this.$store.dispatch('updateBotUser',{
                botUserForm: this.botUserForm
            }).then(()=>{
                this.isEdit = false
                this.botUserForm = {
                    id:null,
                    is_vip: false,
                    is_admin: false,
                    is_work: false,
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
                }

                this.$emit("update")
                this.$botNotification.notification("Редактирование данных", "Данные успешно обновлены!")
            })
        }
    }
}
</script>
