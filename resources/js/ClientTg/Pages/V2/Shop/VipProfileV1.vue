<template>
    <div class="container py-3" v-if="botUser"><!---->

        <div class="row">
            <div class="col-12" v-if="!botUser.is_vip&&settings.display_type==0">
                <form
                    v-on:submit.prevent="submit" class="row mb-2">
                    <div class="col-12 d-flex justify-content-center mb-3">
                        <div class="img-avatar">
                            <img

                                v-lazy="'/images-by-bot-id/'+currentBot.id+'/'+currentBot.image">
                        </div>

                    </div>
                    <div class="col-12">
                        <p class="mb-3"><em>Приветствую Вас, <strong>Дорогой друг!</strong> Я хочу поздравить Вас и дать
                            возможность получать неограниченные преимущества нашего сервиса! Для начала нам нужно с Вами
                            познакомиться - это поможет сделать использование сервиса более комфортным и
                            взаимовыгодным!</em>
                        </p>
                        <h6 class="text-center my-3">Как мне к Вам обращаться?</h6>
                        <div class="form-floating">

                            <input type="text" class="form-control text-center"
                                   placeholder="Петров Петр Семенович"
                                   aria-label="vipForm-name"
                                   v-model="vipForm.name"
                                   aria-describedby="vipForm-name" required>
                            <label for="vipForm-name">Ф.И.О.</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <p class="mb-3" v-if="vipForm.name"><em>- Отлично, <strong>{{ vipForm.name }}</strong>! А теперь,
                            чтобы Вы могли
                            пользоваться всеми моими функциями, мне нужен Ваш номер телефона. Можете ввести его?</em>
                        </p>
                        <h6 class="text-center my-3">Введите свой номер телефона</h6>
                        <div class="form-floating">
                            <input type="text" class="form-control text-center"
                                   v-mask="'+7(###)###-##-##'"
                                   v-model="vipForm.phone"
                                   placeholder="+7(000)000-00-00"
                                   aria-label="vipForm-phone" aria-describedby="vipForm-phone" required>
                            <label for="vipForm-phone">Номер телефона</label>
                        </div>

                    </div>

                    <div class="col-12" v-if="settings.need_sex">
                        <p class="mb-3"><em>Чтобы я мог обращаться к Вам правильно, скажи мне, какого Вы пола?</em></p>
                        <h6 class="text-center my-3">Вы мужчина или женщина?</h6>

                        <div class="row mb-2">
                            <div class="col-6">
                                <div
                                    v-bind:class="{'bg-primary':vipForm.sex}"
                                    @click="vipForm.sex = true"
                                    class="btn p-2 w-100 btn-outline-primary d-flex justify-content-between flex-column align-items-center ">
                                    <i class="fa-solid fa-mars font-28"></i>
                                    <span class="text-center text-uppercase my-2">Мужчина</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    v-bind:class="{'bg-highlight ':!vipForm.sex}"
                                    @click="vipForm.sex = false"
                                    class="btn p-2 w-100 btn-outline-primary d-flex justify-content-between flex-column align-items-center ">
                                    <i class="fa-solid fa-mars font-28"></i>
                                    <span class="text-center text-uppercase my-2">Женщина</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12" v-if="settings.need_birthday">
                        <p class="mb-3"><em>Для того, чтобы я мог поздравлять Вас с днем рождения и сделать Вам приятно, мне
                            нужно знать, когда он у Вас</em></p>
                        <h6 class="text-center my-3">Введите свой день рождения</h6>
                        <div class="form-floating">
                            <input type="date" class="form-control text-center"
                                   v-model="vipForm.birthday"
                                   aria-label="vipForm-birthday" aria-describedby="vipForm-birthday" required>
                            <label for="vipForm-phone">Дата рождения</label>
                        </div>
                    </div>

                    <div class="col-12" v-if="settings.need_city">
                        <p class="mb-3"><em>Чтобы я мог показывать Вам информацию, актуальную для Вашего города, мне нужно
                            знать
                            город Вашего проживания.</em></p>
                        <h6 class="text-center my-3">Какой у Вас город?</h6>
                        <div class="form-floating">
                            <input type="text"
                                   v-model="vipForm.city"
                                   class="form-control text-center"
                                   placeholder="Краснодар"
                                   aria-label="vipForm-city" aria-describedby="vipForm-city" required>
                            <label for="vipForm-city">Город проживания</label>
                        </div>
                    </div>
                    <!-- -->
                    <div class="col-12"

                         v-for="(field, index) in vipForm.fields"
                    >
                        <div v-if="settings[field.key]">
                            <h6 class="text-center my-3">{{ field.description }}</h6>
                            <div class="form-floating" v-if="field.type===0||field.type===1">
                                <input :type="field.type===1?'number':'text'"
                                       v-model="vipForm.fields[index].value"
                                       class="form-control text-center"
                                       :placeholder="field.title"
                                       :pattern="vipForm.fields[index].pattern||''"
                                       aria-label="vipForm-city" aria-describedby="vipForm-city"
                                       :required="vipForm.fields[index].required||false"
                                >
                                <label :for="'vipForm-field'+index">{{field.title}}</label>
                            </div>

                            <div class="row mb-2" v-if="field.type===2">
                                <div class="col-6">
                                    <div
                                        v-bind:class="{'bg-primary':vipForm.fields[index].value}"
                                        @click="vipForm.fields[index].value = true"
                                        class="btn p-2 w-100 btn-outline-primary d-flex justify-content-between flex-column align-items-center ">
                                        <i class="fa-solid fa-check font-28"></i>
                                        <span class="text-center text-uppercase my-2">Да</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div
                                        v-bind:class="{'bg-primary':!vipForm.fields[index].value}"
                                        @click="vipForm.fields[index].value = false"
                                        class="btn p-2 w-100 btn-outline-primary d-flex justify-content-between flex-column align-items-center ">
                                        <i class="fa-solid fa-xmark font-28"></i>
                                        <span class="text-center text-uppercase my-2">Нет</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <p class="mb-3"><em>Отлично! Теперь, прежде чем закончить, пожалуйста, прочитайте условия
                            использования и дайте свое согласие на их принятие.</em></p>

                        <p>Перед отправкой данных нужно ознакомиться с <a
                            href="#">политикой конфиденциальности</a>.</p>

                        <div class="d-flex mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       v-model="confirm"
                                       type="checkbox" role="switch" id="toggle-id-1">
                                <label class="form-check-label" for="toggle-id-1">
                                    <span v-if="!vipForm.sex">С правилами ознакомилась</span>
                                    <span v-if="vipForm.sex">С правилами ознакомлен</span>
                                </label>
                            </div>
                        </div>


                        <button type="submit"
                                :disabled="!confirm||load"
                                class="btn btn-primary p-3 w-100">
                            Отправить анкету
                        </button>

                    </div>
                </form>
            </div>
            <div class="col-12" v-if="!botUser.is_vip&&settings.display_type==1">
                <form
                    v-if="step===0"
                    v-on:submit.prevent="nextStep" class="row mb-2">
                    <div class="col-12 d-flex justify-content-center mb-3">
                        <div class="img-avatar">
                            <img

                                v-lazy="'/images-by-bot-id/'+currentBot.id+'/'+currentBot.image">
                        </div>

                    </div>
                    <div class="col-12">
                        <p class="mb-3"><em>Приветствую Вас, <strong>Дорогой друг!</strong> Я хочу поздравить Вас и дать
                            возможность получать неограниченные преимушества нашего сервиса! Для начала нам нужно с Вами
                            познакомиться - это поможет сделать использование сервиса более комфортным и
                            взаимовыгодным!</em>
                        </p>
                        <h6 class="text-center my-3">Как мне к Вам обращаться?</h6>
                        <div class="form-floating">

                            <input type="text" class="form-control text-center"
                                   placeholder="Петров Петр Семенович"
                                   aria-label="vipForm-name"
                                   v-model="vipForm.name"
                                   aria-describedby="vipForm-name" required>
                        </div>

                        <button
                            class="btn btn-outline-primary p-3 w-100">
                            Следующий шаг
                        </button>

                    </div>
                </form>

                <form
                    v-if="step===1"
                    v-on:submit.prevent="nextStep" class="row mb-2">
                    <div class="col-12 d-flex justify-content-center mb-3">
                        <div class="img-avatar">
                            <img

                                v-lazy="'/images-by-bot-id/'+currentBot.id+'/'+currentBot.image">
                        </div>

                    </div>
                    <div class="col-12">
                        <p class="mb-3"><em>- Отлично, <strong>{{ vipForm.name }}</strong>! А теперь, чтобы Вы могли
                            пользоваться всеми моими функциями, мне нужен Ваш номер телефона. Можете ввести его?</em>
                        </p>
                        <h6 class="text-center my-3">Введите свой номер телефона</h6>
                        <div class="form-floating">
                            <input type="text" class="form-control text-center"
                                   v-mask="'+7(###)###-##-##'"
                                   v-model="vipForm.phone"
                                   placeholder="+7(000)000-00-00"
                                   aria-label="vipForm-phone" aria-describedby="vipForm-phone" required>
                            <label for="vipForm-phone">Номер телефона</label>
                        </div>

                        <button
                            class="btn btn-outline-primary p-3 w-100">
                            Следующий шаг
                        </button>

                    </div>
                </form>

                <form
                    v-if="step===2"
                    v-on:submit.prevent="nextStep" class="row mb-2">
                    <div class="col-12 d-flex justify-content-center mb-3">
                        <div class="img-avatar">

                            <img

                                v-lazy="'/images-by-bot-id/'+currentBot.id+'/'+currentBot.image">
                        </div>

                    </div>
                    <div class="col-12">
                        <p class="mb-3"><em>Чтобы я мог обращаться к Вам правильно, скажи мне, какого Вы пола?</em></p>
                        <h6 class="text-center my-3">Ты мужчина или женщина?</h6>

                        <div class="row mb-2">
                            <div class="col-6">
                                <div
                                    v-bind:class="{'bg-highlight':vipForm.sex}"
                                    @click="vipForm.sex = true"
                                    class="btn btn-border btn-m btn-full border-highlight rounded-s shadow-s w-100 p-3 d-flex justify-content-between flex-column align-items-center ">
                                    <i class="fa-solid fa-mars font-28"></i>
                                    <span class="text-center text-uppercase my-2">Мужчина</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div
                                    v-bind:class="{'bg-highlight ':!vipForm.sex}"
                                    @click="vipForm.sex = false"
                                    class="btn btn-border btn-m btn-full border-highlight rounded-s  shadow-s w-100 p-3 d-flex justify-content-between flex-column align-items-center ">
                                    <i class="fa-solid fa-mars font-28"></i>
                                    <span class="text-center text-uppercase my-2">Женщина</span>
                                </div>
                            </div>
                        </div>


                        <button
                            class="btn btn-outline-primary p-3 w-100">
                            Следующий шаг
                        </button>

                    </div>
                </form>

                <!--            <form
                                v-if="step===3"
                                v-on:submit.prevent="nextStep" class="row mb-2">
                                <div class="col-12 d-flex justify-content-center mb-3">
                                    <div class="img-avatar">
                                        <img
                                            v-lazy="'https://your-cashman.com/Image/IconSmall.png'"
                                            class="img-avatar"/>
                                    </div>

                                </div>
                                <div class="col-12">
                                    <p class="mb-3"><em>Для того, чтобы я мог поздравлять Вас с днем рождения и сделать Вам приятно, мне
                                        нужно знать, когда он у Вас</em></p>
                                    <h6 class="text-center my-3">Введите свой день рождения</h6>
                                    <div class="form-floating">
                                        <input type="date" class="form-control text-center"
                                               v-model="vipForm.birthday"
                                               aria-label="vipForm-birthday" aria-describedby="vipForm-birthday" required>
                                    </div>

                                    <button
                                        class="btn btn-m btn-full mb-2 rounded-s text-uppercase font-900 shadow-s bg-highlight w-100">
                                        Следующий шаг
                                    </button>

                                </div>
                            </form>-->

                <form
                    v-if="step===3"
                    v-on:submit.prevent="nextStep" class="row mb-2">
                    <div class="col-12 d-flex justify-content-center mb-3">
                        <div class="img-avatar">

                            <img

                                v-lazy="'/images-by-bot-id/'+currentBot.id+'/'+currentBot.image">
                        </div>

                    </div>
                    <div class="col-12">
                        <p class="mb-3"><em>Чтобы я мог показывать Вам информацию, актуальную для Вашего города, мне нужно
                            знать
                            город Вашего проживания.</em></p>
                        <h6 class="text-center my-3">Какой у Вас город?</h6>
                        <div class="form-floating">
                            <input type="text"
                                   v-model="vipForm.city"
                                   class="form-control text-center"
                                   placeholder="Краснодар"
                                   id="vipForm-city"
                                   aria-label="vipForm-city" aria-describedby="vipForm-city" required>
                            <label for="vipForm-city">Город проживания</label>
                        </div>

                        <button
                            class="btn btn-outline-primary p-3 w-100">
                            Следующий шаг
                        </button>

                    </div>
                </form>

                <form
                    v-if="step===4"
                    v-on:submit.prevent="submit" class="row mb-2">
                    <div class="col-12 d-flex justify-content-center mb-3">
                        <div class="img-avatar">
                            <img
                                v-lazy="'/images-by-bot-id/'+currentBot.id+'/'+currentBot.image">
                        </div>

                    </div>
                    <div class="col-12">
                        <p class="mb-3"><em>Отлично! Теперь, прежде чем продолжить, пожалуйста, прочитайте мои условия
                            использования и дайте свое согласие на их принятие.</em></p>
                        <h6 class="text-center my-3">Последний шаг</h6>


                        <p>Перед отправкой данных нужно ознакомиться с <a
                            href="#">политикой конфиденциальности</a>.</p>


                        <div class="d-flex mb-3">
                            <div class="pt-1">
                                <h5 data-activate="toggle-id-1" class="font-500 font-13">
                                    <span v-if="!vipForm.sex">С правилами озакномилась</span>
                                    <span v-if="vipForm.sex">С правилами ознакомлен</span>
                                </h5>
                            </div>
                            <div class="ml-auto mr-4 pr-2">
                                <div class="custom-control ios-switch">
                                    <input
                                        v-model="confirm"
                                        type="checkbox" class="ios-input" id="toggle-id-1">
                                    <label class="custom-control-label" for="toggle-id-1"></label>
                                </div>
                            </div>
                        </div>


                        <button type="submit"
                                :disabled="!confirm||load"
                                class="btn btn-outline-primary p-3 w-100">
                            Отправить анкету
                        </button>

                        <button type="button"
                                @click="step=0"
                                class="btn btn-outline-primary p-3 w-100 mb-2">
                            Исправить ошибки
                        </button>

                    </div>
                </form>

            </div>
            <div class="col-12" v-if="botUser.is_vip">
                <div class="card" >
                    <div class="card-body">
                        Поздравляем! Вы являетесь нашим VIP-пользователем! Вам доступны следующие возможности:
                        <ul>
                            <li>Накопление CashBack за покупки</li>
                            <li>Оплата товаров через CashBak</li>
                            <li>Реферальная программа</li>
                            <li>
                                <h6>Ваши данные:</h6>
                                <p class="mb-0">Имя: {{ botUser.name || 'Не указано' }}</p>
                                <p class="mb-0">Телефон: {{ botUser.phone || 'Не указано' }}</p>
                                <p class="mb-0">Город: {{ botUser.city || 'Не указано' }}</p>
                                <!--                    <p class="mb-0">Дата рождения: {{ botUser.birthday || 'Не указано' }}</p>-->
                                <p class="mb-0">Пол: {{ botUser.sex ? 'Мужской' : 'Женский' }}</p>
                                <p class="mb-0" v-for="field in vipForm.fields">
                                    {{ field.title }}:

                                    <span v-if="field.config.type===2">
                            {{ field.value === false ? "Нет" : "Да" }}
                        </span>

                                    <span v-if="field.config.type===0||field.config.type===1">
                            {{ field.value }}
                        </span>
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            settings: {
                display_type: 0,
                need_birthday: true,
                need_age: true,
                need_city: true,
                need_sex: true,
            },
            load: false,
            confirm: false,
            step: 0,
            botUser: null,
            vipForm: {
                name: null,
                phone: null,
                email: null,
                birthday: null,
                city: null,
                country: null,
                address: null,
                sex: true,
                fields: []

            }
        }
    },
    watch: {
        'getSelf': function () {
            this.botUser = this.getSelf

            this.vipForm = {
                name: this.botUser.name || this.botUser.fio_from_telegram || null,
                phone: this.botUser.phone || null,
                email: this.botUser.email || null,
                birthday: this.botUser.birthday || null,
                city: this.botUser.city || null,
                country: this.botUser.country || null,
                address: this.botUser.address || null,
                sex: this.botUser.sex || true,
                fields: [],
            }

            this.loadCurrentBotFields();

        },
    },
    mounted() {

        this.$nextTick(()=>{
            this.botUser = this.getSelf

            this.vipForm = {
                name: this.botUser.name || this.botUser.fio_from_telegram || null,
                phone: this.botUser.phone || null,
                email: this.botUser.email || null,
                birthday: this.botUser.birthday || null,
                city: this.botUser.city || null,
                country: this.botUser.country || null,
                address: this.botUser.address || null,
                sex: this.botUser.sex || true,
            }


        })



        this.loadCashBackModuleData();
    },

    computed: {
        ...mapGetters(['getSelf']),
        tg() {
            return window.Telegram.WebApp;
        },

        currentBot() {
            return window.currentBot
        }
    },
    methods: {
        getUserFieldValue(id) {
            const result = {
                value: null
            }

            if (!this.botUser.fields)
                return result

            return this.botUser.fields.find(item => item.bot_custom_field_setting_id === id) || result
        },
        loadCurrentBotFields() {
            return this.$store.dispatch("loadCurrentBotFields")
                .then((response) => {

                    let fields = response.data || []

                    fields.forEach(item => {

                        if (item.is_active) {

                            let field = this.getUserFieldValue(item.id)
                            let value = field.value;
                            let config = field.config;

                            this.vipForm.fields.push({
                                id: item.id,
                                title: item.label,
                                description: item.description,
                                key: item.key,
                                value: item.type === 2 ? (value === "1") : value,
                                type: item.type,
                                pattern: item.pattern,
                                required: item.required,
                                config: config
                            })


                        }
                    })


                })
        },
        nextStep() {
            this.step++;
        },

        loadCashBackModuleData() {
            this.loading = true;
            this.$store.dispatch("loadCashBackModuleData").then((resp) => {
                this.loading = false

                this.$nextTick(() => {
                    Object.keys(resp).forEach(item => {
                        this.settings[item] = resp[item]
                    })
                })

            }).catch(() => {
                this.loading = false
            })
        },

        submit() {
            this.loading = true;

            this.$store.dispatch("saveVip", {
                ...this.vipForm
            }).then((resp) => {
                this.loading = false

                this.$notify({
                    title: 'Отлично!',
                    text: "Вы успешно заполнили форму и стали наши VIP-пользователем!",
                    type: "success"
                })

                this.tg.close()



            }).catch(() => {
                this.loading = false

                this.$notify({
                    title: 'Упс!',
                    text: "Ошибка заполнения формы!",
                    type: "error"
                })
            })
        }
    }
}
</script>
<style lang="scss" scoped>
.img-avatar {
    width: 200px;
    height: 200px;
    padding: 10px;

    img {
        object-fit: cover;
        width: 100%;
    }

}

.theme-dark {
    input {
        border-color: white;
    }
}
</style>
