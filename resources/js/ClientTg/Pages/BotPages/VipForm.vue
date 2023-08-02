<script setup>
defineProps({
    bot: {
        type: Object,
    },



});
</script>
<template>
    <div v-if="botUser">

    </div>
    <div class="container pt-3 pb-3" v-if="!botUser.is_vip">
        <form
            v-if="step===0"
            v-on:submit.prevent="nextStep" class="row">
            <div class="col-12 d-flex justify-content-center mb-3">
                <div class="img-avatar">
                    <img
                        v-lazy="'https://your-cashman.com/Image/IconSmall.png'"
                        class="img-avatar"/>
                </div>

            </div>
            <div class="col-12">
                <p class="mb-3"><em>Приветствую вас, <strong>Дорогой друг!</strong> Я хочу поздавить тебя и дать возможность получать неограниченные преимушества нашего сервиса! Для начала нам нужно с тобой познакомится - это поможет сделать использование сервиса более кофортным и взаимовыгодным!</em>
                </p>
                <h6 class="text-center">Как мне к Вам обращаться?</h6>
                <div class="input-group mb-3">

                    <input type="text" class="form-control text-center p-3"
                           placeholder="Петров Петр Семенович"
                           aria-label="vipForm-name"
                           v-model="vipForm.name"
                           aria-describedby="vipForm-name" required>
                </div>

                <button class="btn btn-outline-primary p-3 w-100">
                    Следующий шаг
                </button>

            </div>
        </form>

        <form
            v-if="step===1"
            v-on:submit.prevent="nextStep" class="row">
            <div class="col-12 d-flex justify-content-center mb-3">
                <div class="img-avatar">
                    <img
                        v-lazy="'https://your-cashman.com/Image/IconSmall.png'"
                        class="img-avatar"/>
                </div>

            </div>
            <div class="col-12">
                <p class="mb-3"><em>- Отлично, <strong>{{vipForm.name}}</strong>! А теперь, чтобы Вы могли пользоваться всеми моими функциями, мне нужен Ваш номер телефона. Можете ввести его?</em>
                </p>
                <h6 class="text-center">Введите свой номер телефона</h6>
                <div class="input-group mb-3">
                        <input type="text" class="form-control p-3 text-center"
                               v-mask="'+7(###)###-##-##'"
                               v-model="vipForm.phone"
                               placeholder="+7(000)000-00-00"
                               aria-label="vipForm-phone" aria-describedby="vipForm-phone" required>

                </div>

                <button class="btn btn-outline-primary p-3 w-100">
                    Следующий шаг
                </button>

            </div>
        </form>

        <form
            v-if="step===2"
            v-on:submit.prevent="nextStep" class="row">
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
                <div class="input-group mb-3 mt-3 w-100 d-flex justify-content-center">
                    <div class="btn-group w-100" role="group" aria-label="vipForm-sex">
                        <input
                            type="radio"
                            :value="true"
                            v-model="vipForm.sex"
                            class="btn-check" name="sex-radio-btn" id="sex-radio-btn-1" autocomplete="off" required>
                        <label class="btn btn-outline-primary p-3" for="sex-radio-btn-1">Мужчина</label>

                        <input type="radio"
                               v-model="vipForm.sex"
                               :value="false"
                               class="btn-check" name="sex-radio-btn" id="sex-radio-btn-2" autocomplete="off" required>
                        <label class="btn btn-outline-primary p-3" for="sex-radio-btn-2">Женщина</label>

                    </div>
                </div>

                <button class="btn btn-outline-primary p-3 w-100">
                    Следующий шаг
                </button>

            </div>
        </form>

        <form
            v-if="step===3"
            v-on:submit.prevent="nextStep" class="row">
            <div class="col-12 d-flex justify-content-center mb-3">
                <div class="img-avatar">
                    <img
                        v-lazy="'https://your-cashman.com/Image/IconSmall.png'"
                        class="img-avatar"/>
                </div>

            </div>
            <div class="col-12">
                <p class="mb-3"><em>Для того, чтобы я мог поздравлять Вас с днем рождения и сделать Вам приятно, мне нужно знать, когда он у Вас</em></p>
                <h6 class="text-center">Введите свой день рождения</h6>
                <div class="input-group mb-3">
                    <input type="date" class="form-control p-3"
                           v-model="vipForm.birthday"
                           aria-label="vipForm-birthday" aria-describedby="vipForm-birthday" required>
                </div>

                <button class="btn btn-outline-primary p-3 w-100">
                    Следующий шаг
                </button>

            </div>
        </form>

        <form
            v-if="step===4"
            v-on:submit.prevent="nextStep" class="row">
            <div class="col-12 d-flex justify-content-center mb-3">
                <div class="img-avatar">
                    <img
                        v-lazy="'https://your-cashman.com/Image/IconSmall.png'"
                        class="img-avatar"/>
                </div>

            </div>
            <div class="col-12">
                <p class="mb-3"><em>Чтобы я мог показывать Вам информацию, актуальную для Вашего города, мне нужно знать город Вашего проживания.</em></p>
                <h6 class="text-center">Какой у Вас город?</h6>
                <div class="input-group mb-3">
                    <input type="text"
                           v-model="vipForm.city"
                           list="datalistCityOptions"
                           class="form-control p-3" placeholder="Краснодар"
                           aria-label="vipForm-city" aria-describedby="vipForm-city" required>
                    <datalist id="datalistCityOptions">
                        <option value="Краснодар"/>
                        <option value="Ростов-на-Дону"/>
                        <option value="Таганрог"/>
                        <option value="Донецк"/>
                        <option value="Москва"/>
                    </datalist>
                </div>

                <button class="btn btn-outline-primary p-3 w-100">
                    Следующий шаг
                </button>

            </div>
        </form>

        <form
            v-if="step===5"
            v-on:submit.prevent="submit" class="row">
            <div class="col-12 d-flex justify-content-center mb-3">
                <div class="img-avatar">
                    <img
                        v-lazy="'https://your-cashman.com/Image/IconSmall.png'"
                        class="img-avatar"/>
                </div>

            </div>
            <div class="col-12">
                <p class="mb-3"><em>Отлично! Теперь, прежде чем продолжить, пожалуйста, прочитайте мои условия использования и дайте свое согласие на их принятие.</em> </p>
                <h6 class="text-center">Последний шаг</h6>

                <div class="card border-success mb-3">
                    <div class="card-body">
                        <p>Перед отправкой данных нужно ознакомиться с <a
                            href="#">политикой конфиденциальности</a>.</p>
                        <div class="form-check form-switch ">
                            <input class="form-check-input"
                                   v-model="confirm"
                                   type="checkbox" role="switch" id="confirm">
                            <label class="form-check-label" for="confirm">
                                <span v-if="!vipForm.sex">С правилами озакномилась</span>
                                <span v-if="vipForm.sex">С правилами ознакомлен</span>
                            </label>
                        </div>
                    </div>
                </div>
                <button type="button"
                        @click="step=0"
                        class="btn btn-outline-primary w-100 p-3 mb-3">Исправить ошибки
                </button>
                <button type="submit"
                        :disabled="!confirm||load"
                        class="btn btn-outline-success w-100 p-3">Отправить анкету
                </button>

            </div>
        </form>
    </div>
    <div class="container pt-3 pb-3" v-else>
        <div class="row">
            <div class="col-12">
                <div class="card border-success">
                    <div class="card-body">
                        Поздравляем! Вы являетесь нашим VIP-пользователеме! Вам доступны следующие возможности:
                        <ul>
                            <li>Накопление CashBack за покупки</li>
                            <li>Оплата товаров через CashBak</li>
                            <li>Реферальная программа</li>
                        </ul>

                        <button type="button"
                               @click="tg.close()"
                                class="btn btn-outline-success w-100 p-3">Вернуться в бота
                        </button>
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
    mounted() {
        if (this.tgUser) {
            this.loadBotUser()
        }
    },

    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
        tgUser() {
            const urlParams = new URLSearchParams(this.tg.initData);
            return JSON.parse(urlParams.get('user'));
        }
    },
    methods: {
        nextStep(){
          this.step++;
        },
        loadBotUser(){
            this.loading = true;
            this.$store.dispatch("loadCurrentBotUser", {
                dataObject: {
                    bot_id: this.bot.id,
                    tg: this.tgUser,
                }
            }).then((resp) => {
                this.loading = false
                this.botUser = resp
            }).catch(() => {
                this.loading = false
            })
        },
        submit() {
            this.loading = true;
            this.$store.dispatch("saveVip", {
                dataObject: {
                    bot_id: this.bot.id,
                    tg: this.tgUser,
                    form: this.vipForm
                }
            }).then((resp) => {
                this.loading = false
                window.location.reload()
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
<style lang="scss">
.img-avatar {
    width: 200px;
    height: 200px;
    padding: 10px;
    img {
        object-fit: cover;
        width: 100%;
    }

}
</style>
