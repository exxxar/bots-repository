
<template>
    <div class="container pt-3 pb-3">
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
                <p class="mb-3"><em>Приветствую Вас, <strong>Дорогой друг!</strong> Я хочу поздаврить Вас и дать возможность получать неограниченные преимушества нашего сервиса! Для начала нам нужно с тобой познакомиться - это поможет сделать использование сервиса более комфортным и взаимовыгодным!</em>
                </p>
                <h6 class="text-center">Как мне к Вам обращаться?</h6>
                <div class="input-group mb-3">

                    <input type="text" class="form-control text-center p-3"
                           placeholder="Петров Петр Семенович"
                           aria-label="deliveryManForm-name"
                           v-model="deliveryManForm.name"
                           aria-describedby="deliveryManForm-name" required>
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
                <p class="mb-3"><em>- Отлично, <strong>{{deliveryManForm.name}}</strong>! А теперь, чтобы ты мог пользоваться всеми моими функциями, мне нужен твой номер телефона. Можешь ввести его?</em>
                </p>
                <h6 class="text-center">Введи свой номер телефона</h6>
                <div class="input-group mb-3">
                    <input type="text" class="form-control p-3 text-center"
                           v-mask="'+7(###)###-##-##'"
                           v-model="deliveryManForm.phone"
                           placeholder="+7(000)000-00-00"
                           aria-label="deliveryManForm-phone" aria-describedby="deliveryManForm-phone" required>

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
                <p class="mb-3"><em>Чтобы я мог обращаться к Вам правильно, скажи мне, какого ты пола?</em></p>
                <h6 class="text-center">Ты парень или девушка?</h6>
                <div class="input-group mb-3 mt-3 w-100 d-flex justify-content-center">
                    <div class="btn-group w-100" role="group" aria-label="deliveryManForm-sex">
                        <input
                            type="radio"
                            v-model="deliveryManForm.sex"
                            class="btn-check" name="sex-radio-btn" id="sex-radio-btn-1" autocomplete="off" required>
                        <label class="btn btn-outline-primary p-3" for="sex-radio-btn-1">Парень</label>

                        <input type="radio"
                               v-model="deliveryManForm.sex"
                               class="btn-check" name="sex-radio-btn" id="sex-radio-btn-2" autocomplete="off" required>
                        <label class="btn btn-outline-primary p-3" for="sex-radio-btn-2">Девушка</label>

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
                <h6 class="text-center">Введи свой день рождения</h6>
                <div class="input-group mb-3">
                    <input type="date" class="form-control p-3"
                           v-model="deliveryManForm.birthday"
                           aria-label="deliveryManForm-birthday" aria-describedby="deliveryManForm-birthday" required>
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
                <p class="mb-3"><em>Чтобы я мог показывать Вам информацию, актуальную для твоего города, мне нужно знать, где ты живешь.</em></p>
                <h6 class="text-center">Какой у Вас город?</h6>
                <div class="input-group mb-3">
                    <input type="text"
                           v-model="deliveryManForm.city"
                           list="datalistCityOptions"
                           class="form-control p-3" placeholder="Краснодар"
                           aria-label="deliveryManForm-city" aria-describedby="deliveryManForm-city" required>
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
                <p class="mb-3"><em>Отлично! Теперь, прежде чем продолжить, пожалуйста, прочти мои условия использования и дай свое согласие на их принятие.</em> </p>
                <h6 class="text-center">Последний шаг</h6>

                <div class="card border-success mb-3">
                    <div class="card-body">
                        <p>Перед отправкой данных ознакомься с <a href="#">правилами нашего сервиса</a> и с <a
                            href="#">политикой конфиденциальности</a>.</p>
                        <div class="form-check form-switch ">
                            <input class="form-check-input"
                                   v-model="confirm"
                                   type="checkbox" role="switch" id="confirm">
                            <label class="form-check-label" for="confirm">
                                <span v-if="!deliveryManForm.sex">С правилами озакномилась</span>
                                <span v-if="deliveryManForm.sex">С правилами ознакомлен</span>
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
    <div >

<!--        <div class="container pt-3 pb-3" v-else>
            <div class="row">
                <div class="col-12">
                    <div class="card border-success">
                        <div class="card-body">
                            Поздравляем! Вы являетесь нашим Доставщиком! Вам доступны следующие возможности:
                            <ul>
                                <li>Доступ к пулу заказов и возможность зарабатывать на доставке</li>
                                <li>Учет и контроль рабочего времени</li>
                                <li>Учет и контроль рабочего дохода</li>
                                <li>Реферальная программа</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
    </div>

</template>
<script>
export default {
    data() {
        return {
            botUser: null,
            load: false,
            confirm: false,
            step: 0,
            deliveryManForm: {
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
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
        tgUser() {
            const urlParams = new URLSearchParams(this.tg.initData);
            return JSON.parse(urlParams.get('user'));
        }
    },
    mounted() {

    },
    methods: {
        nextStep(){
          this.step++;
        },

        submit() {
            this.loading = true;
            this.$store.dispatch("saveDeliveryman", {
                dataObject: {
                    bot_id: this.bot.id,
                    tg: this.tgUser,
                    form: this.deliveryManForm
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
