<script setup>
import CallbackForm from "ClientTg@/Components/Shop/CallbackForm.vue";
import PlayerForm from "ClientTg@/Components/Shop/PlayerForm.vue";
import ReturnToBot from "ClientTg@/Components/Shop/Helpers/ReturnToBot.vue";
import ProjectInfoCard from "ClientTg@/Components/Shop/Helpers/ProjectInfoCard.vue";
</script>
<template>
    <div v-if="botUser">
        <div class="card card-style p-3" v-if="!botUser.is_vip&&settings.display_type==0">
            <form
                v-on:submit.prevent="submit" class="row mb-0">
                <div class="col-12 d-flex justify-content-center mb-3">
                    <div class="img-avatar">
                        <img
                            v-lazy="'https://your-cashman.com/Image/IconSmall.png'"
                            class="img-avatar"/>
                    </div>

                </div>
                <div class="col-12">
                    <p class="mb-3"><em>Приветствую вас, <strong>Дорогой друг!</strong> Я хочу поздавить тебя и дать
                        возможность получать неограниченные преимушества нашего сервиса! Для начала нам нужно с Вами
                        познакомится - это поможет сделать использование сервиса более кофортным и взаимовыгодным!</em>
                    </p>
                    <h6 class="text-center">Как мне к Вам обращаться?</h6>
                    <div class="input-style input-style-2">

                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               placeholder="Петров Петр Семенович"
                               aria-label="vipForm-name"
                               v-model="vipForm.name"
                               aria-describedby="vipForm-name" required>
                    </div>
                </div>

                <div class="col-12">
                    <p class="mb-3" v-if="vipForm.name"><em>- Отлично, <strong>{{ vipForm.name }}</strong>! А теперь,
                        чтобы Вы могли
                        пользоваться всеми моими функциями, мне нужен Ваш номер телефона. Можете ввести его?</em>
                    </p>
                    <h6 class="text-center">Введите свой номер телефона</h6>
                    <div class="input-style input-style-2">
                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-mask="'+7(###)###-##-##'"
                               v-model="vipForm.phone"
                               placeholder="+7(000)000-00-00"
                               aria-label="vipForm-phone" aria-describedby="vipForm-phone" required>

                    </div>

                </div>

                <div class="col-12">
                    <p class="mb-3"><em>Чтобы я мог обращаться к Вам правильно, скажи мне, какого Вы пол?</em></p>
                    <h6 class="text-center">Вы мужчина или женщина?</h6>

                    <div class="row mb-0">
                        <div class="col-6 p-3">
                            <div
                                v-bind:class="{'bg-highlight':vipForm.sex}"
                                @click="vipForm.sex = true"
                                class="btn btn-border btn-m btn-full border-highlight rounded-s shadow-s w-100 p-3 d-flex justify-content-between flex-column align-items-center ">
                                <i class="fa-solid fa-mars font-28"></i>
                                <span class="text-center text-uppercase my-2">Мужчина</span>
                            </div>
                        </div>
                        <div class="col-6 p-3">
                            <div
                                v-bind:class="{'bg-highlight ':!vipForm.sex}"
                                @click="vipForm.sex = false"
                                class="btn btn-border btn-m btn-full border-highlight rounded-s  shadow-s w-100 p-3 d-flex justify-content-between flex-column align-items-center ">
                                <i class="fa-solid fa-mars font-28"></i>
                                <span class="text-center text-uppercase my-2">Женщина</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12" v-if="settings.need_birthday">
                    <p class="mb-3"><em>Для того, чтобы я мог поздравлять Вас с днем рождения и сделать Вам приятно, мне
                        нужно знать, когда он у Вас</em></p>
                    <h6 class="text-center">Введите свой день рождения</h6>
                    <div class="input-style input-style-2">
                        <input type="date" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-model="vipForm.birthday"
                               aria-label="vipForm-birthday" aria-describedby="vipForm-birthday" required>
                    </div>
                </div>

                <div class="col-12" v-if="settings.need_city">
                    <p class="mb-3"><em>Чтобы я мог показывать Вам информацию, актуальную для Вашего города, мне нужно
                        знать
                        город Вашего проживания.</em></p>
                    <h6 class="text-center">Какой у Вас город?</h6>
                    <div class="input-style input-style-2">
                        <input type="text"
                               v-model="vipForm.city"
                               list="datalistCityOptions"
                               class="form-control text-center font-14 p-3 rounded-s border-theme"
                               placeholder="Краснодар"
                               aria-label="vipForm-city" aria-describedby="vipForm-city" required>
                        <datalist id="datalistCityOptions">
                            <option value="Краснодар"/>
                            <option value="Ростов-на-Дону"/>
                            <option value="Таганрог"/>
                            <option value="Донецк"/>
                            <option value="Москва"/>
                        </datalist>
                    </div>
                </div>

                <div class="col-12">
                    <p class="mb-3"><em>Отлично! Теперь, прежде чем закончить, пожалуйста, прочитайте условия
                        использования и дайте свое согласие на их принятие.</em></p>

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
                            class="btn btn-m btn-full mb-2 rounded-s text-uppercase font-900 shadow-s bg-highlight w-100">
                        Отправить анкету
                    </button>

                </div>
            </form>

        </div>

        <div class="card card-style p-3" v-if="!botUser.is_vip&&settings.display_type==1">
            <form
                v-if="step===0"
                v-on:submit.prevent="nextStep" class="row mb-0">
                <div class="col-12 d-flex justify-content-center mb-3">
                    <div class="img-avatar">
                        <img
                            v-lazy="'https://your-cashman.com/Image/IconSmall.png'"
                            class="img-avatar"/>
                    </div>

                </div>
                <div class="col-12">
                    <p class="mb-3"><em>Приветствую вас, <strong>Дорогой друг!</strong> Я хочу поздавить тебя и дать
                        возможность получать неограниченные преимушества нашего сервиса! Для начала нам нужно с тобой
                        познакомится - это поможет сделать использование сервиса более кофортным и взаимовыгодным!</em>
                    </p>
                    <h6 class="text-center">Как мне к Вам обращаться?</h6>
                    <div class="input-style input-style-2">

                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               placeholder="Петров Петр Семенович"
                               aria-label="vipForm-name"
                               v-model="vipForm.name"
                               aria-describedby="vipForm-name" required>
                    </div>

                    <button
                        class="btn btn-m btn-full mb-2 rounded-s text-uppercase font-900 shadow-s bg-highlight w-100">
                        Следующий шаг
                    </button>

                </div>
            </form>

            <form
                v-if="step===1"
                v-on:submit.prevent="nextStep" class="row mb-0">
                <div class="col-12 d-flex justify-content-center mb-3">
                    <div class="img-avatar">
                        <img
                            v-lazy="'https://your-cashman.com/Image/IconSmall.png'"
                            class="img-avatar"/>
                    </div>

                </div>
                <div class="col-12">
                    <p class="mb-3"><em>- Отлично, <strong>{{ vipForm.name }}</strong>! А теперь, чтобы Вы могли
                        пользоваться всеми моими функциями, мне нужен Ваш номер телефона. Можете ввести его?</em>
                    </p>
                    <h6 class="text-center">Введите свой номер телефона</h6>
                    <div class="input-style input-style-2">
                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-mask="'+7(###)###-##-##'"
                               v-model="vipForm.phone"
                               placeholder="+7(000)000-00-00"
                               aria-label="vipForm-phone" aria-describedby="vipForm-phone" required>

                    </div>

                    <button
                        class="btn btn-m btn-full mb-2 rounded-s text-uppercase font-900 shadow-s bg-highlight w-100">
                        Следующий шаг
                    </button>

                </div>
            </form>

            <form
                v-if="step===2"
                v-on:submit.prevent="nextStep" class="row mb-0">
                <div class="col-12 d-flex justify-content-center mb-3">
                    <div class="img-avatar">
                        <img
                            v-lazy="'https://your-cashman.com/Image/IconSmall.png'"
                            class="img-avatar"/>
                    </div>

                </div>
                <div class="col-12">
                    <p class="mb-3"><em>Чтобы я мог обращаться к Вам правильно, скажи мне, какого Вы пол?</em></p>
                    <h6 class="text-center">Ты мужчина или женщина?</h6>

                    <div class="row mb-0">
                        <div class="col-6 p-3">
                            <div
                                v-bind:class="{'bg-highlight':vipForm.sex}"
                                @click="vipForm.sex = true"
                                class="btn btn-border btn-m btn-full border-highlight rounded-s shadow-s w-100 p-3 d-flex justify-content-between flex-column align-items-center ">
                                <i class="fa-solid fa-mars font-28"></i>
                                <span class="text-center text-uppercase my-2">Мужчина</span>
                            </div>
                        </div>
                        <div class="col-6 p-3">
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
                        class="btn btn-m btn-full mb-2 rounded-s text-uppercase font-900 shadow-s bg-highlight w-100">
                        Следующий шаг
                    </button>

                </div>
            </form>

            <!--            <form
                            v-if="step===3"
                            v-on:submit.prevent="nextStep" class="row mb-0">
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
                                <h6 class="text-center">Введите свой день рождения</h6>
                                <div class="input-style input-style-2">
                                    <input type="date" class="form-control text-center font-14 p-3 rounded-s border-theme"
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
                v-on:submit.prevent="nextStep" class="row mb-0">
                <div class="col-12 d-flex justify-content-center mb-3">
                    <div class="img-avatar">
                        <img
                            v-lazy="'https://your-cashman.com/Image/IconSmall.png'"
                            class="img-avatar"/>
                    </div>

                </div>
                <div class="col-12">
                    <p class="mb-3"><em>Чтобы я мог показывать Вам информацию, актуальную для Вашего города, мне нужно
                        знать
                        город Вашего проживания.</em></p>
                    <h6 class="text-center">Какой у Вас город?</h6>
                    <div class="input-style input-style-2">
                        <input type="text"
                               v-model="vipForm.city"
                               list="datalistCityOptions"
                               class="form-control text-center font-14 p-3 rounded-s border-theme"
                               placeholder="Краснодар"
                               aria-label="vipForm-city" aria-describedby="vipForm-city" required>
                        <datalist id="datalistCityOptions">
                            <option value="Краснодар"/>
                            <option value="Ростов-на-Дону"/>
                            <option value="Таганрог"/>
                            <option value="Донецк"/>
                            <option value="Москва"/>
                        </datalist>
                    </div>

                    <button
                        class="btn btn-m btn-full mb-2 rounded-s text-uppercase font-900 shadow-s bg-highlight w-100">
                        Следующий шаг
                    </button>

                </div>
            </form>

            <form
                v-if="step===4"
                v-on:submit.prevent="submit" class="row mb-0">
                <div class="col-12 d-flex justify-content-center mb-3">
                    <div class="img-avatar">
                        <img
                            v-lazy="'https://your-cashman.com/Image/IconSmall.png'"
                            class="img-avatar"/>
                    </div>

                </div>
                <div class="col-12">
                    <p class="mb-3"><em>Отлично! Теперь, прежде чем продолжить, пожалуйста, прочитайте мои условия
                        использования и дайте свое согласие на их принятие.</em></p>
                    <h6 class="text-center">Последний шаг</h6>


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
                            class="btn btn-m btn-full mb-2 rounded-s text-uppercase font-900 shadow-s bg-highlight w-100">
                        Отправить анкету
                    </button>

                    <button type="button"
                            @click="step=0"
                            class="btn btn-m btn-full rounded-s text-uppercase font-900 shadow-s bg-red1-light w-100 mb-2">
                        Исправить ошибки
                    </button>

                </div>
            </form>
        </div>

        <div class="card card-style p-3" v-if="botUser.is_vip">
            Поздравляем! Вы являетесь нашим VIP-пользователеме! Вам доступны следующие возможности:
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
                </li>
            </ul>

            <ReturnToBot class="mb-2"/>
        </div>

    </div>


    <ProjectInfoCard/>
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
            }

        },
    },
    mounted() {

        /*   this.$store.dispatch("loadSelf").then(() => {
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
           })*/

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
        nextStep() {
            this.step++;
        },

        loadCashBackModuleData() {
            this.loading = true;
            this.$store.dispatch("loadCashBackModuleData").then((resp) => {
                this.loading = false

                this.settings.display_type = resp.display_type || 0
                this.settings.need_birthday = resp.need_birthday || true
                this.settings.need_age = resp.need_age || true
                this.settings.need_city = resp.need_city || true

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
                this.tg.close()
            }).catch(() => {
                this.loading = false
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
